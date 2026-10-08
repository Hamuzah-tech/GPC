@extends('layouts.app')

@section('title', 'Event Card')
@section('css', asset('css/pages/volunteer.css'))

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pages/event.css') }}">
@endpush

@section('content')
<section class="event-result">
    <div class="card-page">
        <div class="card-page-intro no-print">
            <h1>Your event card</h1>
            <p>Keep this card available for identification and attendance.</p>
        </div>
        @include('events.partials.card')
        <div class="card-actions no-print">
            <a class="submit-btn card-download" href="{{ route('events.card.download', ['token' => $registration->verification_token]) }}">Download Card</a>
            <button type="button" class="card-print" onclick="window.print()">Print Card</button>
        </div>
    </div>
</section>
@endsection
