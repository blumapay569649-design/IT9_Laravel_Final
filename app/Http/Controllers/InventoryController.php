<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Item;
use App\Models\ItemLog;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class InventoryController extends Controller
{
    public function index(Request $request): View
    {
        $ownerId = $this->getOwnerId();
        $query = Item::with('supplier')->where('owner_id', $ownerId);

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhereHas('supplier', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $items = $query->paginate(15);
        
        // Get user settings
        $user = Auth::user();
        $settings = $user->settings ?? [];
        $tableSettings = $settings['inventory_table'] ?? $this->getDefaultTableSettings();

        return view('inventory.index', [
            'items' => $items, 
            'search' => $request->search,
            'tableSettings' => $tableSettings
        ]);
    }

    public function create(): View
    {
        $ownerId = $this->getOwnerId();
        $suppliers = Supplier::where('owner_id', $ownerId)->get();
        return view('inventory.create', ['suppliers' => $suppliers]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'capital_price' => 'nullable|numeric|min:0',
            'sell_price' => 'nullable|numeric|min:0',
            'quantity_kilo' => 'required|numeric|min:0',
            'category' => 'nullable|string',
            'image_path' => 'nullable|image',
        ]);

        $ownerId = $this->getOwnerId();

        if ($validated['supplier_id']) {
            $supplier = Supplier::where('owner_id', $ownerId)->findOrFail($validated['supplier_id']);
        }

        // Set defaults for optional fields
        $validated['capital_price'] = $validated['capital_price'] ?? 0;
        $validated['sell_price'] = $validated['sell_price'] ?? 0;
        $validated['supplier_id'] = $validated['supplier_id'] ?? null;

        if ($request->hasFile('image_path')) {
            $validated['image_path'] = $request->file('image_path')->store('items', 'public');
        }

        $validated['owner_id'] = $ownerId;
        $item = Item::create($validated);
        $this->recordItemLog($item, 'created', "Created item {$item->name} with quantity {$item->quantity_kilo}.");

        // Only create expense if capital_price > 0
        if ($validated['capital_price'] > 0) {
            Expense::create([
                'owner_id' => $ownerId,
                'description' => "Capital purchase for item {$item->name}",
                'amount' => $validated['capital_price'] * $validated['quantity_kilo'],
                'category' => 'Inventory',
                'expense_date' => now(),
                'created_by' => Auth::id(),
            ]);
        }

        return redirect()->route('inventory.index')->with('success', 'Item created successfully' . ($validated['capital_price'] > 0 ? ' and capital cost recorded as an expense' : ''));
    }

    public function edit(Item $item): View
    {
        $ownerId = $this->getOwnerId();
        abort_unless($item->owner_id === $ownerId, 403);
        $suppliers = Supplier::where('owner_id', $ownerId)->get();
        return view('inventory.edit', ['item' => $item, 'suppliers' => $suppliers]);
    }

    public function update(Request $request, Item $item): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'capital_price' => 'nullable|numeric|min:0',
            'sell_price' => 'nullable|numeric|min:0',
            'quantity_kilo' => 'required|numeric|min:0',
            'category' => 'nullable|string',
            'image_path' => 'nullable|image',
        ]);

        $ownerId = $this->getOwnerId();
        
        if ($item->owner_id !== $ownerId) {
            abort(403);
        }

        if ($validated['supplier_id']) {
            $supplier = Supplier::where('owner_id', $ownerId)->findOrFail($validated['supplier_id']);
        }

        // Set defaults for optional fields
        $validated['capital_price'] = $validated['capital_price'] ?? 0;
        $validated['sell_price'] = $validated['sell_price'] ?? 0;
        $validated['supplier_id'] = $validated['supplier_id'] ?? null;

        if ($request->hasFile('image_path')) {
            $validated['image_path'] = $request->file('image_path')->store('items', 'public');
        }

        // Store old quantity before updating
        $oldQuantity = $item->quantity_kilo;
        $newQuantity = $validated['quantity_kilo'];

        $item->update($validated);
        $this->recordItemLog($item, 'updated', "Updated item {$item->name}; quantity changed from {$oldQuantity} to {$newQuantity}.");

        // Record expense if quantity increased and capital_price > 0
        if ($newQuantity > $oldQuantity && $validated['capital_price'] > 0) {
            $quantityAdded = $newQuantity - $oldQuantity;
            $expenseAmount = $validated['capital_price'] * $quantityAdded;

            Expense::create([
                'owner_id' => $ownerId,
                'description' => "Additional stock for item {$item->name}",
                'amount' => $expenseAmount,
                'category' => 'Inventory',
                'expense_date' => now(),
                'created_by' => Auth::id(),
            ]);
        } elseif ($newQuantity < $oldQuantity && $validated['capital_price'] > 0) {
            $quantityRemoved = $oldQuantity - $newQuantity;
            $expenseReduction = $validated['capital_price'] * $quantityRemoved;

            Expense::create([
                'owner_id' => $ownerId,
                'description' => "Stock reduction adjustment for item {$item->name}",
                'amount' => -$expenseReduction,
                'category' => 'Inventory',
                'expense_date' => now(),
                'created_by' => Auth::id(),
            ]);
        }

        return redirect()->route('inventory.index')->with('success', 'Item updated successfully');
    }

    public function destroy(Item $item): RedirectResponse
    {
        $ownerId = $this->getOwnerId();
        abort_unless($item->owner_id === $ownerId, 403);
        $this->recordItemLog($item, 'deleted', "Deleted item {$item->name} with quantity {$item->quantity_kilo}.");
        $item->delete();
        return redirect()->route('inventory.index')->with('success', 'Item deleted successfully');
    }

    public function logs(): View
    {
        $ownerId = $this->getOwnerId();
        $logs = ItemLog::where('owner_id', $ownerId)
            ->where('created_at', '>=', now()->subDays(7))
            ->latest()
            ->get();

        return view('inventory.logs', ['logs' => $logs]);
    }

    private function recordItemLog(Item $item, string $action, string $details = null): void
    {
        ItemLog::create([
            'owner_id' => $item->owner_id,
            'user_id' => Auth::id() ?? 0,
            'item_id' => $item->id,
            'item_name' => $item->name,
            'action' => $action,
            'details' => $details,
        ]);
    }

    private function getDefaultTableSettings(): array
    {
        return [
            'table_type' => 'default',
            'show_supplier' => false,
            'show_capital_price' => false,
            'show_selling_price' => false,
            'show_category' => true,
        ];
    }

    public function updateTableSettings(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'table_type' => 'required|in:default,seller,storage',
            'show_supplier' => 'boolean',
            'show_capital_price' => 'boolean',
            'show_selling_price' => 'boolean',
            'show_category' => 'boolean',
        ]);

        // Apply quick settings based on table_type
        switch ($validated['table_type']) {
            case 'default':
                $validated['show_supplier'] = false;
                $validated['show_capital_price'] = false;
                $validated['show_selling_price'] = false;
                $validated['show_category'] = true;
                break;
            case 'seller':
                $validated['show_supplier'] = true;
                $validated['show_capital_price'] = true;
                $validated['show_selling_price'] = true;
                $validated['show_category'] = true;
                break;
            case 'storage':
                $validated['show_supplier'] = false;
                $validated['show_capital_price'] = true;
                $validated['show_selling_price'] = false;
                $validated['show_category'] = true;
                break;
        }

        $user = Auth::user();
        $settings = $user->settings ?? [];
        $settings['inventory_table'] = $validated;
        $user->update(['settings' => $settings]);

        return redirect()->route('inventory.index')->with('success', 'Table settings updated successfully');
    }
}
