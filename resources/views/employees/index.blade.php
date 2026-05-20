@extends('layouts.app')

@section('title', 'Manage Employees')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2>Manage Employees</h2>
        <p class="text-muted">Create, edit, and remove employee accounts.</p>
    </div>
    <a href="{{ route('employees.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Add Employee
    </a>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('employees.index') }}" class="d-flex gap-2">
            <input type="text" name="search" class="form-control" placeholder="Search by name, username, or email..." value="{{ $search ?? '' }}">
            <button type="submit" class="btn btn-outline-primary">
                <i class="fas fa-search"></i> Search
            </button>
            @if(request('search'))
                <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-times"></i> Clear
                </a>
            @endif
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Account Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Owner</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($employees as $employee)
                        <tr>
                            <td>{{ $employee->full_name }}</td>
                            <td>{{ $employee->username }}</td>
                            <td>{{ $employee->email ?? '-' }}</td>
                            <td>{{ ucfirst($employee->role) }}</td>
                            <td>{{ optional($employee->owner)->username ?? 'System' }}</td>
                            <td>
                                <a href="{{ route('employees.edit', $employee) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <form action="{{ route('employees.destroy', $employee) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Are you sure you want to delete this employee account?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">No employee accounts found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $employees->links() }}
        </div>
    </div>
</div>
@endsection