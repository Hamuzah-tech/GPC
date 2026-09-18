@extends('layouts.admin')
@section('title', 'Leadership')
@section('heading', 'Leadership')

@section('content')
<div class="page-card">
    <div class="page-toolbar">
        <h3>Leadership team</h3>
        <a href="{{ route('admin.leaders.create') }}" class="btn-add">
            <x-icon name="plus" /> Add leader
        </a>
    </div>
    <table class="admin-table">
        <thead><tr><th>Photo</th><th>Name</th><th>Position</th><th></th></tr></thead>
        <tbody>
        @forelse($leaders as $leader)
            <tr>
                <td><img class="thumb" src="{{ $leader->imageUrl() }}" alt=""></td>
                <td>{{ $leader->name }}</td>
                <td>{{ $leader->position }}</td>
                <td class="admin-actions">
                    <x-admin.icon-link :href="route('admin.leaders.edit', $leader)" icon="pencil" label="Edit" />
                    <x-admin.icon-delete :action="route('admin.leaders.destroy', $leader)" message="Delete this leader?" />
                </td>
            </tr>
        @empty
            <tr><td colspan="4">No leaders yet.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
