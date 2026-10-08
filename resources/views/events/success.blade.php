@extends('layouts.app')

@section('title', 'Registration Successful')
@section('css', asset('css/pages/volunteer.css'))

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pages/event.css') }}">
@endpush

@section('content')
<section class="volunteer-hero event-hero">
    <div class="hero-overlay"></div>
    <div class="hero-content">
        <h1>Register for the Event</h1>
        <p>{{ $event->name }}</p>
    </div>
</section>

<section class="form-section">
    <div class="volunteer-form success-panel">
        <h1>Registration Successful! 🎉</h1>
        <p>Thank you for registering for “{{ $event->name }}”. Your registration has been received and is currently awaiting confirmation from the organising team.</p>
        <a class="text-link" href="{{ route('home') }}">Return to the homepage</a>
    </div>
</section>
@endsection
