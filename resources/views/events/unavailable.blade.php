@extends('layouts.app')

@section('title', $heading)
@section('css', asset('css/pages/volunteer.css'))

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pages/event.css') }}">
@endpush

@section('content')
<section class="event-result">
    <div class="result-card result-invalid">
        <h1>{{ $heading }}</h1>
        <p>{{ $message }}</p>
    </div>
</section>
@endsection
