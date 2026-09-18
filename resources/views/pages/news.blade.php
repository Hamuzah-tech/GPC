@extends('layouts.app')

@section('title', 'GPC - News | Empowering AGYW in Malawi')
@section('css', asset('css/pages/news.css'))

@section('content')
<section class="news-hero">
    <div class="hero-content">
        <p>Stay updated with empowering stories, program milestones, and community impact from Girl Power for Change</p>
        <div class="search-container">
            <form method="GET" action="{{ route('news') }}">
                <input type="text" id="searchInput" name="q" value="{{ $search }}" placeholder="Search news by title...">
            </form>
        </div>
    </div>
</section>

@if($news->count())
    <div id="newsGridContainer" class="news-grid">
        @foreach($news as $item)
            @php
                $payload = [
                    'title' => $item->title,
                    'date' => optional($item->published_at)->format('F j, Y') ?? 'Recent',
                    'content' => nl2br(e($item->content)),
                    'images' => $item->images->map(fn ($img) => [
                        'url' => $img->url(),
                        'isCover' => $img->is_cover,
                    ])->values()->all(),
                ];
            @endphp
            <div class="news-card reveal-zoom-in" data-news='@json($payload)'>
                <div class="news-card-image">
                    <img src="{{ $item->coverUrl() }}" alt="{{ $item->title }}" loading="lazy" onerror="this.src='https://placehold.co/600x400/1A237E/white?text=GPC+News'">
                </div>
                <div class="news-card-content">
                    <div class="news-date"><i class="far fa-calendar-alt"></i> {{ optional($item->published_at)->format('F j, Y') ?? 'Recent' }}</div>
                    <h3 class="news-title line-clamp-2">{{ $item->title }}</h3>
                    <p class="news-excerpt line-clamp-3">{{ $item->excerpt(100) }}</p>
                    <span class="read-more-btn">Read More <i class="fas fa-arrow-right"></i></span>
                </div>
            </div>
        @endforeach
    </div>

    @if($news->lastPage() > 1)
        <div id="paginationContainer" class="pagination">
            <a class="page-btn {{ $news->onFirstPage() ? 'disabled' : '' }}" href="{{ $news->previousPageUrl() ?? '#' }}"><i class="fas fa-chevron-left"></i></a>
            @foreach($news->getUrlRange(max(1, $news->currentPage() - 2), min($news->lastPage(), $news->currentPage() + 2)) as $page => $url)
                <a class="page-btn {{ $page == $news->currentPage() ? 'active' : '' }}" href="{{ $url }}">{{ $page }}</a>
            @endforeach
            <a class="page-btn {{ $news->currentPage() == $news->lastPage() ? 'disabled' : '' }}" href="{{ $news->nextPageUrl() ?? '#' }}"><i class="fas fa-chevron-right"></i></a>
            <span class="page-info">Page {{ $news->currentPage() }} of {{ $news->lastPage() }}</span>
        </div>
    @endif
@else
    <div id="emptyState" class="empty-state">
        <h3>No News Found</h3>
        <p>Check back soon for inspiring stories and updates from GPC Malawi.</p>
    </div>
@endif

<div id="newsModal" class="modal">
    <div class="modal-content">
        <div class="modal-close" id="closeModalBtn"><i class="fas fa-times"></i></div>
        <div class="modal-body">
            <h2 id="modalTitle" class="modal-title"></h2>
            <div id="modalDate" class="modal-date"><i class="far fa-calendar-alt"></i> <span></span></div>
            <div id="modalStory" class="modal-story"></div>
            <div id="modalImagesSection" class="modal-images-section" style="display: none;">
                <div class="modal-images-title"><i class="fas fa-images"></i> Story Gallery</div>
                <div id="modalGallery" class="modal-gallery"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/news.js') }}"></script>
@endpush
