@extends('layouts.admin')
@section('title', $label)
@section('heading', $label)

@section('content')
<div class="page-card">
    <div class="page-toolbar">
        <h3>{{ $label }}s</h3>
        <a href="{{ route($routePrefix.'.create') }}" class="btn-add">
            <x-icon name="plus" /> Add {{ $label }}
        </a>
    </div>
    <table class="admin-table">
        <thead><tr><th>Title</th><th>Description</th><th></th></tr></thead>
        <tbody>
        @forelse($items as $item)
            <tr>
                <td>{{ $item->title }}</td>
                <td>{{ \Illuminate\Support\Str::limit($item->description, 80) }}</td>
                <td class="admin-actions">
                    <x-admin.icon-link :href="route($routePrefix.'.edit', $item)" icon="pencil" label="Edit" />
                    <x-admin.icon-delete :action="route($routePrefix.'.destroy', $item)" message="Delete this {{ strtolower($label) }}?" />
                </td>
            </tr>
        @empty
            <tr><td colspan="3">No items yet.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
