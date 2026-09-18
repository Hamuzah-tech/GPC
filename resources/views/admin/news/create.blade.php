@extends('layouts.admin')
@section('title', isset($news) ? 'Edit News' : 'Add News')
@section('heading', isset($news) ? 'Edit News' : 'Add News')

@section('content')
<div class="page-card">
    <h3>{{ isset($news) ? 'Edit News Article' : 'Create News Article' }}</h3>
    <form method="POST" action="{{ isset($news) ? route('admin.news.update', $news) : route('admin.news.store') }}" enctype="multipart/form-data">
        @csrf
        @if(isset($news)) @method('PUT') @endif
        <div class="admin-form-group">
            <label>Title</label>
            <input type="text" name="title" value="{{ old('title', $news->title ?? '') }}" required>
        </div>
        <div class="admin-form-group">
            <label>Content</label>
            <textarea name="content" rows="8" required>{{ old('content', $news->content ?? '') }}</textarea>
        </div>
        <div class="admin-form-group">
            <label>Publication Date</label>
            <input type="date" name="published_at" value="{{ old('published_at', isset($news) ? optional($news->published_at)->format('Y-m-d') : now()->toDateString()) }}" required>
        </div>
        @if(isset($news) && $news->images->count())
            <div class="admin-form-group">
                <label>Existing Images</label>
                <div class="images-list">
                    @foreach($news->images as $image)
                        <div class="image-item">
                            <img src="{{ $image->url() }}" alt="">
                            @if($image->is_cover)<div class="cover-badge">COVER</div>@endif
                            <label style="position:absolute; bottom:4px; left:4px; color:#fff; font-size:10px;">
                                <input type="radio" name="cover_image_id" value="{{ $image->id }}" {{ $image->is_cover ? 'checked' : '' }}> Cover
                            </label>
                            <label style="position:absolute; top:4px; right:4px; color:#fff; font-size:10px;">
                                <input type="checkbox" name="remove_images[]" value="{{ $image->id }}"> Remove
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
        <div class="admin-form-group">
            <label>Add Images</label>
            <input type="file" name="images[]" accept="image/jpeg,image/png,image/jpg,image/webp" multiple>
        </div>
        <button type="submit" class="btn-primary">{{ isset($news) ? 'Update News' : 'Publish News' }}</button>
    </form>
</div>
@endsection
