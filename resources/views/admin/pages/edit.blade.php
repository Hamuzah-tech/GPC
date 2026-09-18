@extends('layouts.admin')
@section('title', 'Edit Page')
@section('heading', 'Edit Page')

@section('content')
<div class="page-card">
    <h3>Edit {{ $page->title }}</h3>
    <form method="POST" action="{{ route('admin.pages.update', $page) }}">
        @csrf @method('PUT')
        <div class="admin-form-group"><label>Title</label><input type="text" name="title" value="{{ old('title', $page->title) }}" required></div>
        <div class="admin-form-group"><label>Subtitle</label><input type="text" name="subtitle" value="{{ old('subtitle', $page->subtitle) }}"></div>
        <div class="admin-form-group"><label>Body</label><textarea name="body" rows="8">{{ old('body', $page->body) }}</textarea></div>
        <div class="admin-form-group"><label>Supporting Text</label><textarea name="extra" rows="5">{{ old('extra', $page->extra) }}</textarea></div>
        <button class="btn-primary" type="submit">Save</button>
    </form>
</div>
@endsection
