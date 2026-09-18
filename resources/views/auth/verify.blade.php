@extends('layouts.auth')

@section('title', 'Verify login code')

@section('content')
        <form class="form-content" method="POST" action="{{ route('login.verify') }}">
            @csrf
            <p class="login-subtitle">Enter the 6-digit code we sent to {{ $maskedEmail }}. The code expires in 10 minutes.</p>
            <input type="text" class="input-field otp-input" name="code" inputmode="numeric" autocomplete="one-time-code" maxlength="6" pattern="\d{6}" placeholder="000000" autofocus required>
            <button type="submit" class="btn-sign-in">VERIFY AND SIGN IN</button>
            @if(session('status'))
                <div class="forgot-message" style="display:block;">{{ session('status') }}</div>
            @endif
            @if($errors->any())
                <div class="error-msg" style="display:block;">{{ $errors->first() }}</div>
            @endif
        </form>
@endsection

@section('footer')
            <form method="POST" action="{{ route('login.verify.resend') }}" class="resend-form">
                @csrf
                <button type="submit" class="btn-link-resend">Resend code</button>
            </form>
@endsection
