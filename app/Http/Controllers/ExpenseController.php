<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ExpenseController extends Controller
{
    public function index(Request $request): View
    {
        $ownerId = $this->getOwnerId();
        $query = Expense::with('creator')->where('owner_id', $ownerId);

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%")
                  ->orWhereHas('creator', function($q) use ($search) {
                      $q->where('employee_name', 'like', "%{$search}%");
                  });
            });
        }

        $expenses = $query->paginate(15);
        return view('expenses.index', ['expenses' => $expenses, 'search' => $request->search]);
    }

    public function create(): View
    {
        return view('expenses.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'description' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'category' => 'nullable|string',
        ]);

        $ownerId = $this->getOwnerId();
        $validated['owner_id'] = $ownerId;
        $validated['created_by'] = auth()->id();
        $validated['expense_date'] = now();

        Expense::create($validated);

        return redirect()->route('expenses.index')->with('success', 'Expense recorded successfully');
    }

    public function destroy(Expense $expense): RedirectResponse
    {
        $ownerId = $this->getOwnerId();
        abort_unless($expense->owner_id === $ownerId, 403);
        $expense->delete();
        return redirect()->route('expenses.index')->with('success', 'Expense deleted successfully');
    }
}
