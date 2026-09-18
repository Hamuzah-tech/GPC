@extends('layouts.admin')
@section('title', 'Manage News')
@section('heading', 'News')

@section('content')
<div class="page-card">
    <div class="page-toolbar">
        <h3>All articles</h3>
        <div class="page-toolbar-actions">
            <form method="GET" action="{{ route('admin.news.index') }}">
                <input type="text" name="q" value="{{ $search }}" class="search-bar" placeholder="Search by title...">
            </form>
            <a href="{{ route('admin.news.create') }}" class="btn-add">
                <x-icon name="plus" /> Add news
            </a>
        </div>
    </div>
    <table class="admin-table">
        <thead>
            <tr><th>Title</th><th>Date</th><th>Images</th><th></th></tr>
        </thead>
        <tbody>
        @forelse($news as $item)
            <tr>
                <td>{{ $item->title }}</td>
                <td>{{ optional($item->published_at)->format('M j, Y') }}</td>
                <td>{{ $item->images->count() }}</td>
                <td class="admin-actions">
                    <x-admin.icon-link :href="route('admin.news.edit', $item)" icon="pencil" label="Edit" />
                    <x-admin.icon-delete :action="route('admin.news.destroy', $item)" message="Delete this news article?" />
                </td>
            </tr>
        @empty
            <tr><td colspan="4">No news found.</td></tr>
        @endforelse
        </tbody>
    </table>
    <div class="table-footer">{{ $news->links() }}</div>
</div>
@endsection
