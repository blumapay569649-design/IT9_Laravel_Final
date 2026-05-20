@extends('layouts.app')

@section('title', 'Edit Employee')

@section('content')
<div class="card">
    <div class="card-header">
        <h3>Edit Employee Account</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('employees.update', $employee) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label class="form-label">Employee Name</label>
                <input type="text" name="employee_name" class="form-control" value="{{ old('employee_name', $employee->full_name) }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Account Name</label>
                <input type="text" name="account_name" class="form-control" value="{{ old('account_name', $employee->username) }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Email (optional)</label>
                <input type="email" name="email" class="form-control" value="{{ old('email', $employee->email) }}">
            </div>
            <div class="mb-3">
                <label class="form-label">Role</label>
                <select name="role" class="form-select" required>
                    @foreach($roles as $key => $label)
                        <option value="{{ $key }}" {{ old('role', $employee->role) === $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Password (leave blank to keep current)</label>
                <input type="password" name="password" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Confirm Password</label>
                <input type="password" name="password_confirmation" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Add Documents (optional, max 3 images total)</label>
                <input type="file" name="documents[]" class="form-control" accept="image/*" multiple>
            </div>
            @if(!empty($employee->documents))
                <div class="mb-3">
                    <label class="form-label">Current Documents</label>
                    <div class="d-flex gap-2 flex-wrap">
                        @foreach($employee->documents as $doc)
                            <div class="border rounded p-2" style="width: 120px;">
                                <img src="{{ asset('storage/'.$doc) }}" class="img-fluid" alt="Document">
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Update Employee
            </button>
            <a href="{{ route('employees.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection