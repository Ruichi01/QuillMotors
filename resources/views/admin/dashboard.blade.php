@extends('admin.layout')
@section('title', 'Dashboard')
@section('heading', 'Good day, '.auth()->user()->name.'.')
@section('kicker', 'Overview / '.now()->format('F j, Y'))
@section('content')
<div class="admin-stats">
    <div class="stat-card"><span class="stat-label">Total cars</span><strong>{{ $stats['cars'] }}</strong><small>{{ $stats['availableCars'] }} available now</small></div>
    <div class="stat-card"><span class="stat-label">Customers</span><strong>{{ $stats['users'] }}</strong><small>Registered accounts</small></div>
    <div class="stat-card"><span class="stat-label">Pending inquiries</span><strong>{{ $stats['inquiries'] }}</strong><small>Need your attention</small></div>
    <div class="stat-card"><span class="stat-label">Categories</span><strong>{{ $stats['categories'] }}</strong><small>Inventory groupings</small></div>
</div>
<div class="admin-dashboard-grid">
    <section class="admin-panel graph-panel"><div class="panel-heading"><div><p class="panel-kicker">Buyer interest</p><h2>Inquiry activity</h2></div><span class="panel-note">Last 7 months</span></div><div class="bar-chart">@php($maxInquiry = max(1, $inquiryCounts->max('count')))<div class="bars">@foreach ($inquiryCounts as $month)<div class="bar-column"><div class="bar-value">{{ $month['count'] }}</div><div class="bar" style="height: {{ max(8, ($month['count'] / $maxInquiry) * 100) }}%"></div><span>{{ $month['label'] }}</span></div>@endforeach</div></div></section>
    <section class="admin-panel"><div class="panel-heading"><div><p class="panel-kicker">Quick actions</p><h2>Manage Quill</h2></div></div><div class="quick-actions"><a href="{{ route('admin.cars.create') }}"><span>＋</span><strong>Add a car</strong><small>Create a new listing</small></a><a href="{{ route('admin.categories.create') }}"><span>＋</span><strong>Add a category</strong><small>Organize inventory</small></a><a href="{{ route('admin.users.index') }}"><span>→</span><strong>View customers</strong><small>Review account activity</small></a></div></section>
</div>
<div class="admin-dashboard-grid lower-grid">
    <section class="admin-panel"><div class="panel-heading"><div><p class="panel-kicker">Latest inventory</p><h2>Recently added cars</h2></div><a class="panel-link" href="{{ route('admin.cars.index') }}">View all →</a></div><div class="mini-list">@forelse ($recentCars as $car)<a href="{{ route('admin.cars.edit', $car) }}"><span class="mini-avatar">{{ strtoupper(substr($car->make, 0, 1)) }}</span><span><strong>{{ $car->make }} {{ $car->model }}</strong><small>{{ $car->category->name }} · {{ $car->year }}</small></span><b>₱ {{ number_format($car->price, 0) }}</b></a>@empty<p class="empty-state">No cars yet.</p>@endforelse</div></section>
    <section class="admin-panel"><div class="panel-heading"><div><p class="panel-kicker">Customer activity</p><h2>Latest inquiries</h2></div></div><div class="mini-list">@forelse ($recentInquiries as $inquiry)<div><span class="mini-avatar">{{ strtoupper(substr($inquiry->name, 0, 1)) }}</span><span><strong>{{ $inquiry->name }}</strong><small>{{ $inquiry->car?->make }} {{ $inquiry->car?->model }}</small></span><em class="status status-{{ $inquiry->status }}">{{ ucfirst($inquiry->status) }}</em></div>@empty<p class="empty-state">No inquiries yet.</p>@endforelse</div></section>
</div>
@endsection
