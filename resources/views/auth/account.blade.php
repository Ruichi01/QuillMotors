<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Your account — Quill Motors</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@400;500;600;700;800&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>
<body>
@include('partials.store-nav')
<main class="auth-shell">
    <section class="auth-card account-card">
        <p class="eyebrow">Your Quill account</p>
        <div class="profile-avatar">
            @if (auth()->user()->profile_photo_path)<img src="{{ asset('storage/'.auth()->user()->profile_photo_path) }}" alt="{{ auth()->user()->name }}">@else<span>{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>@endif
        </div>
        <h1>Your profile.</h1>
        @if (session('success'))<div class="alert-success">{{ session('success') }}</div>@endif
        @if ($errors->any())<div class="alert-error">{{ $errors->first() }}</div>@endif
        <form method="POST" action="{{ route('account.profile.update') }}" enctype="multipart/form-data" class="profile-form">
            @csrf @method('PUT')
            <div class="form-group"><label for="name">Name</label><input id="name" name="name" value="{{ old('name', auth()->user()->name) }}" required></div>
            <div class="form-group"><label for="email">Email address</label><input id="email" name="email" type="email" value="{{ old('email', auth()->user()->email) }}" required></div>
            <div class="form-group"><label for="profile_photo">Profile photo <small>JPG, PNG, or WebP; 2 MB maximum</small></label><input id="profile_photo" name="profile_photo" type="file" accept="image/jpeg,image/png,image/webp"></div>
            <button class="btn" type="submit">Save profile</button>
        </form>
        <div class="account-section account-messenger">
            <p class="eyebrow">Messenger</p><h2>Messages</h2>
            @if($selectedConversation)
                <div class="messenger-thread-heading"><a href="{{ route('account') }}" aria-label="Back to conversations">←</a><div><strong>Quill Motors</strong><small>Owner</small></div></div>
                <div class="account-chat-thread">@foreach($selectedConversation->messages as $message)<div class="account-chat-message account-chat-message-{{ $message->sender_type }}"><span class="chat-message-avatar">{{ $message->sender_type === 'customer' ? strtoupper(substr(auth()->user()->name, 0, 1)) : 'Q' }}</span><div><strong>{{ $message->sender_type === 'customer' ? 'You' : 'Quill Motors' }}</strong><small>{{ $message->created_at->diffForHumans() }} · {{ $message->created_at->format('M j, g:i A') }}</small><p>{{ $message->body }}</p></div></div>@endforeach</div>
                <form method="POST" action="{{ route('chat.messages.store') }}" class="account-chat-reply">@csrf<textarea name="body" rows="2" placeholder="Write a message..." required></textarea><button class="btn" type="submit">Send</button></form>
            @else
                <div class="messenger-list">
                    @forelse($chatConversations as $conversation)
                        <a href="{{ route('account', ['conversation' => $conversation->id]) }}" class="messenger-contact"><span class="profile-avatar small-avatar">Q</span><span><strong>Quill Motors</strong><small>{{ $conversation->last_message_at?->diffForHumans() }}</small></span><b>→</b></a>
                    @empty
                        <p class="form-note">Your FAQ questions and owner conversations will appear here.</p>
                    @endforelse
                </div>
            @endif
        </div>
        <div class="account-section">
            <p class="eyebrow">Security</p><h2>Change password</h2>
            <form method="POST" action="{{ route('account.password.update') }}" class="profile-form">
                @csrf @method('PUT')
                <div class="form-group"><label for="current_password">Current password</label><input id="current_password" name="current_password" type="password" required></div>
                <div class="form-group"><label for="password">New password</label><input id="password" name="password" type="password" minlength="8" required></div>
                <div class="form-group"><label for="password_confirmation">Confirm new password</label><input id="password_confirmation" name="password_confirmation" type="password" minlength="8" required></div>
                <button class="btn" type="submit">Change password</button>
            </form>
        </div>
        @if (auth()->user()->role === 'admin')
            <a class="btn" href="{{ route('admin.dashboard') }}">Open admin dashboard <span>↗</span></a>
        @endif
        <a class="back-link account-home-link" href="{{ route('home') }}">← Explore Quill Motors</a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="text-button" type="submit">Sign out</button>
        </form>
    </section>
</main>
<script src="{{ asset('js/landing.js') }}" defer></script>
</body>
</html>
