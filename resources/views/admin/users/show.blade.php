@extends('admin.layout')
@section('title', $user->name)
@section('heading', $user->name)
@section('kicker', 'Users / Account history')
@section('content')
<div class="admin-page-actions"><p>{{ $user->email }} · Joined {{ $user->created_at->format('M j, Y') }}</p><a class="admin-cancel" href="{{ route('admin.users.index') }}">← Back to users</a></div>
<section class="admin-panel history-panel">
    <div class="panel-heading"><div><p class="panel-kicker">Messenger history</p><h2>Conversations</h2></div></div>
    @forelse($user->chatConversations as $conversation)
        <div class="chat-account-thread"><div class="chat-account-heading"><strong>{{ $user->name }}</strong><small>{{ $conversation->last_message_at?->format('M j, Y · g:i A') }}</small></div>@foreach($conversation->messages as $message)<div class="chat-message chat-message-{{ $message->sender_type }}"><small>{{ ucfirst($message->sender_type) }} · {{ $message->created_at->format('M j, g:i A') }}</small><p>{{ $message->body }}</p></div>@endforeach</div>
    @empty
        <p class="empty-state">No chat conversations from this account.</p>
    @endforelse
</section>
<div class="admin-dashboard-grid lower-grid">
    <section class="admin-panel">
        <div class="panel-heading"><div><p class="panel-kicker">Purchase history</p><h2>Past purchases</h2></div></div>
        <p class="empty-state">No purchase records are available yet.</p>
    </section>
    <section class="admin-panel">
        <div class="panel-heading"><div><p class="panel-kicker">Saved vehicles</p><h2>Favorites</h2></div></div>
        @forelse($user->favorites as $favorite)
            <div class="history-row"><span><strong>{{ $favorite->car->make }} {{ $favorite->car->model }}</strong><small>{{ $favorite->car->year }} · {{ ucfirst($favorite->car->status) }}</small></span></div>
        @empty
            <p class="empty-state">No saved vehicles.</p>
        @endforelse
    </section>
</div>
<section class="admin-panel history-panel">
    <div class="panel-heading"><div><p class="panel-kicker">Contact history</p><h2>Past inquiries</h2></div></div>
    @forelse($user->inquiries as $inquiry)
        <div class="history-row"><span><strong>{{ $inquiry->car->make }} {{ $inquiry->car->model }}</strong><small>{{ $inquiry->created_at->format('M j, Y · g:i A') }} · {{ $inquiry->email }}</small><p>{{ $inquiry->message }}</p></span><span class="status status-{{ $inquiry->status }}">{{ ucfirst($inquiry->status) }}</span></div>
    @empty
        <p class="empty-state">No inquiries from this user.</p>
    @endforelse
</section>
@endsection
