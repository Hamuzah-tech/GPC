@extends('layouts.app')

@section('title', !empty($registrationSucceeded) ? 'Registration Successful' : 'Register for the Event')
@section('css', asset('css/pages/volunteer.css'))

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pages/event.css') }}">
@endpush

@section('content')
<div id="registrationStage" @if(!empty($registrationSucceeded)) inert data-depth="0.12" @endif>
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
        <p>Register for the “{{ $event->name }}” mentorship session for first-year Girls.</p>
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
                    <input type="text" id="full_name" name="full_name" value="{{ old('full_name') }}" required maxlength="255" autocomplete="name" @error('full_name') aria-invalid="true" aria-describedby="full-name-error" @enderror>
                    @error('full_name')
                        <p class="field-error" id="full-name-error">{{ $message }}</p>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="registration_number">Registration Number <span class="required">*</span></label>
                    <input type="text" id="registration_number" name="registration_number" value="{{ old('registration_number') }}" required maxlength="100" autocomplete="off" @error('registration_number') aria-invalid="true" aria-describedby="registration-number-error" @enderror>
                    @error('registration_number')
                        <p class="field-error" id="registration-number-error">{{ $message }}</p>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="phone">Phone Number <span class="required">*</span></label>
                    <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" required maxlength="50" autocomplete="tel" @error('phone') aria-invalid="true" aria-describedby="phone-error" @enderror>
                    @error('phone')
                        <p class="field-error" id="phone-error">{{ $message }}</p>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="email">Email Address <span class="required">*</span></label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required maxlength="255" autocomplete="email" @error('email') aria-invalid="true" aria-describedby="email-error" @enderror>
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
</div>

@if(!empty($registrationSucceeded))
<div class="success-modal" id="successModal" role="dialog" aria-modal="true" aria-labelledby="success-title">
    <div class="success-scene" data-success-scene aria-hidden="true">
        <span class="success-orb success-orb-a" data-depth="0.7"></span>
        <span class="success-orb success-orb-b" data-depth="1.15"></span>
        <span class="success-orb success-orb-c" data-depth="0.42"></span>
    </div>
    <div class="success-dialog-float" data-depth="0.28">
        <div class="success-dialog" tabindex="-1">
            <h1 id="success-title">Registration Successful!</h1>
            <p>Thank you for registering for “{{ $event->name }}”. Your registration has been received and is currently awaiting confirmation from the organising team.</p>
            <a class="submit-btn success-home" href="{{ route('home') }}">Return to the homepage</a>
        </div>
    </div>
</div>
@endif
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
@if(!empty($registrationSucceeded))
<script>
    (function () {
        const modal = document.getElementById('successModal');
        const stage = document.getElementById('registrationStage');
        const dialog = modal?.querySelector('.success-dialog');
        if (!modal) {
            return;
        }

        document.body.classList.add('success-open');
        document.querySelectorAll('header, footer, .scroll-up').forEach(function (element) {
            element.setAttribute('inert', '');
        });

        const motion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        const nodes = [stage, ...modal.querySelectorAll('[data-depth]')].filter(Boolean);
        let targetX = 0;
        let targetY = 0;
        let currentX = 0;
        let currentY = 0;
        let frame = 0;

        function reveal() {
            modal.classList.add('is-in');
            dialog?.focus();
            if (!motion) {
                return;
            }
            const started = performance.now();
            function tick(now) {
                currentX += (targetX - currentX) * 0.065;
                currentY += (targetY - currentY) * 0.065;
                const time = (now - started) / 1000;
                nodes.forEach(function (node) {
                    const depth = parseFloat(node.dataset.depth || '0');
                    const driftX = Math.sin(time * 0.32 + depth * 6) * 22 * depth;
                    const driftY = Math.cos(time * 0.24 + depth * 4) * 16 * depth;
                    const x = currentX * 34 * depth + driftX;
                    const y = currentY * 26 * depth + driftY;
                    node.style.transform = 'translate3d(' + x.toFixed(2) + 'px, ' + y.toFixed(2) + 'px, 0)';
                });
                frame = window.requestAnimationFrame(tick);
            }
            frame = window.requestAnimationFrame(tick);
        }

        modal.addEventListener('pointermove', function (event) {
            const rect = modal.getBoundingClientRect();
            targetX = ((event.clientX - rect.left) / rect.width - 0.5) * 2;
            targetY = ((event.clientY - rect.top) / rect.height - 0.5) * 2;
        });

        function onLoaderGone() {
            window.setTimeout(reveal, motion ? 0 : 80);
        }

        const loader = document.getElementById('loaderWrapper');
        if (!loader || loader.classList.contains('hide')) {
            onLoaderGone();
        } else {
            const observer = new MutationObserver(function () {
                if (loader.classList.contains('hide')) {
                    observer.disconnect();
                    onLoaderGone();
                }
            });
            observer.observe(loader, { attributes: true, attributeFilter: ['class'] });
            window.setTimeout(function () {
                if (!modal.classList.contains('is-in')) {
                    reveal();
                }
            }, 1800);
        }

        window.addEventListener('pagehide', function () {
            if (frame) {
                window.cancelAnimationFrame(frame);
            }
        });
    })();
</script>
@endif
@endpush
