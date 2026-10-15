<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Browse available vehicles at Quill Motors.">
    <title>Market — Quill Motors</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@400;500;600;700;800&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
</head>
<body>
<header class="navbar"><div class="container nav-inner"><a href="{{ route('home') }}" class="brand">Quill<span>.</span></a><nav class="nav-links" aria-label="Market navigation"><a href="{{ route('home') }}">Home</a><a href="{{ route('store') }}">Market</a>@auth<a href="{{ route('account') }}">Account</a>@else<a href="{{ route('login') }}">Sign in</a>@endauth</nav></div></header>
<main>
    <section class="section store-hero"><div class="container"><p class="eyebrow">The Quill Motors market</p><h1 class="section-heading">Find the car<br>that feels like you.</h1><p class="section-copy">Browse our available vehicles, then create an account to save cars and start a conversation with the seller.</p></div></section>
    <section class="section store-listing"><div class="container">
    <form class="store-filters" method="GET" action="{{ route('store') }}">
        <div class="filter-field filter-search"><label for="search">Search cars</label><input id="search" name="search" type="search" value="{{ request('search') }}" placeholder="Make or model"></div>
        <div class="filter-field"><label for="category">Category</label><select id="category" name="category"><option value="">All categories</option>@foreach ($categories as $category)<option value="{{ $category->slug }}" @selected(request('category') === $category->slug)>{{ $category->name }}</option>@endforeach</select></div>
        <div class="filter-field"><label for="year">Year</label><input id="year" name="year" type="number" min="1900" max="{{ date('Y') + 1 }}" value="{{ request('year') }}" placeholder="Any year"></div>
        <div class="filter-field"><label for="sort">Sort by</label><select id="sort" name="sort"><option value="">Newest added</option><option value="oldest" @selected(request('sort') === 'oldest')>Oldest added</option></select></div>
        <button class="btn filter-submit" type="submit">Apply filters</button>
        @if (request()->hasAny(['search', 'category', 'year', 'sort']))<a class="filter-reset" href="{{ route('store') }}">Clear</a>@endif
    </form>
    <div class="vehicle-grid">
        @forelse ($cars as $car)
            @php($image = $car->images->firstWhere('is_primary', true) ?? $car->images->first())
            <article class="vehicle-card">
                <a class="vehicle-image-wrap" href="{{ route('store.show', $car) }}" aria-label="View {{ $car->make }} {{ $car->model }}">
                    @if ($image)<img class="vehicle-image" src="{{ asset($image->image_path) }}" alt="{{ $car->make }} {{ $car->model }}" loading="lazy">@endif
                    <div class="image-fallback"><span>{{ sprintf('%02d', $car->id) }}</span><small>PHOTO COMING SOON</small></div><span class="vehicle-tag">Available</span>
                </a>
                <div class="vehicle-info"><span class="vehicle-category">{{ $car->category->name }}</span><h2 class="vehicle-name">{{ $car->make }} {{ $car->model }}</h2><p class="vehicle-year">{{ $car->year }} · {{ ucfirst($car->condition) }}</p><div class="vehicle-specs"><span>{{ number_format($car->mileage) }} KM</span><span>{{ $car->transmission ?: 'Contact us' }}</span><span>{{ $car->fuel_type ?: 'Available' }}</span></div><div class="vehicle-price-row"><span class="vehicle-price">₱ {{ number_format($car->price, 0) }}</span><a class="vehicle-arrow" href="{{ route('store.show', $car) }}" aria-label="View vehicle details">↗</a></div></div>
            </article>
        @empty
            <p class="empty-state">No vehicles are currently listed. Check back soon.</p>
        @endforelse
    </div><div class="store-pagination">{{ $cars->links() }}</div></div></section>
</main>
</body>
</html>
