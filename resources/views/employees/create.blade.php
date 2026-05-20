@extends('layouts.app')

@section('title', 'Create Employee')

@section('content')
<div class="card">
    <div class="card-header">
        <h3>Create Employee Account</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('employees.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label class="form-label">Employee Name</label>
                <input type="text" name="employee_name" class="form-control" value="{{ old('employee_name') }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Account Name</label>
                <input type="text" name="account_name" class="form-control" value="{{ old('account_name') }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Email (optional)</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}">
            </div>
            <div class="mb-3">
                <label class="form-label">Role</label>
                <select name="role" class="form-select" required>
                    @foreach($roles as $key => $label)
                        <option value="{{ $key }}" {{ old('role') === $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Confirm Password</label>
                <input type="password" name="password_confirmation" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Documents (optional, max 3 images)</label>
                <input type="file" name="documents[]" class="form-control" accept="image/*" multiple>
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Create Employee
            </button>
            <a href="{{ route('employees.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection