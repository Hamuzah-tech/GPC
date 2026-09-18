@extends('layouts.admin')
@section('title', 'Settings')
@section('heading', 'Settings')

@section('content')
<div class="page-card">
    <h3>Website Settings</h3>
    <form method="POST" action="{{ route('admin.settings.update') }}">
        @csrf @method('PUT')
        <div class="admin-form-group"><label>Site Name</label><input type="text" name="site_name" value="{{ old('site_name', $settings['site_name'] ?? 'GPC') }}" required></div>
        <div class="admin-form-group"><label>Tagline</label><input type="text" name="tagline" value="{{ old('tagline', $settings['tagline'] ?? '') }}"></div>
        <div class="admin-form-group"><label>Footer About Text</label><textarea name="about_footer" rows="4">{{ old('about_footer', $settings['about_footer'] ?? '') }}</textarea></div>
        <div class="admin-form-group"><label>Address</label><input type="text" name="address" value="{{ old('address', $settings['address'] ?? '') }}"></div>
        <div class="admin-form-group"><label>Phone</label><input type="text" name="phone" value="{{ old('phone', $settings['phone'] ?? '') }}"></div>
        <div class="admin-form-group"><label>Email</label><input type="email" name="email" value="{{ old('email', $settings['email'] ?? '') }}"></div>
        <div class="admin-form-group"><label>Office Hours</label><input type="text" name="office_hours" value="{{ old('office_hours', $settings['office_hours'] ?? '') }}"></div>
        <div class="admin-form-group"><label>Facebook URL</label><input type="url" name="facebook_url" value="{{ old('facebook_url', $settings['facebook_url'] ?? '') }}"></div>
        <div class="admin-form-group"><label>Instagram URL</label><input type="url" name="instagram_url" value="{{ old('instagram_url', $settings['instagram_url'] ?? '') }}"></div>
        <div class="admin-form-group"><label>Impact: Districts</label><input type="number" name="impact_districts" value="{{ old('impact_districts', $settings['impact_districts'] ?? 3) }}"></div>
        <div class="admin-form-group"><label>Impact: Programs</label><input type="number" name="impact_programs" value="{{ old('impact_programs', $settings['impact_programs'] ?? 5) }}"></div>
        <div class="admin-form-group"><label>Impact: Lives</label><input type="number" name="impact_lives" value="{{ old('impact_lives', $settings['impact_lives'] ?? 10000) }}"></div>
        <button class="btn-primary" type="submit">Save Settings</button>
    </form>
</div>
@endsection
