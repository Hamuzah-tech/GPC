@extends('layouts.app')

@section('title', 'Registration Verified')
@section('css', asset('css/pages/volunteer.css'))

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pages/event.css') }}">
@endpush

@section('content')
<section class="event-result">
    <div class="result-card result-valid">
        <div class="result-mark" aria-hidden="true">✓</div>
        <h1>Registration Verified</h1>
        <p class="result-event">{{ $registration->event->name }}</p>
        <dl>
            <div>
                <dt>Participant</dt>
                <dd>{{ $registration->full_name }}</dd>
            </div>
            <div>
                <dt>Registration ID</dt>
                <dd>{{ $registration->registration_id }}</dd>
            </div>
            <div>
                <dt>Status</dt>
                <dd>{{ $registration->statusLabel() }}</dd>
            </div>
        </dl>
    </div>
</section>
@endsection
