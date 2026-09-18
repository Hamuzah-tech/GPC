@extends('layouts.admin')
@section('title', 'Gallery')
@section('heading', 'Gallery')

@section('content')
<div class="page-card" style="margin-bottom:20px;">
    <h3>Add image</h3>
    <form method="POST" action="{{ route('admin.gallery.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="admin-form-group"><label>Image</label><input type="file" name="photo" accept="image/*" required></div>
        <div class="admin-form-group"><label>Alt text</label><input type="text" name="alt"></div>
        <div class="admin-form-group">
            <label>Type</label>
            <select name="type">
                <option value="gallery">Homepage gallery</option>
                <option value="hero">Homepage hero</option>
            </select>
        </div>
        <div class="admin-form-group"><label>Sort order</label><input type="number" name="sort_order" value="0"></div>
        <button class="btn-primary" type="submit">Upload</button>
    </form>
</div>

<div class="page-card">
    <h3>Current images</h3>
    <div class="gallery-grid">
        @forelse($images as $image)
            <div class="gallery-item">
                <img src="{{ $image->url() }}" alt="{{ $image->alt }}">
                <div class="meta">
                    <span>{{ ucfirst($image->type) }}</span>
                    <x-admin.icon-delete :action="route('admin.gallery.destroy', $image)" message="Delete this image?" />
                </div>
            </div>
        @empty
            <p class="empty">No gallery images yet.</p>
        @endforelse
    </div>
</div>
@endsection
