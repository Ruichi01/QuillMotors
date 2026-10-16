@extends('admin.layout')
@section('title', 'Users')
@section('heading', 'Users')
@section('kicker', 'Manage / Accounts')
@section('content')
<div class="admin-page-actions"><p>Review customer accounts and engagement.</p></div>
<form class="admin-filters" method="GET"><input name="search" value="{{ request('search') }}" placeholder="Search name or email" aria-label="Search users"><select name="role" aria-label="Filter by role"><option value="">All roles</option><option value="admin" @selected(request('role') === 'admin')>Admin</option><option value="customer" @selected(request('role') === 'customer')>Customer</option></select><button class="admin-button" type="submit">Filter</button>@if(request()->hasAny(['search','role']))<a class="admin-cancel" href="{{ route('admin.users.index') }}">Clear</a>@endif</form>
<section class="admin-panel table-panel"><table class="admin-table"><thead><tr><th>User</th><th>Role</th><th>Favorites</th><th>Inquiries</th><th>Joined</th></tr></thead><tbody>@forelse($users as $user)<tr><td><strong>{{ $user->name }}</strong><small>{{ $user->email }}</small></td><td><span class="status status-{{ $user->role }}">{{ ucfirst($user->role) }}</span></td><td>{{ $user->favorites_count }}</td><td>{{ $user->inquiries_count }}</td><td>{{ $user->created_at->format('M j, Y') }}</td></tr>@empty<tr><td colspan="5" class="empty-state">No users yet.</td></tr>@endforelse</tbody></table><div class="admin-pagination">{{ $users->links() }}</div></section>
@endsection
