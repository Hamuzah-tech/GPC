<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>You Have Been Accepted</title>
</head>
<body style="font-family: Arial, sans-serif; color: #1A237E; line-height: 1.6; padding: 24px;">
    <p>Dear {{ $registration->full_name }},</p>
    <p>We are pleased to inform you that your registration for <strong>“{{ $registration->event->name }}”</strong> has been accepted.</p>
    <p>You are officially part of the mentorship session.</p>
    <p><strong>Registration ID:</strong> {{ $registration->registration_id }}</p>
    <p>Please keep your digital event card available as it may be required for identification and attendance at the event.</p>
    <p>We look forward to having you with us.</p>
    <p><strong>{{ $registration->event->name }}</strong></p>
    <p style="margin: 28px 0;">
        <a href="{{ $cardUrl }}" style="display:inline-block;background:#F128C8;color:#ffffff;text-decoration:none;padding:12px 22px;border-radius:24px;font-weight:bold;">View My Event Card</a>
    </p>
    <p>Best regards,<br><strong>The Organising Team</strong></p>
</body>
</html>
