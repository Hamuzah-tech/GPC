@extends('layouts.admin')
@section('title', 'Edit '.$label)
@section('heading', 'Edit '.$label)

@section('content')
<div class="page-card">
    <h3>Edit {{ $label }}</h3>
    <form method="POST" action="{{ route($routePrefix.'.update', $item) }}">
        @csrf @method('PUT')
        <div class="admin-form-group"><label>Title</label><input type="text" name="title" value="{{ old('title', $item->title) }}" required></div>
        <div class="admin-form-group"><label>Description</label><textarea name="description" rows="5">{{ old('description', $item->description) }}</textarea></div>
        <div class="admin-form-group"><label>Sort Order</label><input type="number" name="sort_order" value="{{ old('sort_order', $item->sort_order) }}"></div>
        <button class="btn-primary" type="submit">Save</button>
    </form>
</div>
@endsection
