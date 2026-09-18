@extends('layouts.admin')
@section('title', 'Message')
@section('heading', 'Message')

@section('content')
<div class="page-card">
    <div class="page-toolbar">
        <h3>{{ $message->name }}</h3>
        <div class="admin-actions">
            <x-admin.icon-link :href="route('admin.messages.index')" icon="arrow-left" label="Back" tone="view" />
            <x-admin.icon-delete :action="route('admin.messages.destroy', $message)" message="Delete this message?" />
        </div>
    </div>
    <p><strong>Email:</strong> {{ $message->email }}</p>
    <p><strong>Phone:</strong> {{ $message->phone ?: 'Not provided' }}</p>
    <p><strong>Date:</strong> {{ $message->created_at->format('F j, Y g:i A') }}</p>
    <p style="margin-top:16px;">{{ $message->message }}</p>
</div>
@endsection
