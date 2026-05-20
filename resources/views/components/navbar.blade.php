<nav class="navbar navbar-dark bg-primary">
    <div class="container-fluid">
        <span class="navbar-brand mb-0 h1"><i class="fas fa-warehouse"></i> Inventory System</span>
        <div class="d-flex align-items-center">
            <span class="text-white me-3">Welcome, <strong>{{ auth()->user()->username ?? auth()->user()->name }}</strong></span>
            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-outline-light btn-sm">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </button>
            </form>
        </div>
    </div>
</nav>
