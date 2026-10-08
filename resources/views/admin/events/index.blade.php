@extends('layouts.admin')
@section('title', 'Event Registrations')
@section('heading', 'Event Registrations')

@section('content')
<div class="page-card">
    <div class="page-toolbar">
        <h3>Event registrations</h3>
        <div class="page-toolbar-actions">
            <a class="btn-add" href="{{ route('admin.event-registrations.export', request()->query()) }}">Export Registrations</a>
        </div>
    </div>

    <form method="GET" action="{{ route('admin.event-registrations.index') }}" class="filter-bar">
        <input class="search-bar" type="search" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Search name, registration number, phone, or email" aria-label="Search registrations">
        <select name="event" aria-label="Filter by event">
            <option value="">All events</option>
            @foreach($events as $event)
                <option value="{{ $event->id }}" @selected((string) ($filters['event'] ?? '') === (string) $event->id)>{{ $event->name }}</option>
            @endforeach
        </select>
        <select name="status" aria-label="Filter by status">
            <option value="">All statuses</option>
            <option value="pending" @selected(($filters['status'] ?? '') === 'pending')>Pending</option>
            <option value="accepted" @selected(($filters['status'] ?? '') === 'accepted')>Accepted</option>
            <option value="rejected" @selected(($filters['status'] ?? '') === 'rejected')>Rejected</option>
        </select>
        @if(($filters['registered'] ?? '') === 'today')
            <input type="hidden" name="registered" value="today">
        @endif
        <button type="submit" class="btn-add">Filter</button>
        <a class="btn-inline btn-inline-neutral" href="{{ route('admin.event-registrations.index') }}">Clear</a>
    </form>

    <div class="table-scroll">
        <table class="admin-table registrations-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Full Name</th>
                    <th>Registration No.</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Year</th>
                    <th>Event</th>
                    <th>Status</th>
                    <th>Registered At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            @forelse($registrations as $registration)
                <tr>
                    <td>{{ ($registrations->firstItem() ?? 1) + $loop->index }}</td>
                    <td>
                        {{ $registration->full_name }}
                        @if($registration->registration_id)
                            <div class="cell-sub">{{ $registration->registration_id }}</div>
                        @endif
                    </td>
                    <td>{{ $registration->registration_number }}</td>
                    <td>{{ $registration->phone }}</td>
                    <td>{{ $registration->email }}</td>
                    <td>{{ $registration->year_of_study }}</td>
                    <td>{{ $registration->event->name }}</td>
                    <td><span class="status-badge status-{{ $registration->status }}">{{ $registration->statusLabel() }}</span></td>
                    <td>{{ $registration->created_at->format('M j, Y H:i') }}</td>
                    <td>@include('admin.events.partials.actions', ['registration' => $registration, 'showView' => true])</td>
                </tr>
            @empty
                <tr><td colspan="10">No registrations yet.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="table-footer">{{ $registrations->links() }}</div>
</div>
@endsection
