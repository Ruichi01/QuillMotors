<header class="navbar">
    <div class="container nav-inner">
        <a href="{{ route('home') }}" class="brand">Quill<span>.</span></a>
        <nav class="nav-links" id="navLinks" aria-label="Main navigation">
            <a href="{{ route('home') }}">Home</a>
            <a href="{{ route('store') }}">Market</a>
            @auth
                <div class="profile-menu">
                    <button class="profile-menu-toggle" type="button" aria-expanded="false" aria-controls="profileMenu">
                        @if (auth()->user()->profile_photo_path)
                            <img src="{{ asset('storage/'.auth()->user()->profile_photo_path) }}" alt="">
                        @else
                            <span>{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                        @endif
                        {{ auth()->user()->name }}
                    </button>
                    <div class="profile-menu-panel" id="profileMenu" hidden>
                        <a href="{{ route('account') }}">Profile settings</a>
                        @if (auth()->user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}">Admin dashboard</a>
                        @endif
                        <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit">Sign out</button></form>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}">Sign in</a>
            @endauth
        </nav>
        <button class="nav-toggle" id="navToggle" type="button" aria-expanded="false" aria-controls="navLinks">Menu</button>
    </div>
</header>
