@extends('layouts.admin')
@section('title', 'Programs')
@section('heading', 'Programs')

@section('content')
<div class="page-card">
    <div class="page-toolbar">
        <h3>Programs</h3>
        <a href="{{ route('admin.programs.create') }}" class="btn-add">
            <x-icon name="plus" /> Add program
        </a>
    </div>
    <table class="admin-table">
        <thead><tr><th>Image</th><th>Title</th><th></th></tr></thead>
        <tbody>
        @forelse($programs as $program)
            <tr>
                <td><img class="thumb" src="{{ $program->imageUrl() }}" alt=""></td>
                <td>{{ $program->title }}</td>
                <td class="admin-actions">
                    <x-admin.icon-link :href="route('admin.programs.edit', $program)" icon="pencil" label="Edit" />
                    <x-admin.icon-delete :action="route('admin.programs.destroy', $program)" message="Delete this program?" />
                </td>
            </tr>
        @empty
            <tr><td colspan="3">No programs yet.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
