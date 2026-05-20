@extends('layouts.app')

@section('title', 'Inventory - Inventory System')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-cubes"></i> Inventory</h2>
    <div class="btn-group" role="group" aria-label="Inventory actions">
        <a href="{{ route('inventory.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add Item
        </a>
        <a href="{{ route('inventory.logs') }}" class="btn btn-secondary">
            <i class="fas fa-history"></i> Show Logs
        </a>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('inventory.index') }}" class="d-flex gap-2">
            <input type="text" name="search" class="form-control" placeholder="Search by item name or supplier..." value="{{ $search ?? '' }}">
            <button type="submit" class="btn btn-outline-primary">
                <i class="fas fa-search"></i> Search
            </button>
            @if(request('search'))
                <a href="{{ route('inventory.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-times"></i> Clear
                </a>
            @endif
        </form>
    </div>
</div>

<!-- Table Settings -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-cog"></i> Table Settings</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('inventory.settings.table') }}" id="tableSettingsForm">
            @csrf
            
            <div class="row">
                <div class="col-md-4">
                    <label for="table_type" class="form-label">Table Type</label>
                    <select class="form-select" id="table_type" name="table_type">
                        <option value="default" {{ ($tableSettings['table_type'] ?? 'default') == 'default' ? 'selected' : '' }}>Default</option>
                        <option value="seller" {{ ($tableSettings['table_type'] ?? 'default') == 'seller' ? 'selected' : '' }}>Seller</option>
                        <option value="storage" {{ ($tableSettings['table_type'] ?? 'default') == 'storage' ? 'selected' : '' }}>Storage</option>
                    </select>
                </div>
            </div>
            
            <div class="row mt-3">
                <div class="col-md-12">
                    <h6>Column Visibility</h6>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="show_supplier" name="show_supplier" value="1" {{ $tableSettings['show_supplier'] ?? false ? 'checked' : '' }}>
                                <label class="form-check-label" for="show_supplier">
                                    Supplier
                                </label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="show_capital_price" name="show_capital_price" value="1" {{ $tableSettings['show_capital_price'] ?? false ? 'checked' : '' }}>
                                <label class="form-check-label" for="show_capital_price">
                                    Capital Price
                                </label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="show_selling_price" name="show_selling_price" value="1" {{ $tableSettings['show_selling_price'] ?? false ? 'checked' : '' }}>
                                <label class="form-check-label" for="show_selling_price">
                                    Selling Price
                                </label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="show_category" name="show_category" value="1" {{ $tableSettings['show_category'] ?? true ? 'checked' : '' }}>
                                <label class="form-check-label" for="show_category">
                                    Category
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mt-3">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Save Settings
                </button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Item Name</th>
                    <th>Quantity</th>
                    @if($tableSettings['show_supplier'] ?? false)
                        <th>Supplier</th>
                    @endif
                    @if($tableSettings['show_capital_price'] ?? false)
                        <th>Capital Price</th>
                    @endif
                    @if($tableSettings['show_selling_price'] ?? false)
                        <th>Sell Price</th>
                    @endif
                    @if($tableSettings['show_category'] ?? true)
                        <th>Category</th>
                    @endif
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $item)
                    <tr>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->quantity_kilo }}</td>
                        @if($tableSettings['show_supplier'] ?? false)
                            <td>{{ $item->supplier->name ?? 'N/A' }}</td>
                        @endif
                        @if($tableSettings['show_capital_price'] ?? false)
                            <td>₱{{ number_format($item->capital_price ?? 0, 2) }}</td>
                        @endif
                        @if($tableSettings['show_selling_price'] ?? false)
                            <td>₱{{ number_format($item->sell_price ?? 0, 2) }}</td>
                        @endif
                        @if($tableSettings['show_category'] ?? true)
                            <td>{{ $item->category ?? 'N/A' }}</td>
                        @endif
                        <td>
                            <a href="{{ route('inventory.edit', ['item' => $item]) }}" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <form method="POST" action="{{ route('inventory.destroy', ['item' => $item]) }}" style="display:inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ 4 + ($tableSettings['show_supplier'] ?? false ? 1 : 0) + ($tableSettings['show_capital_price'] ?? false ? 1 : 0) + ($tableSettings['show_selling_price'] ?? false ? 1 : 0) + ($tableSettings['show_category'] ?? true ? 1 : 0) }}" class="text-center text-muted py-4">No items found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="d-flex justify-content-center mt-4">
    {{ $items->links() }}
</div>
@endsection

@section('scripts')
<script>
document.getElementById('table_type').addEventListener('change', function() {
    const tableType = this.value;
    const checkboxes = {
        show_supplier: document.getElementById('show_supplier'),
        show_capital_price: document.getElementById('show_capital_price'),
        show_selling_price: document.getElementById('show_selling_price'),
        show_category: document.getElementById('show_category')
    };

    // Reset all checkboxes
    Object.values(checkboxes).forEach(cb => cb.checked = false);

    // Set based on table type
    switch(tableType) {
        case 'default':
            checkboxes.show_category.checked = true;
            break;
        case 'seller':
            checkboxes.show_supplier.checked = true;
            checkboxes.show_capital_price.checked = true;
            checkboxes.show_selling_price.checked = true;
            checkboxes.show_category.checked = true;
            break;
        case 'storage':
            checkboxes.show_capital_price.checked = true;
            // selling price remains off
            checkboxes.show_category.checked = true;
            break;
    }
});
</script>
@endsection
