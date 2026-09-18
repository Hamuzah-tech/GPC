@extends('layouts.auth')

@section('title', 'Forgot password')

@section('content')
        <form class="form-content" method="POST" action="{{ route('password.email') }}">
            @csrf
            <p class="login-subtitle">Enter your admin email. If it is registered, we will send a reset code.</p>
            <input type="email" class="input-field" name="email" value="{{ old('email') }}" placeholder="Email address" autofocus required>
            <button type="submit" class="btn-sign-in">SEND RESET CODE</button>
            @if($errors->any())
                <div class="error-msg" style="display:block;">{{ $errors->first() }}</div>
            @endif
            <div class="auth-link-row">
                <a href="{{ route('login') }}">Back to sign in</a>
            </div>
        </form>
@endsection
