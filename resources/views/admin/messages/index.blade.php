@extends('layouts.admin')
@section('title', 'Messages')
@section('heading', 'Messages')

@section('content')
<div class="page-card">
    <div class="page-toolbar">
        <h3>Contact messages</h3>
    </div>
    <table class="admin-table">
        <thead><tr><th>Name</th><th>Email</th><th>Date</th><th>Status</th><th></th></tr></thead>
        <tbody>
        @forelse($messages as $message)
            <tr>
                <td>{{ $message->name }}</td>
                <td>{{ $message->email }}</td>
                <td>{{ $message->created_at->format('M j, Y') }}</td>
                <td>{{ $message->is_read ? 'Read' : 'New' }}</td>
                <td class="admin-actions">
                    <x-admin.icon-link :href="route('admin.messages.show', $message)" icon="eye" label="View" tone="view" />
                    <x-admin.icon-delete :action="route('admin.messages.destroy', $message)" message="Delete this message?" />
                </td>
            </tr>
        @empty
            <tr><td colspan="5">No messages yet.</td></tr>
        @endforelse
        </tbody>
    </table>
    <div class="table-footer">{{ $messages->links() }}</div>
</div>
@endsection
