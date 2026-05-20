@extends('layouts.app')

@section('title', 'Expenses - Inventory System')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-money-bill-wave"></i> Expenses</h2>
    <a href="{{ route('expenses.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Record Expense
    </a>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('expenses.index') }}" class="d-flex gap-2">
            <input type="text" name="search" class="form-control" placeholder="Search by description, category, or creator..." value="{{ $search ?? '' }}">
            <button type="submit" class="btn btn-outline-primary">
                <i class="fas fa-search"></i> Search
            </button>
            @if(request('search'))
                <a href="{{ route('expenses.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-times"></i> Clear
                </a>
            @endif
        </form>
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Description</th>
                    <th>Category</th>
                    <th>Amount</th>
                    <th>Date</th>
                    <th>Created By</th>
                    <!-- <th>Actions</th> -->
                </tr>
            </thead>
            <tbody>
                @forelse($expenses as $expense)
                    <tr>
                        <td>{{ $expense->description }}</td>
                        <td>{{ $expense->category ?? '-' }}</td>
                        <td>₱{{ number_format($expense->amount, 2) }}</td>
                        <td>{{ $expense->expense_date->format('M d, Y') }}</td>
                        <td>{{ $expense->creator->username ?? $expense->creator->name }}</td>
                        <!-- <td>
                            <form method="POST" action="{{ route('expenses.destroy', $expense) }}" style="display:inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </form>
                        </td> -->
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">No expenses found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="d-flex justify-content-center mt-4">
    {{ $expenses->links() }}
</div>
@endsection
