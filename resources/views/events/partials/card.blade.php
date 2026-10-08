<article class="pass-card">
    <header class="pass-header">
        @if($logoDataUri)
            <div class="pass-logo-wrap">
                <img src="{{ $logoDataUri }}" alt="GPC" class="pass-logo">
            </div>
        @endif
        <p class="pass-kicker">{{ $registration->event->session_label }}</p>
        <h2>{{ $registration->event->headline }}</h2>
        <p class="pass-tagline">{{ $registration->event->tagline }}</p>
    </header>
    <div class="pass-accent"></div>
    <div class="pass-body">
        <p class="pass-role">Mentorship Participant</p>
        <h3 class="pass-name">{{ $registration->full_name }}</h3>
        <p class="pass-year">{{ $registration->year_of_study }}</p>
        <dl class="pass-meta">
            <div>
                <dt>Registration ID</dt>
                <dd>{{ $registration->registration_id }}</dd>
            </div>
            <div>
                <dt>Student No.</dt>
                <dd>{{ $registration->registration_number }}</dd>
            </div>
        </dl>
        <div class="pass-qr">
            <img src="{{ $qrDataUri }}" alt="QR code that opens this participant's verification page">
            <p>SCAN TO VERIFY</p>
        </div>
    </div>
    <footer class="pass-footer">Girl Power for Change</footer>
</article>
