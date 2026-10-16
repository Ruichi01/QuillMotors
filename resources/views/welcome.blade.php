<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Discover extraordinary automobiles at Quill Motors.">
    <meta name="theme-color" content="#101112">
    <title>Quill MOTORS — Extraordinary by Nature</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@400;500;600;700;800&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
    <link rel="stylesheet" href="{{ asset('css/faq-chat.css') }}">
</head>
<body>
<header class="navbar">
    <div class="container nav-inner">
        <a href="#home" class="brand" aria-label="Quill Motors home">Quill<span>.</span></a>
        <button class="nav-toggle" id="navToggle" aria-expanded="false" aria-controls="navLinks" aria-label="Toggle navigation">☰ MENU</button>
        <nav class="nav-links" id="navLinks" aria-label="Main navigation">
            <a href="#home">Home</a><a href="#collection">Collection</a><a href="#about">Our Story</a><a href="#experience">Experience</a><a href="#contact">Contact</a>
            @auth
                <a href="{{ route('account') }}">Account</a>
            @else
                <a href="{{ route('login') }}">Sign in</a>
            @endauth
        </nav>
    </div>
</header>

<main>
<section class="hero" id="home">
    <!-- IMAGE: 1920 × 1000 px, wide landscape. Keep the car toward the RIGHT side. -->
    <img class="hero-image" src="{{ asset('images/hero-car.jpg') }}" alt="Featured performance car" onerror="this.style.display='none'">
    <div class="hero-fallback" aria-hidden="true"></div>
    <div class="container hero-container">
        <div class="hero-content">
            <p class="eyebrow">The art of the drive</p>
            <h1>NOT MADE<br>TO <span>BLEND IN.</span></h1>
            <p class="hero-description">Some cars take you places. Others make every journey unforgettable. Discover your next automotive obsession.</p>
            <div class="hero-actions">
                <a href="{{ route('store') }}" class="btn">Browse the store <span>↗</span></a>
                <a href="#about" class="btn btn-outline">Discover Quill</a>
            </div>
        </div>
    </div>
    <div class="hero-bottom-label"><span></span> PERFORMANCE · PRECISION · PRESTIGE</div>
</section>

<section class="values">
    <div class="container values-grid">
        <article class="value-item"><span class="value-number">01 / PERFORMANCE</span><h3>Engineered to excite</h3><p>Exceptional machines for people who love the drive.</p></article>
        <article class="value-item"><span class="value-number">02 / CONFIDENCE</span><h3>Driven by trust</h3><p>A thoughtful buying experience from first look to handover.</p></article>
        <article class="value-item"><span class="value-number">03 / INDIVIDUALITY</span><h3>Find your statement</h3><p>Discover a vehicle that reflects your own ambitions.</p></article>
    </div>
</section>

<section class="section" id="collection">
    <div class="container">
        <div class="inventory-header">
            <div><p class="eyebrow">Recently added</p><h2 class="section-heading">Find your next<br>signature drive.</h2><p class="section-copy">Explore the latest automobiles added to the Quill Motors marketplace.</p></div>
        </div>
        <div class="inventory-carousel">
            <button class="carousel-arrow carousel-arrow-prev" type="button" data-carousel-direction="-1" aria-label="Previous recently added cars">←</button>
            <div class="inventory-viewport">
                <div class="inventory-track" id="recentVehicles">
                    @forelse ($recentCars as $car)
                        @php($image = $car->images->firstWhere('is_primary', true) ?? $car->images->first())
                        <article class="vehicle-card">
                            <a href="{{ route('store.show', $car) }}" class="vehicle-image-wrap" aria-label="View {{ $car->make }} {{ $car->model }}">
                                @if ($image)
                                    <img class="vehicle-image" src="{{ asset($image->image_path) }}" alt="{{ $car->make }} {{ $car->model }}" loading="lazy">
                                @endif
                                <div class="image-fallback"><span>{{ sprintf('%02d', $loop->iteration) }}</span><small>PHOTO COMING SOON</small></div><span class="vehicle-tag">Recently added</span>
                            </a>
                            <div class="vehicle-info"><span class="vehicle-category">{{ $car->category->name }}</span><h3 class="vehicle-name">{{ $car->make }} {{ $car->model }}</h3><p class="vehicle-year">{{ $car->year }} · {{ ucfirst($car->condition) }}</p><div class="vehicle-specs"><span>{{ number_format($car->mileage) }} KM</span><span>{{ $car->transmission ?: 'Contact us' }}</span><span>{{ $car->fuel_type ?: 'Available' }}</span></div><a href="{{ route('store.show', $car) }}" class="vehicle-price-row"><span class="vehicle-price">₱ {{ number_format($car->price, 0) }}</span><span class="vehicle-arrow">↗</span></a></div>
                        </article>
                    @empty
                        <p class="empty-state">New vehicles are being prepared. Visit the market soon.</p>
                    @endforelse
                    <a class="see-more-card" href="{{ route('store') }}">
                        <span class="see-more-mark">↗</span>
                        <span class="eyebrow">The full market</span>
                        <strong>See more<br>cars.</strong>
                        <span class="see-more-link">Browse all vehicles</span>
                    </a>
                </div>
            </div>
            <button class="carousel-arrow carousel-arrow-next" type="button" data-carousel-direction="1" aria-label="Next recently added cars">→</button>
        </div>
    </div>
