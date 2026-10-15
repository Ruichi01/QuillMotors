@extends('admin.layout')
@section('title', $category->exists ? 'Edit category' : 'Add category')
@section('heading', $category->exists ? 'Edit category' : 'Add a category')
@section('kicker', 'Manage / Organization')
@section('content')
<form class="admin-form admin-panel narrow-panel" method="POST" action="{{ $category->exists ? route('admin.categories.update', $category) : route('admin.categories.store') }}">@csrf @if($category->exists) @method('PUT') @endif
    <div class="form-section-title"><p class="panel-kicker">Category details</p><h2>Make inventory easier to explore</h2></div>
    <div class="admin-field"><label for="name">Name</label><input id="name" name="name" value="{{ old('name', $category->name) }}" required></div>
    <div class="admin-field"><label for="slug">Slug <small>Optional — generated from the name when blank</small></label><input id="slug" name="slug" value="{{ old('slug', $category->slug) }}"></div>
    <div class="admin-field"><label for="description">Description</label><textarea id="description" name="description" rows="5">{{ old('description', $category->description) }}</textarea></div>
    <div class="admin-form-actions"><a class="admin-cancel" href="{{ route('admin.categories.index') }}">Cancel</a><button class="admin-button" type="submit">{{ $category->exists ? 'Save changes' : 'Add category' }}</button></div>
</form>
@endsection
