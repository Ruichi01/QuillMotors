@extends('admin.layout')
@section('title', $car->exists ? 'Edit car' : 'Add car')
@section('heading', $car->exists ? 'Edit car' : 'Add a car')
@section('kicker', 'Manage / Inventory')
@section('content')
<form class="admin-form admin-panel" method="POST" enctype="multipart/form-data" action="{{ $car->exists ? route('admin.cars.update', $car) : route('admin.cars.store') }}">@csrf @if($car->exists) @method('PUT') @endif
    <div class="form-section-title"><p class="panel-kicker">Vehicle details</p><h2>Tell buyers about this car</h2></div>
    <div class="admin-form-grid"><div class="admin-field"><label for="make">Make</label><input id="make" name="make" value="{{ old('make', $car->make) }}" required></div><div class="admin-field"><label for="model">Model</label><input id="model" name="model" value="{{ old('model', $car->model) }}" required></div><div class="admin-field"><label for="category_id">Category</label><select id="category_id" name="category_id" required><option value="">Choose category</option>@foreach($categories as $category)<option value="{{ $category->id }}" @selected(old('category_id', $car->category_id) == $category->id)>{{ $category->name }}</option>@endforeach</select></div><div class="admin-field"><label for="year">Year</label><input id="year" name="year" type="number" value="{{ old('year', $car->year) }}" required></div><div class="admin-field"><label for="price">Current price (₱)</label><input id="price" name="price" type="number" min="0" step="0.01" value="{{ old('price', $car->price) }}" required></div><div class="admin-field"><label>Previous price</label><input value="{{ $car->original_price ? '₱ '.number_format($car->original_price, 2) : 'No previous price' }}" disabled></div><div class="admin-field"><label for="mileage">Odometer (km)</label><input id="mileage" name="mileage" type="number" min="0" value="{{ old('mileage', $car->mileage ?? 0) }}" required></div><div class="admin-field"><label for="transmission">Transmission</label><input id="transmission" name="transmission" value="{{ old('transmission', $car->transmission) }}" placeholder="Automatic"></div><div class="admin-field"><label for="fuel_type">Fuel type</label><input id="fuel_type" name="fuel_type" value="{{ old('fuel_type', $car->fuel_type) }}" placeholder="Gasoline"></div><div class="admin-field"><label for="condition">Condition</label><input id="condition" name="condition" value="{{ old('condition', $car->condition ?: 'used') }}" required></div><div class="admin-field"><label for="status">Status</label><select id="status" name="status" required><option value="available" @selected(old('status', $car->status ?: 'available') === 'available')>Available</option><option value="reserved" @selected(old('status', $car->status) === 'reserved')>Reserved</option><option value="sold" @selected(old('status', $car->status) === 'sold')>Sold</option></select></div></div>
    <div class="admin-field"><label for="description">Description</label><textarea id="description" name="description" rows="6">{{ old('description', $car->description) }}</textarea></div>
    <div class="admin-field"><label for="photos">Photos <small>Choose up to 4 images total (JPG, PNG, or WebP; 5 MB each)</small></label><input id="photos" name="photos[]" type="file" accept="image/jpeg,image/png,image/webp" multiple></div>
    @if ($car->exists && $car->images->isNotEmpty())<div class="admin-photo-list">@foreach($car->images as $image)<div class="admin-photo-item" data-image-item="{{ $image->id }}"><img src="{{ asset($image->image_path) }}" alt="Current {{ $car->make }} {{ $car->model }} photo"><button class="admin-photo-remove" type="button" data-remove-image="{{ $image->id }}">Remove</button>@if($image->is_primary)<small>Primary photo</small>@endif</div>@endforeach</div>@endif
    <div id="removed-images"></div>
    <div class="admin-form-actions"><a class="admin-cancel" href="{{ route('admin.cars.index') }}">Cancel</a><button class="admin-button" type="submit">{{ $car->exists ? 'Save changes' : 'Add car' }}</button></div>
</form>
@if ($car->exists)
<script>
document.querySelectorAll('[data-remove-image]').forEach((button) => {
    button.addEventListener('click', () => {
        const imageId = button.dataset.removeImage;
        const item = document.querySelector(`[data-image-item="${imageId}"]`);
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'removed_image_ids[]';
        input.value = imageId;
        document.getElementById('removed-images').appendChild(input);
        item.classList.add('is-pending-removal');
        button.disabled = true;
        button.textContent = 'Will remove on save';
    });
});
</script>
@endif
@endsection
