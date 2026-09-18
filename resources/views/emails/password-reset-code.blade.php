<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>GPC password reset code</title>
</head>
<body style="font-family: Arial, sans-serif; color: #1A237E; line-height: 1.6; padding: 24px;">
    <p>Hello {{ $user->name }},</p>
    <p>Use this code to reset your Girl Power for Change admin password:</p>
    <p style="font-size: 32px; letter-spacing: 8px; font-weight: 700; color: #F128C8;">{{ $code }}</p>
    <p>This code expires in 10 minutes. If you did not request a password reset, you can ignore this email.</p>
</body>
</html>
