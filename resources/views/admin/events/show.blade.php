@extends('layouts.admin')
@section('title', 'Registration details')
@section('heading', 'Registration details')

@section('content')
<div class="page-card">
    <div class="page-toolbar">
        <h3>{{ $registration->full_name }}</h3>
        @include('admin.events.partials.actions', ['registration' => $registration, 'showView' => false])
    </div>

    <dl class="detail-grid">
        <div><dt>Full name</dt><dd>{{ $registration->full_name }}</dd></div>
        <div><dt>Registration number</dt><dd>{{ $registration->registration_number }}</dd></div>
        <div><dt>Phone</dt><dd>{{ $registration->phone }}</dd></div>
        <div><dt>Email</dt><dd>{{ $registration->email }}</dd></div>
        <div><dt>Year of study</dt><dd>{{ $registration->year_of_study }}</dd></div>
        <div><dt>Event</dt><dd>{{ $registration->event->name }}</dd></div>
        <div><dt>Status</dt><dd><span class="status-badge status-{{ $registration->status }}">{{ $registration->statusLabel() }}</span></dd></div>
        <div><dt>Registration ID</dt><dd>{{ $registration->registration_id ?: 'Not issued' }}</dd></div>
        <div><dt>Registered at</dt><dd>{{ $registration->created_at->format('M j, Y H:i') }}</dd></div>
        <div><dt>Accepted at</dt><dd>{{ $registration->accepted_at?->format('M j, Y H:i') ?: '—' }}</dd></div>
        <div><dt>Email sent at</dt><dd>{{ $registration->email_sent_at?->format('M j, Y H:i') ?: '—' }}</dd></div>
        <div><dt>Emails sent</dt><dd>{{ $registration->email_send_count }}</dd></div>
    </dl>

    <p class="detail-back"><a href="{{ route('admin.event-registrations.index') }}">Back to registrations</a></p>
</div>
@endsection
