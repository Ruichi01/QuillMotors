<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') — Quill Motors</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@400;500;600;700;800&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body class="admin-body">
<aside class="admin-sidebar">
    <a class="admin-brand" href="{{ route('admin.dashboard') }}">Quill<span>.</span><small>CONTROL CENTER</small></a>
    <nav class="admin-nav" aria-label="Admin navigation">
        <a class="{{ request()->routeIs('admin.dashboard') ? 'is-active' : '' }}" href="{{ route('admin.dashboard') }}"><span>▦</span> Dashboard</a>
        <p>Manage</p>
        <a class="{{ request()->routeIs('admin.cars.*') ? 'is-active' : '' }}" href="{{ route('admin.cars.index') }}"><span>◈</span> Cars</a>
        <a class="{{ request()->routeIs('admin.categories.*') ? 'is-active' : '' }}" href="{{ route('admin.categories.index') }}"><span>◇</span> Categories</a>
        <a class="{{ request()->routeIs('admin.users.*') ? 'is-active' : '' }}" href="{{ route('admin.users.index') }}"><span>♙</span> Users</a>
    </nav>
    <div class="admin-sidebar-bottom">
        <a href="{{ route('home') }}">↗ View storefront</a>
        <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit">↪ Sign out</button></form>
    </div>
</aside>
<main class="admin-main">
    <header class="admin-topbar"><div><p class="admin-kicker">@yield('kicker', 'Quill Motors admin')</p><h1>@yield('heading', 'Dashboard')</h1></div><div class="admin-user"><span>{{ auth()->user()->name }}</span><small>{{ auth()->user()->email }}</small></div></header>
    @if (session('success'))<div class="admin-alert admin-alert-success">{{ session('success') }}</div>@endif
    @if ($errors->any())<div class="admin-alert admin-alert-error">{{ $errors->first() }}</div>@endif
    @yield('content')
</main>
</body>
</html>
