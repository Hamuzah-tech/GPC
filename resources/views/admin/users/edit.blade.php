@extends('layouts.admin')
@section('title', 'Edit user')
@section('heading', 'Edit user')

@section('content')
<div class="page-card">
    <h3>{{ $user->name }}</h3>
    <form method="POST" action="{{ route('admin.users.update', $user) }}">
        @csrf
        @method('PUT')
        <div class="admin-form-group">
            <label>Name</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" required>
        </div>
        <div class="admin-form-group">
            <label>Email</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" required>
        </div>
        <div class="admin-form-group">
            <label>New password</label>
            <input type="password" name="password">
        </div>
        <div class="admin-form-group">
            <label>Confirm new password</label>
            <input type="password" name="password_confirmation">
        </div>
        <div class="admin-form-group">
            <label>Role</label>
            <select name="role" required>
                <option value="user" @selected(old('role', $user->role) === 'user')>Regular user</option>
                <option value="admin" @selected(old('role', $user->role) === 'admin')>Admin</option>
            </select>
        </div>
        <p style="font-size:0.8rem;color:#64748b;margin-bottom:16px;">Leave the password blank to keep the current password.</p>
        <button class="btn-primary" type="submit">Save user</button>
    </form>
</div>
@endsection
