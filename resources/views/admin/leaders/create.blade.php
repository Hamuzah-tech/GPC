@extends('layouts.admin')
@section('title', isset($leader) ? 'Edit Leader' : 'Add Leader')
@section('heading', isset($leader) ? 'Edit Leader' : 'Add Leader')

@section('content')
<div class="page-card">
    <h3>{{ isset($leader) ? 'Edit Leader' : 'Add Leader' }}</h3>
    <form method="POST" action="{{ isset($leader) ? route('admin.leaders.update', $leader) : route('admin.leaders.store') }}" enctype="multipart/form-data">
        @csrf
        @if(isset($leader)) @method('PUT') @endif
        <div class="admin-form-group"><label>Name</label><input type="text" name="name" value="{{ old('name', $leader->name ?? '') }}" required></div>
        <div class="admin-form-group"><label>Position</label><input type="text" name="position" value="{{ old('position', $leader->position ?? '') }}" required></div>
        <div class="admin-form-group"><label>Short Bio</label><textarea name="bio_preview" rows="3">{{ old('bio_preview', $leader->bio_preview ?? '') }}</textarea></div>
        <div class="admin-form-group"><label>Full Bio</label><textarea name="full_bio" rows="8">{{ old('full_bio', $leader->full_bio ?? '') }}</textarea></div>
        <div class="admin-form-group"><label>Sort Order</label><input type="number" name="sort_order" value="{{ old('sort_order', $leader->sort_order ?? 0) }}"></div>
        <div class="admin-form-group"><label>Photo</label><input type="file" name="photo" accept="image/*"></div>
        <button class="btn-primary" type="submit">Save</button>
    </form>
</div>
@endsection
