@extends('layouts.app')

@section('title', 'Record Sale - Inventory System')

@section('content')
<div class="mb-4">
    <h2><i class="fas fa-plus"></i> Record New Sale</h2>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('sales.store') }}">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="item_id" class="form-label">Item *</label>
                        <select class="form-select @error('item_id') is-invalid @enderror" id="item_id" name="item_id" required>
                            <option value="">Select Item</option>
                            @foreach($items as $item)
                                <option value="{{ $item->id }}" data-capital-price="{{ $item->capital_price }}" data-sell-price="{{ $item->sell_price }}" data-quantity="{{ $item->quantity_kilo }}" {{ old('item_id') == $item->id ? 'selected' : '' }}>
                                    {{ $item->name }} - Sell ₱{{ number_format($item->sell_price, 2) }} / Capital ₱{{ number_format($item->capital_price, 2) }} (Remaining: {{ $item->quantity_kilo }} kg)
                                </option>
                            @endforeach
                        </select>
                        @error('item_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-3">
                        <label for="quantity_sold" class="form-label">Quantity Sold (qty.kg) *</label>
                        <input type="number" step="0.01" class="form-control @error('quantity_sold') is-invalid @enderror" id="quantity_sold" name="quantity_sold" value="{{ old('quantity_sold') }}" required>
                        @error('quantity_sold') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-3">
                        <label for="selling_price" class="form-label">Selling Price per Unit *</label>
                        <input type="number" step="0.01" class="form-control @error('selling_price') is-invalid @enderror" id="selling_price" name="selling_price" value="{{ old('selling_price') }}" required>
                        @error('selling_price') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        <div class="form-text" id="price-note">Selling price must be greater than or equal to the selected item's capital price.</div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Record Sale
                        </button>
                        <a href="{{ route('sales.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('js')
<script>
    const itemSelect = document.getElementById('item_id');
    const sellingPriceInput = document.getElementById('selling_price');
    const priceNote = document.getElementById('price-note');

    function updatePriceHints() {
        const option = itemSelect.selectedOptions[0];
        if (!option || !option.dataset.sellPrice) {
            priceNote.textContent = 'Selling price must be greater than or equal to the selected item\'s capital price.';
            return;
        }

        const capitalPrice = parseFloat(option.dataset.capitalPrice);
        const sellPrice = parseFloat(option.dataset.sellPrice);
        if (!isNaN(sellPrice) && !sellingPriceInput.value) {
            sellingPriceInput.value = sellPrice.toFixed(2);
        }
        priceNote.textContent = `Minimum sell price: ₱${capitalPrice.toFixed(2)}.`;
    }

    itemSelect.addEventListener('change', updatePriceHints);
    updatePriceHints();
</script>
@endsection
