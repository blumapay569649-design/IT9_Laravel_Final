<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class SalesController extends Controller
{
    public function index(Request $request): View
    {
        $ownerId = $this->getOwnerId();
        $query = Sale::with(['item', 'creator'])->where('owner_id', $ownerId);

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('item', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                })->orWhereHas('creator', function($q) use ($search) {
                    $q->where('employee_name', 'like', "%{$search}%");
                });
            });
        }

        $sales = $query->paginate(15);
        return view('sales.index', ['sales' => $sales, 'search' => $request->search]);
    }

    public function create(): View
    {
        $ownerId = $this->getOwnerId();
        $items = Item::where('owner_id', $ownerId)->get();
        return view('sales.create', ['items' => $items]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'item_id' => 'required|exists:items,id',
            'quantity_sold' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
        ]);

        $item = Item::where('owner_id', $this->getOwnerId())->findOrFail($validated['item_id']);
        if ($validated['selling_price'] < $item->capital_price) {
            return back()->withErrors([
                'selling_price' => "Selling price must be at least the capital price (₱" . number_format((float)$item->capital_price, 2) . ").",
            ])->withInput();
        }

        if ($validated['quantity_sold'] > $item->quantity_kilo) {
            return back()->withErrors([
                'quantity_sold' => "Quantity sold cannot exceed available inventory (Remaining: {$item->quantity_kilo} kg).",
            ])->withInput();
        }

        $validated['total_amount'] = $validated['quantity_sold'] * $validated['selling_price'];
        $ownerId = $this->getOwnerId();
        $validated['owner_id'] = $ownerId;
        $validated['created_by'] = Auth::id();
        $validated['sale_date'] = now();

        Sale::create($validated);

        // Update item quantity
        $item->quantity_kilo -= $validated['quantity_sold'];
        $item->save();

        return redirect()->route('sales.index')->with('success', 'Sale recorded successfully');
    }

    public function destroy(Sale $sale): RedirectResponse
    {
        $ownerId = $this->getOwnerId();
        abort_unless($sale->owner_id === $ownerId, 403);
        $sale->delete();
        return redirect()->route('sales.index')->with('success', 'Sale deleted successfully');
    }
}
