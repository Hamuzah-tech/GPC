@extends('layouts.admin')
@section('title', 'Event card')
@section('heading', 'Event card')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pages/event.css') }}">
@endpush

@section('content')
<div class="page-card">
    <div class="page-toolbar no-print">
        <h3>{{ $registration->full_name }}</h3>
        <div class="admin-actions">
            <a class="btn-inline btn-inline-neutral" href="{{ route('admin.event-registrations.card.download', $registration) }}">Download Card</a>
            <a class="btn-inline btn-inline-neutral" href="{{ route('admin.event-registrations.show', $registration) }}">Back</a>
        </div>
    </div>
    <div class="card-page">
        @include('events.partials.card')
    </div>
</div>
@endsection
