@extends('layouts.app')

@section('title', 'Dashboard - Inventory System')

@section('content')
<h2 class="mb-4">
    <i class="fas fa-chart-line"></i> Dashboard
</h2>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stat-card revenue">
            <i class="fas fa-cubes stat-icon text-success"></i>
            <p class="stat-label">Total Items</p>
            <p class="stat-value">{{ number_format($totalItems) }}</p>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stat-card inventory">
            <i class="fas fa-warehouse stat-icon text-info"></i>
            <p class="stat-label">Total Suppliers</p>
            <p class="stat-value">{{ number_format($totalSuppliers) }}</p>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stat-card revenue">
            <i class="fas fa-arrow-up stat-icon text-success"></i>
            <p class="stat-label">Total Sales</p>
            <p class="stat-value">₱{{ number_format($totalSales, 2) }}</p>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stat-card expenses">
            <i class="fas fa-arrow-down stat-icon text-danger"></i>
            <p class="stat-label">Total Expenses</p>
            <p class="stat-value">₱{{ number_format($totalExpenses, 2) }}</p>
        </div>
    </div>
</div>

<!-- Recent Sales -->
<div class="row mb-4">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-receipt"></i> Recent Sales
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Quantity</th>
                                <th>Total</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentSales as $sale)
                                <tr>
                                    <td>{{ $sale->item->name }}</td>
                                    <td>{{ $sale->quantity_sold }}</td>
                                    <td>₱{{ number_format($sale->total_amount, 2) }}</td>
                                    <td>{{ $sale->sale_date->format('M d, Y') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">No sales recorded yet</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Expenses -->
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-receipt"></i> Recent Expenses
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Description</th>
                                <th>Category</th>
                                <th>Amount</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentExpenses as $expense)
                                <tr>
                                    <td>{{ $expense->description }}</td>
                                    <td>{{ $expense->category }}</td>
                                    <td>₱{{ number_format($expense->amount, 2) }}</td>
                                    <td>{{ $expense->expense_date->format('M d, Y') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">No expenses recorded yet</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
