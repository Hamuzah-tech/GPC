@extends('layouts.app')

@section('title', 'GPC - Leadership | Empowering AGYW in Malawi')
@section('css', asset('css/pages/leadership.css'))

@section('content')
<section class="leadership-hero">
    <div class="hero-overlay"></div>
    <div class="hero-content">
        <h1>Leadership</h1>
        <p>Meet the passionate team driving change for AGYW in Malawi</p>
    </div>
</section>

<section class="leadership-section">
    <div class="section-header">
        <h2>Our Leadership Team</h2>
        <p>Dedicated experts committed to empowering adolescent girls and young women</p>
    </div>
    <div class="leadership-grid" id="leadershipGrid">
        @foreach($leaders as $leader)
            <div class="leader-card"
                 data-name="{{ $leader->name }}"
                 data-position="{{ $leader->position }}"
                 data-bio="{{ $leader->full_bio }}"
                 data-image="{{ $leader->imageUrl() }}">
                <img src="{{ $leader->imageUrl() }}" alt="{{ $leader->name }}" class="leader-image" onerror="this.src='https://placehold.co/300x280/1A237E/white?text=GPC'">
                <div class="leader-info">
                    <h3 class="leader-name">{{ $leader->name }}</h3>
                    <div class="leader-position">{{ $leader->position }}</div>
                    <p class="leader-bio-preview">{{ $leader->bio_preview }}</p>
                    <button class="read-more-btn" type="button">Read More <i class="fas fa-arrow-right"></i></button>
                </div>
            </div>
        @endforeach
    </div>
</section>

<div class="modal" id="leaderModal">
    <div class="modal-content">
        <span class="modal-close" id="modalClose">&times;</span>
        <img src="" alt="Leader" class="modal-image" id="modalImage">
        <h2 class="modal-name" id="modalName"></h2>
        <div class="modal-position" id="modalPosition"></div>
        <div class="modal-bio" id="modalBio"></div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/leadership.js') }}"></script>
@endpush
