<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sign in — Quill Motors</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@400;500;600;700;800&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
    <link rel="stylesheet" href="{{ asset('css/faq-chat.css') }}">
</head>
<body>
<main class="auth-shell">
    <a href="{{ route('home') }}" class="brand">Quill<span>.</span></a>
    <section class="auth-card">
        <p class="eyebrow">Welcome back</p>
        <h1>Sign in to Quill.</h1>
        <p class="intro">Keep track of your vehicle inquiries and find your next drive.</p>
        <form method="POST" action="{{ route('login.store') }}">
            @csrf
            @if ($errors->any())
                <div class="alert-error" role="alert">{{ $errors->first() }}</div>
            @endif
            <div class="form-group">
                <label for="email">Email address</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" required autofocus>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input id="password" name="password" type="password" autocomplete="current-password" required>
            </div>
            <label class="checkbox"><input type="checkbox" name="remember"> Remember me</label>
            <button class="btn" type="submit">Sign in <span>↗</span></button>
        </form>
        <p class="auth-link">New to Quill? <a href="{{ route('register') }}">Create an account</a></p>
        <a class="back-link" href="{{ route('home') }}">← Back to Quill Motors</a>
    </section>
</main>
@include('partials.faq-chat')
</body>
</html>
