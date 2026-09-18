<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>GPC login code</title>
</head>
<body style="font-family: Arial, sans-serif; color: #1A237E; line-height: 1.6; padding: 24px;">
    <p>Hello {{ $user->name }},</p>
    <p>Use this code to finish signing in to the Girl Power for Change admin panel:</p>
    <p style="font-size: 32px; letter-spacing: 8px; font-weight: 700; color: #F128C8;">{{ $code }}</p>
    <p>This code expires in 10 minutes. If you did not try to sign in, you can ignore this email.</p>
</body>
</html>
