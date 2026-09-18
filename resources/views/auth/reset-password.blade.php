@extends('layouts.auth')

@section('title', 'Reset password')

@section('content')
        <form class="form-content" method="POST" action="{{ route('password.update') }}">
            @csrf
            <p class="login-subtitle">Enter the 6-digit code sent to {{ $maskedEmail }}, then choose a new password.</p>
            <input type="email" class="input-field" name="email" value="{{ old('email', $email) }}" required>
            <input type="text" class="input-field otp-input" name="code" inputmode="numeric" autocomplete="one-time-code" maxlength="6" pattern="\d{6}" placeholder="000000" autofocus required>
            <input type="password" class="input-field" name="password" placeholder="New password" required>
            <input type="password" class="input-field" name="password_confirmation" placeholder="Confirm new password" required>
            <button type="submit" class="btn-sign-in">RESET PASSWORD</button>
            @if(session('status'))
                <div class="forgot-message" style="display:block;">{{ session('status') }}</div>
            @endif
            @if($errors->any())
                <div class="error-msg" style="display:block;">{{ $errors->first() }}</div>
            @endif
            <div class="auth-link-row">
                <a href="{{ route('password.request') }}">Request a new code</a>
            </div>
        </form>
@endsection
