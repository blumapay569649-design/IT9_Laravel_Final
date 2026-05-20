<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $current = auth()->user();
        $ownerId = $this->getOwnerId();

        $query = User::query();
        if ($current->isEmployer()) {
            $query->where('role', '!=', 'admin')
                  ->where('role', '!=', 'employer')
                  ->where('owner_id', $ownerId);
        } else {
            // Admin should only see their own employees
            $query->where('role', '!=', 'admin')
                  ->where('owner_id', $ownerId);
        }

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('employee_name', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $employees = $query->paginate(15);
        return view('employees.index', ['employees' => $employees, 'search' => $request->search]);
    }

    public function create()
    {
        $current = auth()->user();
        $roles = ['cashier' => 'Cashier', 'default' => 'Default'];

        if ($current->isAdmin()) {
            $roles = ['employer' => 'Employer', 'cashier' => 'Cashier', 'default' => 'Default'];
        }

        return view('employees.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $current = auth()->user();

        $request->validate([
            'employee_name' => 'required|string|max:255',
            'account_name' => 'required|string|max:255|unique:users,username',
            'email' => 'nullable|email|max:255|unique:users,email',
            'role' => 'required|in:employer,cashier,default',
            'password' => 'required|string|min:8|confirmed',
            'documents.*' => 'nullable|image|max:2048',
        ]);

        if ($current->isEmployer() && $request->role === 'employer') {
            abort(403, 'Employers cannot create another employer account.');
        }

        $ownerId = $this->getOwnerId();

        $documents = [];
        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $document) {
                if (count($documents) >= 3) {
                    break;
                }
                $documents[] = $document->store('employee-documents', 'public');
            }
        }

        User::create([
            'owner_id' => $ownerId,
            'name' => $request->employee_name,
            'username' => $request->account_name,
            'email' => $request->email,
            'full_name' => $request->employee_name,
            'role' => $request->role,
            'password' => Hash::make($request->password),
            'documents' => $documents ?: null,
        ]);

        return redirect()->route('employees.index')->with('success', 'Employee account created successfully.');
    }

    public function edit(User $employee)
    {
        if ($employee->isAdmin()) {
            abort(403);
        }

        $ownerId = $this->getOwnerId();
        abort_unless($employee->owner_id === $ownerId, 403);

        $current = auth()->user();
        $roles = ['cashier' => 'Cashier', 'default' => 'Default'];

        if ($current->isAdmin()) {
            $roles = ['employer' => 'Employer', 'cashier' => 'Cashier', 'default' => 'Default'];
        }

        if ($current->isEmployer() && $employee->isEmployer()) {
            abort(403, 'You cannot edit another employer.');
        }

        return view('employees.edit', compact('employee', 'roles'));
    }

    public function update(Request $request, User $employee)
    {
        if ($employee->isAdmin()) {
            abort(403);
        }

        $ownerId = $this->getOwnerId();
        if ($employee->owner_id !== $ownerId) {
            abort(403);
        }

        $current = auth()->user();

        $request->validate([
            'employee_name' => 'required|string|max:255',
            'account_name' => 'required|string|max:255|unique:users,username,' . $employee->id,
            'email' => 'nullable|email|max:255|unique:users,email,' . $employee->id,
            'role' => 'required|in:employer,cashier,default',
            'password' => 'nullable|string|min:8|confirmed',
            'documents.*' => 'nullable|image|max:2048',
        ]);

        if ($current->isEmployer() && $request->role === 'employer') {
            abort(403, 'Employers cannot assign the employer role.');
        }

        $employee->name = $request->employee_name;
        $employee->username = $request->account_name;
        $employee->email = $request->email;
        $employee->full_name = $request->employee_name;
        $employee->role = $request->role;

        if ($request->filled('password')) {
            $employee->password = Hash::make($request->password);
        }

        if ($request->hasFile('documents')) {
            $documents = $employee->documents ?? [];
            foreach ($request->file('documents') as $document) {
                if (count($documents) >= 3) {
                    break;
                }
                $documents[] = $document->store('employee-documents', 'public');
            }
            $employee->documents = $documents;
        }

        $employee->save();

        return redirect()->route('employees.index')->with('success', 'Employee account updated successfully.');
    }

    public function destroy(User $employee)
    {
        if ($employee->isAdmin()) {
            abort(403);
        }

        $ownerId = $this->getOwnerId();
        if ($employee->owner_id !== $ownerId) {
            abort(403);
        }

        $current = auth()->user();

        if ($current->isEmployer() && $employee->isEmployer()) {
            abort(403, 'You cannot delete another employer.');
        }

        $employee->delete();

        return redirect()->route('employees.index')->with('success', 'Employee account deleted successfully.');
    }
}
