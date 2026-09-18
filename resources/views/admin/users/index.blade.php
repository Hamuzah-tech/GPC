@extends('layouts.admin')
@section('title', 'Users')
@section('heading', 'Users')

@section('content')
<div class="page-card">
    <div class="page-toolbar">
        <h3>Staff accounts</h3>
        <a href="{{ route('admin.users.create') }}" class="btn-add">
            <x-icon name="plus" /> Create user
        </a>
    </div>
    <div class="table-scroll">
        <table class="admin-table">
            <thead>
                <tr><th>Name</th><th>Email</th><th>Role</th><th></th></tr>
            </thead>
            <tbody>
            @forelse($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->roleLabel() }}</td>
                    <td class="admin-actions">
                        <x-admin.icon-link :href="route('admin.users.edit', $user)" icon="pencil" label="Edit" />
                        @if(! auth()->user()->is($user))
                            <x-admin.icon-delete :action="route('admin.users.destroy', $user)" message="Delete this user?" />
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="4">No users yet.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="table-footer">{{ $users->links() }}</div>
</div>
@endsection
