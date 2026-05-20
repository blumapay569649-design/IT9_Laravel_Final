@extends('layouts.app')

@section('title', 'Sales - Inventory System')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-cash-register"></i> Sales</h2>
    <a href="{{ route('sales.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Record Sale
    </a>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('sales.index') }}" class="d-flex gap-2">
            <input type="text" name="search" class="form-control" placeholder="Search by item name or creator..." value="{{ $search ?? '' }}">
            <button type="submit" class="btn btn-outline-primary">
                <i class="fas fa-search"></i> Search
            </button>
            @if(request('search'))
                <a href="{{ route('sales.index') }}" class="btn btn-outline-secondary">
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
                    <th>Item</th>
                    <th>Quantity Sold</th>
                    <th>Selling Price</th>
                    <th>Total Amount</th>
                    <th>Date</th>
                    <th>Created By</th>
                    <!-- <th>Actions</th> -->
                </tr>
            </thead>
            <tbody>
                @forelse($sales as $sale)
                    <tr>
                        <td>{{ $sale->item->name }}</td>
                        <td>{{ $sale->quantity_sold }}</td>
                        <td>₱{{ number_format($sale->selling_price, 2) }}</td>
                        <td>₱{{ number_format($sale->total_amount, 2) }}</td>
                        <td>{{ $sale->sale_date->format('M d, Y') }}</td>
                        <td>{{ $sale->creator->username ?? $sale->creator->name }}</td>
                        <!--<td>
                            <form method="POST" action="{{ route('sales.destroy', $sale) }}" style="display:inline">
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
                        <td colspan="7" class="text-center text-muted py-4">No sales found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="d-flex justify-content-center mt-4">
    {{ $sales->links() }}
</div>
@endsection
