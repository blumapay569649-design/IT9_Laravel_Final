<div class="sidebar">
    <div style="padding: 0 20px; margin-bottom: 30px;">
        <h5 class="text-light"><i class="fas fa-bars"></i> Menu</h5>
    </div>
    
    <nav class="nav flex-column">
        @if(auth()->user()->role === 'admin')
            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                <i class="fas fa-home me-2"></i> Dashboard
            </a>
        @endif

        @if(auth()->user()->role === 'admin' || auth()->user()->role === 'default')
            <a class="nav-link {{ request()->routeIs('inventory.*') ? 'active' : '' }}" href="{{ route('inventory.index') }}">
                <i class="fas fa-cubes me-2"></i> Inventory
            </a>
        @endif

        @if(auth()->user()->role === 'admin' || auth()->user()->role === 'employer')
            <a class="nav-link {{ request()->routeIs('suppliers.*') ? 'active' : '' }}" href="{{ route('suppliers.index') }}">
                <i class="fas fa-truck me-2"></i> Suppliers
            </a>
            <a class="nav-link {{ request()->routeIs('employees.*') ? 'active' : '' }}" href="{{ route('employees.index') }}">
                <i class="fas fa-users me-2"></i> Manage Employees
            </a>
        @endif

        @if(auth()->user()->role === 'admin' || auth()->user()->role === 'cashier')
            <a class="nav-link {{ request()->routeIs('sales.*') ? 'active' : '' }}" href="{{ route('sales.index') }}">
                <i class="fas fa-cash-register me-2"></i> Cashier / Sales
            </a>
        @endif

        @if(auth()->user()->role === 'admin')
            <a class="nav-link {{ request()->routeIs('expenses.*') ? 'active' : '' }}" href="{{ route('expenses.index') }}">
                <i class="fas fa-money-bill-wave me-2"></i> Expenses
            </a>
            <a class="nav-link {{ request()->routeIs('insight') ? 'active' : '' }}" href="{{ route('insight') }}">
                <i class="fas fa-chart-pie me-2"></i> Insights
            </a>
        @endif
    </nav>
</div>
