@extends('layouts.admin')
@section('title', 'Volunteer Application')
@section('heading', 'Volunteer Application')

@section('content')
<div class="page-card">
    <div class="page-toolbar">
        <h3>{{ $application->full_name }}</h3>
        <div class="admin-actions">
            @if($application->cv_path)
                <x-admin.icon-link :href="route('admin.volunteers.cv', $application)" icon="arrow-down-tray" label="Download CV" tone="view" />
            @endif
            <x-admin.icon-link :href="route('admin.volunteers.index')" icon="arrow-left" label="Back" tone="view" />
        </div>
    </div>
    <p><strong>Email:</strong> {{ $application->email }}</p>
    <p><strong>Phone:</strong> {{ $application->phone }}</p>
    <p><strong>Age:</strong> {{ $application->age ?: 'Not provided' }}</p>
    <p><strong>Gender:</strong> {{ $application->gender ?: 'Not specified' }}</p>
    <p><strong>Location:</strong> {{ $application->location }}</p>
    <p><strong>Skills:</strong> {{ $application->skills ?: 'Not specified' }}</p>
    <p><strong>Interests:</strong> {{ $application->interests ? implode(', ', $application->interests) : 'None selected' }}</p>
    <p><strong>Availability:</strong> {{ $application->availability ?: 'Not specified' }}</p>
    <p><strong>CV:</strong>
        @if($application->cv_path)
            <a href="{{ route('admin.volunteers.cv', $application) }}">{{ $application->cv_original_name ?: 'Download PDF' }}</a>
        @else
            Not uploaded
        @endif
    </p>
    <p style="margin-top:16px;"><strong>Motivation:</strong><br>{{ $application->message }}</p>
</div>
@endsection
