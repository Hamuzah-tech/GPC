@extends('layouts.admin')
@section('title', 'Edit Program')
@section('heading', 'Edit Program')

@section('content')
<div class="page-card">
    <h3>Edit Program</h3>
    <form method="POST" action="{{ route('admin.programs.update', $program) }}" enctype="multipart/form-data">
        @csrf @method('PUT')
        <div class="admin-form-group"><label>Title</label><input type="text" name="title" value="{{ old('title', $program->title) }}" required></div>
        <div class="admin-form-group"><label>Description</label><textarea name="description" rows="5">{{ old('description', $program->description) }}</textarea></div>
        <div class="admin-form-group"><label>Sort Order</label><input type="number" name="sort_order" value="{{ old('sort_order', $program->sort_order) }}"></div>
        <div class="admin-form-group"><label>Image</label><input type="file" name="photo" accept="image/*"></div>
        <button class="btn-primary" type="submit">Save</button>
    </form>
</div>
@endsection
