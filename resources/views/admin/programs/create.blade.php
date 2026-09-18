@extends('layouts.admin')
@section('title', isset($program) ? 'Edit Program' : 'Add Program')
@section('heading', isset($program) ? 'Edit Program' : 'Add Program')

@section('content')
<div class="page-card">
    <h3>{{ isset($program) ? 'Edit Program' : 'Add Program' }}</h3>
    <form method="POST" action="{{ isset($program) ? route('admin.programs.update', $program) : route('admin.programs.store') }}" enctype="multipart/form-data">
        @csrf
        @if(isset($program)) @method('PUT') @endif
        <div class="admin-form-group"><label>Title</label><input type="text" name="title" value="{{ old('title', $program->title ?? '') }}" required></div>
        <div class="admin-form-group"><label>Description</label><textarea name="description" rows="5">{{ old('description', $program->description ?? '') }}</textarea></div>
        <div class="admin-form-group"><label>Sort Order</label><input type="number" name="sort_order" value="{{ old('sort_order', $program->sort_order ?? 0) }}"></div>
        <div class="admin-form-group"><label>Image</label><input type="file" name="photo" accept="image/*"></div>
        <button class="btn-primary" type="submit">Save</button>
    </form>
</div>
@endsection
