@extends('layouts.admin')
@section('title', 'Create user')
@section('heading', 'Create user')

@section('content')
<div class="page-card">
    <h3>New staff account</h3>
    <form method="POST" action="{{ route('admin.users.store') }}">
        @csrf
        <div class="admin-form-group">
            <label>Name</label>
            <input type="text" name="name" value="{{ old('name') }}" required>
        </div>
        <div class="admin-form-group">
            <label>Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required>
        </div>
        <div class="admin-form-group">
            <label>Password</label>
            <input type="password" name="password" required>
        </div>
        <div class="admin-form-group">
            <label>Confirm password</label>
            <input type="password" name="password_confirmation" required>
        </div>
        <div class="admin-form-group">
            <label>Role</label>
            <select name="role" required>
                <option value="user" @selected(old('role', 'user') === 'user')>Regular user</option>
                <option value="admin" @selected(old('role') === 'admin')>Admin</option>
            </select>
        </div>
        <p style="font-size:0.8rem;color:#64748b;margin-bottom:16px;">Regular users can only open News, Messages, and Volunteers. Admins can manage the full site. The new person will receive an email with their login details.</p>
        <button class="btn-primary" type="submit">Create user</button>
    </form>
</div>
@endsection
