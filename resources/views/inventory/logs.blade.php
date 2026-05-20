@extends('layouts.app')

@section('title', 'Item Logs - Inventory System')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-history"></i> Item Log History</h2>
    <a href="{{ route('inventory.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Back to Inventory
    </a>
</div>

<div class="card mb-4">
    <div class="card-body">
        <p class="mb-0">Showing item activity for the last 7 days. This includes add, edit, and delete actions.</p>
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Date</th>
                    <th>Action</th>
                    <th>Item</th>
                    <th>User</th>
                    <th>Details</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                    <tr>
                        <td>{{ $log->created_at->format('M d, Y H:i') }}</td>
                        <td>{{ ucfirst($log->action) }}</td>
                        <td>{{ $log->item_name }}</td>
                        <td>{{ $log->user->name ?? 'System' }}</td>
                        <td>{{ $log->details ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">No item log entries found for the last 7 days.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
