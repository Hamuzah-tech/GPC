@extends('layouts.auth')

@section('title', 'GPC Admin Login')

@section('content')
        <form class="form-content" method="POST" action="{{ route('login.store') }}">
            @csrf
            <p class="login-subtitle">Enter your email and password. We will send a verification code to your email before you can sign in.</p>
            <input type="email" class="input-field" name="email" value="{{ old('email') }}" placeholder="Email address" autofocus required>
            <input type="password" class="input-field" name="password" placeholder="Password" required>
            <button type="submit" class="btn-sign-in">SEND LOGIN CODE</button>
            @if(session('status'))
                <div class="forgot-message" style="display:block;">{{ session('status') }}</div>
            @endif
            @if($errors->any())
                <div class="error-msg" style="display:block;">{{ $errors->first() }}</div>
            @endif
            <div class="auth-link-row">
                <a href="{{ route('password.request') }}">Forgot password?</a>
            </div>
        </form>
@endsection
