<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $registration->registration_id }}</title>
    <style>
        @page { margin: 0; }
        * { box-sizing: border-box; }
        body { margin: 0; font-family: DejaVu Sans, sans-serif; color: #333333; }
        .header { background: #1A237E; color: #ffffff; text-align: center; padding: 28px 24px 22px; }
        .logo-wrap { background: #ffffff; display: inline-block; padding: 6px 8px; border-radius: 8px; }
        .logo { height: 42px; }
        .kicker { letter-spacing: 2px; font-size: 11px; color: #F128C8; margin: 16px 0 0; }
        h1 { font-size: 28px; letter-spacing: 1px; margin: 8px 0 0; }
        .tagline { margin: 4px 0 0; font-size: 13px; letter-spacing: 1px; }
        .accent { height: 7px; background: #F128C8; }
        .body { padding: 28px 32px 18px; text-align: center; }
        .role { color: #F128C8; font-size: 12px; letter-spacing: 1px; margin: 0; text-transform: uppercase; }
        .name { font-size: 26px; color: #1A237E; margin: 10px 0 4px; }
        .year { margin: 0; font-size: 14px; letter-spacing: 1px; }
        table.meta { width: 100%; margin-top: 18px; border-collapse: separate; }
        table.meta td { background: #f4f6fb; padding: 10px 8px; text-align: center; }
        .label { display: block; font-size: 10px; letter-spacing: 1px; color: #666666; text-transform: uppercase; }
        .value { display: block; margin-top: 4px; font-size: 14px; color: #1A237E; font-weight: bold; }
        .qr { margin-top: 18px; text-align: center; }
        .qr img { width: 190px; height: 190px; }
        .scan { font-size: 11px; letter-spacing: 2px; color: #1A237E; margin: 8px 0 0; }
        .footer { background: #1A237E; color: #ffffff; text-align: center; padding: 14px; font-size: 11px; margin-top: 8px; }
    </style>
</head>
<body>
    <div class="header">
        @if($logoDataUri)
            <div class="logo-wrap"><img class="logo" src="{{ $logoDataUri }}" alt="GPC"></div>
        @endif
        <p class="kicker">{{ $registration->event->session_label }}</p>
        <h1>{{ $registration->event->headline }}</h1>
        <p class="tagline">{{ $registration->event->tagline }}</p>
    </div>
    <div class="accent"></div>
    <div class="body">
        <p class="role">Mentorship Participant</p>
        <div class="name">{{ $registration->full_name }}</div>
        <p class="year">{{ $registration->year_of_study }}</p>
        <table class="meta">
            <tr>
                <td>
                    <span class="label">Registration ID</span>
                    <span class="value">{{ $registration->registration_id }}</span>
                </td>
                <td>
                    <span class="label">Student No.</span>
                    <span class="value">{{ $registration->registration_number }}</span>
                </td>
            </tr>
        </table>
        <div class="qr">
            <img src="{{ $qrDataUri }}" alt="QR code">
            <p class="scan">SCAN TO VERIFY</p>
        </div>
    </div>
    <div class="footer">Girl Power for Change</div>
</body>
</html>
