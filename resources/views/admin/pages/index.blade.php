@extends('layouts.admin')
@section('title', 'Pages')
@section('heading', 'Pages')

@section('content')
<div class="page-card">
    <div class="page-toolbar">
        <h3>Website pages</h3>
    </div>
    <table class="admin-table">
        <thead><tr><th>Slug</th><th>Title</th><th></th></tr></thead>
        <tbody>
        @foreach($pages as $page)
            <tr>
                <td>{{ $page->slug }}</td>
                <td>{{ $page->title }}</td>
                <td class="admin-actions">
                    <x-admin.icon-link :href="route('admin.pages.edit', $page)" icon="pencil" label="Edit" />
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endsection
