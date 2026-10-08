@extends('layouts.app')

@section('title', 'Register for the Event')
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
    <div class="section-header">
        <h2>{{ $event->name }}</h2>
        <p>Register for the “{{ $event->name }}” mentorship session for first-year women.</p>
    </div>

    <div class="volunteer-form">
        @if(session('already_registered'))
            <div class="notice-panel" role="alert">
                <h2>Already Registered</h2>
                <p>Our records show that you have already registered for this event. If you believe this is an error, please contact the event organisers.</p>
            </div>
        @endif

        <form id="eventRegistrationForm" action="{{ route('events.register.store') }}" method="POST">
            @csrf
            <div class="form-grid">
                <div class="form-group">
                    <label for="full_name">Full Name <span class="required">*</span></label>
                    <input type="text" id="full_name" name="full_name" value="{{ old('full_name') }}" required maxlength="255" autocomplete="name" placeholder="Mary Banda" @error('full_name') aria-invalid="true" aria-describedby="full-name-error" @enderror>
                    @error('full_name')
                        <p class="field-error" id="full-name-error">{{ $message }}</p>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="registration_number">Registration Number <span class="required">*</span></label>
                    <input type="text" id="registration_number" name="registration_number" value="{{ old('registration_number') }}" required maxlength="100" autocomplete="off" placeholder="UNIMA-2026-001" @error('registration_number') aria-invalid="true" aria-describedby="registration-number-error" @enderror>
                    @error('registration_number')
                        <p class="field-error" id="registration-number-error">{{ $message }}</p>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="phone">Phone Number <span class="required">*</span></label>
                    <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" required maxlength="50" autocomplete="tel" placeholder="+265 XXX XXX XXX" @error('phone') aria-invalid="true" aria-describedby="phone-error" @enderror>
                    @error('phone')
                        <p class="field-error" id="phone-error">{{ $message }}</p>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="email">Email Address <span class="required">*</span></label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required maxlength="255" autocomplete="email" placeholder="you@example.com" @error('email') aria-invalid="true" aria-describedby="email-error" @enderror>
                    @error('email')
                        <p class="field-error" id="email-error">{{ $message }}</p>
                    @enderror
                </div>
                <div class="form-group full-width">
                    <label for="year_of_study">Year of Study <span class="required">*</span></label>
                    <input type="text" id="year_of_study" name="year_of_study" value="Year 1" readonly required aria-describedby="year-help">
                    <p class="file-hint" id="year-help">This mentorship session is for first-year students.</p>
                    @error('year_of_study')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            <button type="submit" class="submit-btn" id="submitBtn">Register for the Event</button>
        </form>
    </div>
</section>
@endsection

@push('scripts')
<script>
    (function () {
        const form = document.getElementById('eventRegistrationForm');
        const button = document.getElementById('submitBtn');
        let submitted = false;

        form?.addEventListener('submit', function (event) {
            if (submitted) {
                event.preventDefault();
                return;
            }
            submitted = true;
            window.setTimeout(function () {
                if (!button) {
                    return;
                }
                button.disabled = true;
                button.textContent = 'Submitting...';
            }, 0);
        });
    })();
</script>
@endpush
