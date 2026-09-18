<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'GPC Admin')</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}?v=2">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}?v=2">
    <link rel="apple-touch-icon" href="{{ asset('images/logo.png') }}?v=2">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/pages/admin.css') }}">
    @stack('styles')
</head>
<body>
@php
    $isAdmin = auth()->user()->isAdmin();
    $contentWorkspace = $isAdmin && request()->routeIs(
        'admin.dashboard',
        'admin.leaders.*',
        'admin.programs.*',
        'admin.pillars.*',
        'admin.thematic-areas.*',
        'admin.strategic-approaches.*',
        'admin.faqs.*',
        'admin.pages.*',
        'admin.gallery.*'
    );
@endphp
<div class="admin-container active">
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <img src="{{ asset('images/logo.png') }}" alt="GPC" class="sidebar-logo">
            <div>
                <h3>GPC Admin</h3>
            </div>
        </div>
        <nav class="sidebar-menu">
            @if($isAdmin)
                <a class="menu-item {{ $contentWorkspace ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                    <x-icon name="home" />
                    <span>Dashboard</span>
                </a>
            @endif
            <a class="menu-item {{ request()->routeIs('admin.news.*') ? 'active' : '' }}" href="{{ route('admin.news.index') }}">
                <x-icon name="newspaper" />
                <span>News</span>
            </a>
            <a class="menu-item {{ request()->routeIs('admin.messages.*') ? 'active' : '' }}" href="{{ route('admin.messages.index') }}">
                <x-icon name="envelope" />
                <span>Messages</span>
            </a>
            <a class="menu-item {{ request()->routeIs('admin.volunteers.*') ? 'active' : '' }}" href="{{ route('admin.volunteers.index') }}">
                <x-icon name="user-plus" />
                <span>Volunteers</span>
            </a>
            @if($isAdmin)
                <a class="menu-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                    <x-icon name="users" />
                    <span>Users</span>
                </a>
                <a class="menu-item {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}" href="{{ route('admin.settings.edit') }}">
                    <x-icon name="cog" />
                    <span>Settings</span>
                </a>
            @endif
        </nav>
        <div class="logout-container">
            <div class="user-profile">
                <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                <div class="user-info-sidebar">
                    <div class="user-name">{{ auth()->user()->name }}</div>
                    <div class="user-email-sidebar">{{ auth()->user()->email }}</div>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-btn-sidebar">
                    <x-icon name="logout" />
                    Sign out
                </button>
            </form>
        </div>
    </aside>
    <div class="sidebar-backdrop" id="sidebar-backdrop"></div>

    <div class="main-content">
        <div class="top-bar">
            <div class="top-bar-left">
                <button class="mobile-menu-btn" id="mobileMenuBtn" type="button" aria-expanded="false" aria-controls="sidebar">
                    <x-icon name="bars" />
                    Menu
                </button>
                <h2>@yield('heading', 'Dashboard')</h2>
            </div>
            <div class="user-info-top">
                <span>{{ auth()->user()->email }}</span>
            </div>
        </div>

        @if($contentWorkspace)
            <div class="content-tabs-wrap">
                @include('admin.partials.content-tabs')
            </div>
        @endif

        @yield('content')
    </div>
</div>

<div id="toast-stack" class="toast-stack"></div>

<div id="confirm-modal" class="confirm-modal" role="dialog" aria-modal="true" aria-labelledby="confirm-title">
    <div class="confirm-modal-card">
        <div class="confirm-modal-icon">
            <x-icon name="exclamation" />
        </div>
        <h3 id="confirm-title">Delete this item?</h3>
        <p>This action cannot be undone.</p>
        <div class="confirm-modal-actions">
            <button type="button" id="confirm-cancel" class="btn-cancel">Cancel</button>
            <button type="button" id="confirm-ok" class="btn-confirm">Delete</button>
        </div>
    </div>
</div>

<script src="{{ asset('js/admin.js') }}"></script>
<script>
    @if(session('success'))
        showAdminToast(@json(session('success')), 'success');
    @endif
    @if(session('error'))
        showAdminToast(@json(session('error')), 'error');
    @endif
    @if($errors->any())
        showAdminToast(@json($errors->first()), 'error');
    @endif
</script>
</body>
</html>
