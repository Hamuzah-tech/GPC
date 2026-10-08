@extends('layouts.admin')

@section('title', 'GPC Admin Dashboard')
@section('heading', 'Dashboard')

@section('content')
<div class="stats-cards">
    <a class="stat-card" href="{{ route('admin.news.index') }}">
        <div class="stat-icon"><x-icon name="newspaper" /></div>
        <div class="stat-info"><h3>{{ $newsCount }}</h3><p>News articles</p></div>
    </a>
    <a class="stat-card" href="{{ route('admin.messages.index') }}">
        <div class="stat-icon"><x-icon name="envelope" /></div>
        <div class="stat-info"><h3>{{ $unreadMessages }}</h3><p>Unread messages</p></div>
    </a>
    <a class="stat-card" href="{{ route('admin.volunteers.index') }}">
        <div class="stat-icon"><x-icon name="user-plus" /></div>
        <div class="stat-info"><h3>{{ $volunteerCount }}</h3><p>Volunteers</p></div>
    </a>
    <a class="stat-card" href="{{ route('admin.leaders.index') }}">
        <div class="stat-icon"><x-icon name="users" /></div>
        <div class="stat-info"><h3>{{ $leaderCount }}</h3><p>Leaders</p></div>
    </a>
    <a class="stat-card" href="{{ route('admin.programs.index') }}">
        <div class="stat-icon"><x-icon name="photo" /></div>
        <div class="stat-info"><h3>{{ $programCount }}</h3><p>Programs</p></div>
    </a>
    <a class="stat-card" href="{{ route('admin.news.index') }}">
        <div class="stat-icon"><x-icon name="photo" /></div>
        <div class="stat-info"><h3>{{ $imageCount }}</h3><p>News images</p></div>
    </a>
</div>

<h3 class="dashboard-section-label">Event registrations</h3>
<div class="stats-cards">
    <a class="stat-card" href="{{ route('admin.event-registrations.index') }}">
        <div class="stat-icon"><x-icon name="identification" /></div>
        <div class="stat-info"><h3>{{ $registrationCount }}</h3><p>Total registrations</p></div>
    </a>
    <a class="stat-card" href="{{ route('admin.event-registrations.index', ['status' => 'pending']) }}">
        <div class="stat-icon"><x-icon name="exclamation" /></div>
        <div class="stat-info"><h3>{{ $pendingRegistrations }}</h3><p>Pending</p></div>
    </a>
    <a class="stat-card" href="{{ route('admin.event-registrations.index', ['status' => 'accepted']) }}">
        <div class="stat-icon"><x-icon name="check" /></div>
        <div class="stat-info"><h3>{{ $acceptedRegistrations }}</h3><p>Accepted</p></div>
    </a>
    <a class="stat-card" href="{{ route('admin.event-registrations.index', ['status' => 'rejected']) }}">
        <div class="stat-icon"><x-icon name="x-mark" /></div>
        <div class="stat-info"><h3>{{ $rejectedRegistrations }}</h3><p>Rejected</p></div>
    </a>
    <a class="stat-card" href="{{ route('admin.event-registrations.index', ['registered' => 'today']) }}">
        <div class="stat-icon"><x-icon name="user-plus" /></div>
        <div class="stat-info"><h3>{{ $todayRegistrations }}</h3><p>Today's registrations</p></div>
    </a>
</div>

<div class="dashboard dashboard-overview">
    <div class="news-list-section">
        <h3>Recent news</h3>
        <div class="news-list">
            @forelse($recentNews as $item)
                <a class="news-item" href="{{ route('admin.news.edit', $item) }}">
                    <div class="news-info">
                        <div class="news-title">{{ $item->title }}</div>
                        <div class="news-date">{{ optional($item->published_at)->format('M j, Y') }}</div>
                    </div>
                </a>
            @empty
                <div class="empty">No news articles yet</div>
            @endforelse
        </div>
    </div>
    <div class="news-list-section">
        <h3>Recent messages</h3>
        <div class="news-list">
            @forelse($recentMessages as $message)
                <a class="news-item" href="{{ route('admin.messages.show', $message) }}">
                    <div class="news-info">
                        <div class="news-title">{{ $message->name }}</div>
                        <div class="news-date">{{ $message->created_at->format('M j, Y') }}</div>
                        <div class="news-excerpt">{{ \Illuminate\Support\Str::limit($message->message, 70) }}</div>
                    </div>
                </a>
            @empty
                <div class="empty">No messages yet</div>
            @endforelse
        </div>
    </div>
</div>
@endsection
