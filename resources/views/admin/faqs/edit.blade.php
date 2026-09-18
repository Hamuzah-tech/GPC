@extends('layouts.admin')
@section('title', 'Edit FAQ')
@section('heading', 'Edit FAQ')

@section('content')
<div class="page-card">
    <h3>Edit FAQ</h3>
    <form method="POST" action="{{ route('admin.faqs.update', $faq) }}">
        @csrf @method('PUT')
        <div class="admin-form-group"><label>Question</label><input type="text" name="question" value="{{ old('question', $faq->question) }}" required></div>
        <div class="admin-form-group"><label>Answer</label><textarea name="answer" rows="5" required>{{ old('answer', $faq->answer) }}</textarea></div>
        <div class="admin-form-group"><label>Sort Order</label><input type="number" name="sort_order" value="{{ old('sort_order', $faq->sort_order) }}"></div>
        <button class="btn-primary" type="submit">Save</button>
    </form>
</div>
@endsection