</section>

<section class="section story" id="about">
    <div class="container story-grid">
        <div class="story-image-wrap">
            <!-- IMAGE: 1000 × 1250 px portrait. Show a showroom or car-detail photo. -->
            <img class="story-image" src="{{ asset('images/showroom.jpg') }}" alt="Quill Motors showroom" loading="lazy" onerror="this.style.display='none'">
            <div class="story-fallback"><span>Quill / ATELIER</span><small>SHOWROOM IMAGE · 1000 × 1250 PX</small></div>
            <div class="story-image-label">BEYOND THE ORDINARY</div>
        </div>
        <div class="story-copy">
            <p class="eyebrow">A different kind of dealership</p><h2 class="section-heading">MORE THAN<br>FOUR WHEELS.</h2>
            <p class="section-copy">We believe buying a car should feel as special as driving one. Every detail matters — from discovering the right vehicle to the moment you take the keys.</p>
            <p class="section-copy">Quill Motors is a concept for a new generation of automotive enthusiasts. Our goal is to bring great design, exceptional vehicles, and a more personal buying experience together.</p>
            <a href="#contact" class="btn">Start your journey <span>↗</span></a>
        </div>
    </div>
</section>

<section class="experience" id="experience">
    <!-- IMAGE: 1920 × 900 px. Use a wide road-driving or scenic car photograph. -->
    <img class="experience-image" src="{{ asset('images/driving-experience.jpg') }}" alt="Performance car on an open road" loading="lazy" onerror="this.style.display='none'">
    <div class="experience-overlay"></div>
    <div class="container experience-container"><div class="experience-content"><p class="eyebrow">Your next chapter starts here</p><h2>THE ROAD<br>IS YOURS.</h2><p>The right car isn't just about where you're going. It's about how you feel getting there.</p><a href="#contact" class="btn">Find your next car <span>↗</span></a></div></div>
</section>

<section class="section contact" id="contact">
    <div class="container contact-grid">
        <div>
            <p class="eyebrow">Let's talk automobiles</p><h2 class="section-heading">YOUR NEXT<br>DRIVE AWAITS.</h2><p class="section-copy">Have a particular model in mind? Tell us what you're looking for and we'll help you take the next step.</p>
            <div class="contact-details">
                <div class="contact-detail"><h3>Visit our showroom</h3><p>YOUR SHOWROOM ADDRESS HERE</p></div>
                <div class="contact-detail"><h3>Call us</h3><a href="tel:+630000000000">+63 XXX XXX XXXX</a></div>
                <div class="contact-detail"><h3>Email</h3><a href="mailto:hello@example.com">hello@example.com</a></div>
            </div>
        </div>
        <form class="contact-form" action="{{ route('inquiries.store') }}" method="POST">
            @csrf
            @if (session('inquiry_success'))
                <div class="alert-success" role="status">{{ session('inquiry_success') }}</div>
            @endif
            @if ($errors->any())
                <div class="alert-error" role="alert"><strong>Please check the form:</strong><ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
            @endif
            <div class="form-row">
                <div class="form-group"><label for="name">Full name</label><input id="name" name="name" value="{{ old('name') }}" type="text" placeholder="Your name" autocomplete="name" required maxlength="120"></div>
                <div class="form-group"><label for="email">Email address</label><input id="email" name="email" value="{{ old('email') }}" type="email" placeholder="you@example.com" autocomplete="email" required maxlength="190"></div>
            </div>
            <div class="form-group"><label for="vehicle">Vehicle of interest</label><select id="vehicle" name="vehicle" required><option value="">Select a model</option><option {{ old('vehicle') === 'Model Name One' ? 'selected' : '' }}>Model Name One</option><option {{ old('vehicle') === 'Model Name Two' ? 'selected' : '' }}>Model Name Two</option><option {{ old('vehicle') === 'Model Name Three' ? 'selected' : '' }}>Model Name Three</option><option {{ old('vehicle') === 'Other' ? 'selected' : '' }}>Other</option></select></div>
            <div class="form-group"><label for="message">Your message <span class="optional">(optional)</span></label><textarea id="message" name="message" placeholder="Tell us what you're looking for..." maxlength="3000">{{ old('message') }}</textarea></div>
            <button type="submit" class="btn submit-btn">Send inquiry <span>↗</span></button>
            <p class="form-note">Your inquiry is validated by Laravel and stored locally for this starter project.</p>
        </form>
    </div>
</section>
</main>

<footer class="footer">
    <div class="container">
        <div class="footer-top"><div><a href="#home" class="brand">Quill<span>.</span></a><p class="footer-description">Extraordinary automobiles. A more personal way to discover your next drive.</p></div><nav class="footer-links" aria-label="Footer navigation"><a href="#home">Home</a><a href="#collection">Collection</a><a href="#about">Our Story</a><a href="#experience">Experience</a><a href="#contact">Contact</a></nav></div>
        <div class="footer-bottom"><p>© {{ date('Y') }} Quill Motors. All rights reserved.</p><p>DESIGNED FOR THE LOVE OF DRIVING.</p></div>
    </div>
</footer>
@include('partials.faq-chat')
<script src="{{ asset('js/landing.js') }}" defer></script>
</body>
</html>
