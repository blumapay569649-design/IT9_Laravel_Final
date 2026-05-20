<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Sale;
use App\Models\Supplier;
use App\Models\Expense;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View|RedirectResponse
    {
        $user = auth()->user();

        if ($user->role === 'cashier') {
            return redirect()->route('sales.index');
        } elseif ($user->role === 'employer') {
            return redirect()->route('suppliers.index');
        } elseif ($user->role === 'default') {
            return redirect()->route('inventory.index');
        }

        // Admin role: show dashboard
        $ownerId = $this->getOwnerId();
        $totalItems = Item::where('owner_id', $ownerId)->count();
        $totalSuppliers = Supplier::where('owner_id', $ownerId)->count();
        $totalSales = Sale::where('owner_id', $ownerId)->sum('total_amount');
        $totalExpenses = Expense::where('owner_id', $ownerId)->sum('amount');
        
        $recentSales = Sale::with(['item', 'creator'])->where('owner_id', $ownerId)->latest()->limit(5)->get();
        $recentExpenses = Expense::with('creator')->where('owner_id', $ownerId)->latest()->limit(5)->get();
        
        return view('dashboard.index', [
            'totalItems' => $totalItems,
            'totalSuppliers' => $totalSuppliers,
            'totalSales' => $totalSales,
            'totalExpenses' => $totalExpenses,
            'recentSales' => $recentSales,
            'recentExpenses' => $recentExpenses,
        ]);
    }

    public function insight(): View
    {
        $ownerId = $this->getOwnerId();
        $totalItems = Item::where('owner_id', $ownerId)->count();
        $totalSuppliers = Supplier::where('owner_id', $ownerId)->count();
        $totalSales = Sale::where('owner_id', $ownerId)->sum('total_amount');
        $totalExpenses = Expense::where('owner_id', $ownerId)->sum('amount');
        $profit = $totalSales - $totalExpenses;
        
        return view('dashboard.insight', [
            'totalItems' => $totalItems,
            'totalSuppliers' => $totalSuppliers,
            'totalSales' => $totalSales,
            'totalExpenses' => $totalExpenses,
            'profit' => $profit,
        ]);
    }
}
