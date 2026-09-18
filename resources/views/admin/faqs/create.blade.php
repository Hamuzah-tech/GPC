@extends('layouts.admin')
@section('title', 'Add FAQ')
@section('heading', 'Add FAQ')

@section('content')
<div class="page-card">
    <h3>Add FAQ</h3>
    <form method="POST" action="{{ route('admin.faqs.store') }}">
        @csrf
        <div class="admin-form-group"><label>Question</label><input type="text" name="question" value="{{ old('question') }}" required></div>
        <div class="admin-form-group"><label>Answer</label><textarea name="answer" rows="5" required>{{ old('answer') }}</textarea></div>
        <div class="admin-form-group"><label>Sort Order</label><input type="number" name="sort_order" value="{{ old('sort_order', 0) }}"></div>
        <button class="btn-primary" type="submit">Save</button>
    </form>
</div>
@endsection
