<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Create an account — Quill Motors</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@400;500;600;700;800&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>
<body>
<main class="auth-shell">
    <a href="{{ route('home') }}" class="brand">Quill<span>.</span></a>
    <section class="auth-card">
        <p class="eyebrow">Join the community</p>
        <h1>Create your account.</h1>
        <p class="intro">Save your details so it is easier to ask about your next vehicle.</p>
        <form method="POST" action="{{ route('register.store') }}">
            @csrf
            @if ($errors->any())
                <div class="alert-error" role="alert"><ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
            @endif
            <div class="form-group">
                <label for="name">Full name</label>
                <input id="name" name="name" type="text" value="{{ old('name') }}" autocomplete="name" required autofocus>
            </div>
            <div class="form-group">
                <label for="email">Email address</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input id="password" name="password" type="password" autocomplete="new-password" required>
                <small>Use at least 8 characters.</small>
            </div>
            <div class="form-group">
                <label for="password_confirmation">Confirm password</label>
                <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required>
            </div>
            <button class="btn" type="submit">Create account <span>↗</span></button>
        </form>
        <p class="auth-link">Already have an account? <a href="{{ route('login') }}">Sign in</a></p>
        <a class="back-link" href="{{ route('home') }}">← Back to Quill Motors</a>
    </section>
</main>
</body>
</html>
