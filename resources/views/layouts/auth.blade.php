<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>@yield('title', 'GPC Admin Login')</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}?v=2">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}?v=2">
    <link rel="apple-touch-icon" href="{{ asset('images/logo.png') }}?v=2">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/pages/admin.css') }}">
</head>
<body>
<div class="login-modal" style="display:flex;">
    <div class="login-container">
        <div class="login-header">
            <div class="login-logo">
                <img src="{{ asset('images/logo.png') }}" alt="GPC Logo">
            </div>
        </div>
        @yield('content')
        <div class="login-footer">
            @yield('footer')
            <a href="{{ route('home') }}">Back to website</a>
        </div>
    </div>
</div>
</body>
</html>
