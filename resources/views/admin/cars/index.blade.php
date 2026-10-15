@extends('admin.layout')
@section('title', 'Cars')
@section('heading', 'Cars')
@section('kicker', 'Manage / Inventory')
@section('content')
<div class="admin-page-actions"><p>Keep your marketplace inventory current.</p><a class="admin-button" href="{{ route('admin.cars.create') }}">＋ Add car</a></div>
<section class="admin-panel table-panel"><table class="admin-table"><thead><tr><th>Vehicle</th><th>Category</th><th>Price</th><th>Status</th><th>Added</th><th></th></tr></thead><tbody>@forelse ($cars as $car)<tr><td><strong>{{ $car->make }} {{ $car->model }}</strong><small>{{ $car->year }} · {{ number_format($car->mileage) }} km</small></td><td>{{ $car->category->name }}</td><td>₱ {{ number_format($car->price, 0) }}</td><td><span class="status status-{{ $car->status }}">{{ ucfirst($car->status) }}</span></td><td>{{ $car->created_at->format('M j, Y') }}</td><td class="table-actions"><a href="{{ route('admin.cars.edit', $car) }}">Edit</a><form method="POST" action="{{ route('admin.cars.destroy', $car) }}" onsubmit="return confirm('Remove this car?')">@csrf @method('DELETE')<button>Delete</button></form></td></tr>@empty<tr><td colspan="6" class="empty-state">No cars in the inventory yet.</td></tr>@endforelse</tbody></table><div class="admin-pagination">{{ $cars->links() }}</div></section>
@endsection
