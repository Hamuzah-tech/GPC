@extends('layouts.admin')
@section('title', 'Add '.$label)
@section('heading', 'Add '.$label)

@section('content')
<div class="page-card">
    <h3>Add {{ $label }}</h3>
    <form method="POST" action="{{ route($routePrefix.'.store') }}">
        @csrf
        <div class="admin-form-group"><label>Title</label><input type="text" name="title" value="{{ old('title') }}" required></div>
        <div class="admin-form-group"><label>Description</label><textarea name="description" rows="5">{{ old('description') }}</textarea></div>
        <div class="admin-form-group"><label>Sort Order</label><input type="number" name="sort_order" value="{{ old('sort_order', 0) }}"></div>
        <button class="btn-primary" type="submit">Save</button>
    </form>
</div>
@endsection
