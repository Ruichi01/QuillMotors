<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Your account — Quill Motors</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@400;500;600;700;800&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
    <link rel="stylesheet" href="{{ asset('css/faq-chat.css') }}">
</head>
<body>
<main class="auth-shell">
    <a href="{{ route('home') }}" class="brand">Quill<span>.</span></a>
    <section class="auth-card account-card">
        <p class="eyebrow">Your Quill account</p>
        <h1>Welcome, {{ auth()->user()->name }}.</h1>
        <p class="intro">Your account is ready. The next step will be connecting it to saved vehicles and buyer-to-seller conversations.</p>
        <div class="account-detail"><span>Email</span><strong>{{ auth()->user()->email }}</strong></div>
        @if (auth()->user()->role === 'admin')
            <a class="btn" href="{{ route('admin.dashboard') }}">Open admin dashboard <span>↗</span></a>
        @endif
        <a class="btn" href="{{ route('home') }}">Explore Quill Motors <span>↗</span></a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="text-button" type="submit">Sign out</button>
        </form>
    </section>
</main>
@include('partials.faq-chat')
</body>
</html>
