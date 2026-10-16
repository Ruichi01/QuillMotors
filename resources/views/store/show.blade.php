<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $car->make }} {{ $car->model }} — Quill Motors</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@400;500;600;700;800&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
    <link rel="stylesheet" href="{{ asset('css/faq-chat.css') }}">
</head>
<body>
<header class="navbar"><div class="container nav-inner"><a href="{{ route('home') }}" class="brand">Quill<span>.</span></a><nav class="nav-links" aria-label="Vehicle navigation"><a href="{{ route('home') }}">Home</a><a href="{{ route('store') }}">Market</a>@auth<a href="{{ route('account') }}">Account</a>@else<a href="{{ route('login') }}">Sign in</a>@endauth</nav></div></header>
<main class="section vehicle-detail"><div class="container detail-grid">
    <div class="detail-image-wrap">
        @php($image = $car->images->firstWhere('is_primary', true) ?? $car->images->first())
        @if ($image)<img class="detail-image" src="{{ asset($image->image_path) }}" alt="{{ $car->make }} {{ $car->model }}">@endif
        <div class="image-fallback"><span>{{ sprintf('%02d', $car->id) }}</span><small>PHOTO COMING SOON</small></div>
        @if ($car->images->count() > 1)
            <div class="detail-gallery" aria-label="More photos">
                @foreach ($car->images as $galleryImage)
                    <img src="{{ asset($galleryImage->image_path) }}" alt="{{ $car->make }} {{ $car->model }} photo {{ $loop->iteration }}" loading="lazy" data-detail-image="{{ asset($galleryImage->image_path) }}">
                @endforeach
            </div>
        @endif
    </div>
    <div class="detail-content"><p class="eyebrow">{{ $car->category->name }}</p><h1 class="section-heading">{{ $car->make }}<br>{{ $car->model }}</h1><p class="detail-price">₱ {{ number_format($car->price, 0) }}</p><p class="detail-description">{{ $car->description ?: 'Contact Quill Motors for more information about this vehicle.' }}</p><div class="detail-spec-grid"><div><span>Year</span><strong>{{ $car->year }}</strong></div><div><span>Odometer</span><strong>{{ number_format($car->mileage) }} km</strong></div><div><span>Transmission</span><strong>{{ $car->transmission ?: 'Not specified' }}</strong></div><div><span>Fuel type</span><strong>{{ $car->fuel_type ?: 'Not specified' }}</strong></div><div><span>Condition</span><strong>{{ ucfirst($car->condition) }}</strong></div><div><span>Status</span><strong>{{ ucfirst($car->status) }}</strong></div></div><a class="btn" href="{{ route('home', ['#' => 'contact']) }}">Ask about this car <span>↗</span></a><a class="detail-back" href="{{ route('store') }}">← Back to the market</a></div>
</div></main>
@include('partials.faq-chat')
<script src="{{ asset('js/landing.js') }}" defer></script>
</body>
</html>
