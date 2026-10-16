@extends('admin.layout')
@section('title', 'Categories')
@section('heading', 'Categories')
@section('kicker', 'Manage / Organization')
@section('content')
<div class="admin-page-actions"><p>Group vehicles so buyers can browse easily.</p><a class="admin-button" href="{{ route('admin.categories.create') }}">＋ Add category</a></div>
<form class="admin-filters" method="GET"><input name="search" value="{{ request('search') }}" placeholder="Search categories" aria-label="Search categories"><button class="admin-button" type="submit">Filter</button>@if(request('search'))<a class="admin-cancel" href="{{ route('admin.categories.index') }}">Clear</a>@endif</form>
<section class="admin-panel table-panel"><table class="admin-table"><thead><tr><th>Name</th><th>Slug</th><th>Cars</th><th>Description</th><th></th></tr></thead><tbody>@forelse($categories as $category)<tr><td><strong>{{ $category->name }}</strong></td><td><code>{{ $category->slug }}</code></td><td>{{ $category->cars_count }}</td><td>{{ $category->description ?: '—' }}</td><td class="table-actions"><a href="{{ route('admin.categories.edit', $category) }}">Edit</a><form method="POST" action="{{ route('admin.categories.destroy', $category) }}" onsubmit="return confirm('Delete this category?')">@csrf @method('DELETE')<button>Delete</button></form></td></tr>@empty<tr><td colspan="5" class="empty-state">No categories yet.</td></tr>@endforelse</tbody></table></section>
@endsection
