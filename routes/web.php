<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\ExpenseController;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return view('welcome');
});

// Authentication Routes
Route::get('/login', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return view('auth.login');
})->name('login');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

Route::post('/login', function (Illuminate\Http\Request $request) {
    $data = $request->validate([
        'username' => 'required|string',
        'password' => 'required|string',
        'login_mode' => 'required|in:admin,employee',
    ]);

    if (!Auth::attempt(['username' => $data['username'], 'password' => $data['password']])) {
        return back()->withErrors([
            'username' => 'The provided credentials do not match our records.',
        ]);
    }

    $user = Auth::user();
    if ($data['login_mode'] === 'admin' && $user->role !== 'admin') {
        Auth::logout();
        return back()->withErrors(['username' => 'Please use employee login for this account.']);
    }

    if ($data['login_mode'] === 'employee' && $user->role === 'admin') {
        Auth::logout();
        return back()->withErrors(['username' => 'Please use admin login for this account.']);
    }

    $request->session()->regenerate();
    return redirect()->intended(route('dashboard'));
});

Route::post('/logout', function (Illuminate\Http\Request $request) {
    Auth::guard('web')->logout();
    Auth::guard('employee')->logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/');
})->name('logout');

Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/insight', [DashboardController::class, 'insight'])->middleware('role:admin')->name('insight');

    // Inventory
    Route::get('inventory/logs', [InventoryController::class, 'logs'])
        ->name('inventory.logs')
        ->middleware('role:admin,default');
    Route::resource('inventory', InventoryController::class)
        ->parameters(['inventory' => 'item'])
        ->middleware('role:admin,default');
    Route::post('inventory/settings/table', [InventoryController::class, 'updateTableSettings'])
        ->name('inventory.settings.table')
        ->middleware('role:admin,default');

    // Suppliers
    Route::resource('suppliers', SupplierController::class)->middleware('role:admin,employer');

    // Sales
    Route::resource('sales', SalesController::class)->middleware('role:admin,cashier');

    // Expenses
    Route::resource('expenses', ExpenseController::class)->middleware('role:admin');

    // Employee Management
    Route::resource('employees', EmployeeController::class)->middleware('role:admin,employer');
});


