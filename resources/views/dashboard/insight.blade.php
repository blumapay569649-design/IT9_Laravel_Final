@extends('layouts.app')

@section('title', 'Insights - Inventory System')

@section('content')
<h2 class="mb-4">
    <i class="fas fa-chart-pie"></i> Business Insights
</h2>

<!-- Key Metrics -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stat-card revenue">
            <i class="fas fa-cubes stat-icon"></i>
            <p class="stat-label">Total Items</p>
            <p class="stat-value">{{ number_format($totalItems) }}</p>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stat-card inventory">
            <i class="fas fa-truck stat-icon"></i>
            <p class="stat-label">Total Suppliers</p>
            <p class="stat-value">{{ number_format($totalSuppliers) }}</p>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stat-card revenue">
            <i class="fas fa-arrow-up stat-icon"></i>
            <p class="stat-label">Total Sales Revenue</p>
            <p class="stat-value">₱{{ number_format($totalSales, 2) }}</p>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stat-card expenses">
            <i class="fas fa-arrow-down stat-icon"></i>
            <p class="stat-label">Total Expenses</p>
            <p class="stat-value">₱{{ number_format($totalExpenses, 2) }}</p>
        </div>
    </div>
</div>

<!-- Profitability Analysis -->
<div class="row mb-4">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body text-center">
                <h6 class="text-muted mb-2">Net Profit</h6>
                <h2 class="{{ $profit >= 0 ? 'text-success' : 'text-danger' }}">
                    ₱{{ number_format($profit, 2) }}
                </h2>
                <small class="text-muted">
                    @if($profit >= 0)
                        <i class="fas fa-arrow-up"></i> Positive
                    @else
                        <i class="fas fa-arrow-down"></i> Negative
                    @endif
                </small>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body text-center">
                <h6 class="text-muted mb-2">Profit Margin</h6>
                <h2 class="text-primary">
                    {{ $totalSales > 0 ? number_format(($profit / $totalSales * 100), 2) : 0 }}%
                </h2>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body text-center">
                <h6 class="text-muted mb-2">Revenue to Expense Ratio</h6>
                <h2 class="text-info">
                    {{ $totalExpenses > 0 ? number_format(($totalSales / $totalExpenses), 2) : 0 }}:1
                </h2>
            </div>
        </div>
    </div>
</div>

<!-- Summary Cards -->
<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-info-circle"></i> Summary
            </div>
            <div class="card-body">
                <p><strong>Total Items in Inventory:</strong> {{ number_format($totalItems) }}</p>
                <p><strong>Total Suppliers:</strong> {{ number_format($totalSuppliers) }}</p>
                <hr>
                <p><strong>Total Revenue Generated:</strong> ₱{{ number_format($totalSales, 2) }}</p>
                <p><strong>Total Expenses:</strong> ₱{{ number_format($totalExpenses, 2) }}</p>
                <p><strong>Net Profit:</strong> <strong class="{{ $profit >= 0 ? 'text-success' : 'text-danger' }}">₱{{ number_format($profit, 2) }}</strong></p>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-chart-line"></i> Performance Indicators
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label>Profitability Status</label>
                    <div class="progress">
                        <div class="progress-bar {{ $profit >= 0 ? 'bg-success' : 'bg-danger' }}" style="width: {{ min(abs($profit / ($totalSales ?: 1) * 100), 100) }}%"></div>
                    </div>
                    <small class="text-muted">{{ $profit >= 0 ? 'Profitable' : 'Operating at a Loss' }}</small>
                </div>
                <div class="mb-3">
                    <label>Expense Ratio</label>
                    <div class="progress">
                        <div class="progress-bar bg-warning" style="width: {{ min($totalSales > 0 ? ($totalExpenses / $totalSales * 100) : 0, 100) }}%"></div>
                    </div>
                    <small class="text-muted">{{ $totalSales > 0 ? round($totalExpenses / $totalSales * 100) : 0 }}% of Revenue</small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts -->
<div class="row mt-4">
    <div class="col-lg-6 mb-4">
        <div class="card h-100">
            <div class="card-header">
                <i class="fas fa-chart-bar"></i> Sales vs Expenses
            </div>
            <div class="card-body">
                <canvas id="salesExpensesChart" height="220"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-6 mb-4">
        <div class="card h-100">
            <div class="card-header">
                <i class="fas fa-chart-pie"></i> Sales vs Expenses Share
            </div>
            <div class="card-body d-flex align-items-center justify-content-center">
                <canvas id="salesExpensesPieChart" height="220" style="max-width: 100%;"></canvas>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const totalSales = Number(@json($totalSales)) || 0;
        const totalExpenses = Number(@json($totalExpenses)) || 0;

        const salesExpensesCtx = document.getElementById('salesExpensesChart');
        if (salesExpensesCtx) {
            new Chart(salesExpensesCtx, {
                type: 'bar',
                data: {
                    labels: ['Sales', 'Expenses'],
                    datasets: [{
                        label: 'Amount (₱)',
                        data: [totalSales, totalExpenses],
                        backgroundColor: ['rgba(54, 162, 235, 0.7)', 'rgba(255, 99, 132, 0.7)'],
                        borderColor: ['rgba(54, 162, 235, 1)', 'rgba(255, 99, 132, 1)'],
                        borderWidth: 1,
                    }],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return '₱' + value.toLocaleString();
                                }
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false,
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return '₱' + Number(context.parsed.y).toLocaleString();
                                }
                            }
                        }
                    }
                }
            });
        }

        const pieCtx = document.getElementById('salesExpensesPieChart');
        if (pieCtx) {
            new Chart(pieCtx, {
                type: 'pie',
                data: {
                    labels: ['Sales', 'Expenses'],
                    datasets: [{
                        data: [totalSales, totalExpenses],
                        backgroundColor: ['rgba(54, 162, 235, 0.8)', 'rgba(255, 99, 132, 0.8)'],
                        borderColor: ['#ffffff', '#ffffff'],
                        borderWidth: 2,
                    }],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const value = context.parsed;
                                    const total = totalSales + totalExpenses;
                                    const percentage = total ? ((value / total) * 100).toFixed(2) : 0;
                                    return context.label + ': ₱' + Number(value).toLocaleString() + ' (' + percentage + '%)';
                                }
                            }
                        }
                    }
                }
            });
        }
    });
</script>
@endsection
