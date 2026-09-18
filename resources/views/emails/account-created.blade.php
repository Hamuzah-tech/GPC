<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Your GPC staff account</title>
</head>
<body style="font-family: Arial, sans-serif; color: #1A237E; line-height: 1.6; padding: 24px;">
    <p>Hello {{ $user->name }},</p>
    <p>A Girl Power for Change staff account has been created for you as a <strong>{{ $user->roleLabel() }}</strong>.</p>
    @if (! $user->isAdmin())
        <p>You can open News, Messages, and Volunteers in the admin panel.</p>
    @endif
    <p>Sign in here: <a href="{{ $loginUrl }}">{{ $loginUrl }}</a></p>
    <p>
        Email: {{ $user->email }}<br>
        Password: {{ $plainPassword }}
    </p>
    <p>After you enter your password, a one-time code will be sent to this email to finish signing in.</p>
    <p>If you did not expect this account, contact the GPC team.</p>
    <p>GPC</p>
</body>
</html>
