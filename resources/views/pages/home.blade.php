@extends('layouts.app')

@section('title', 'GPC - Empowering AGYW in Malawi')
@section('css', asset('css/pages/home.css'))

@section('content')
<section class="hero">
    <div class="hero-slider" id="heroSlider">
        @forelse($heroSlides as $index => $slide)
            <div class="hero-slide {{ $index === 0 ? 'active' : '' }}" style="background-image: url('{{ $slide->url() }}')"></div>
        @empty
            <div class="hero-slide active" style="background-image: url('{{ asset('images/image12.jpg') }}')"></div>
            <div class="hero-slide" style="background-image: url('{{ asset('images/image19.jpg') }}')"></div>
        @endforelse
    </div>
    <div class="hero-overlay"></div>
    <div class="hero-content">
        <div class="welcome-text">Welcome to Girl Power for Change</div>
        <h1>{{ $settings['tagline'] ?? 'Empowering Adolescent Girls & Young Women in Malawi' }}</h1>
    </div>
</section>

<div class="cta-cards-container">
    <div class="cta-card reveal-zoom-in">
        <h3>Volunteer</h3>
        <p>Join your hand with us for a better life and beautiful future of young women.</p>
        <a href="{{ route('volunteer') }}" class="btn-card">Register Now</a>
    </div>
    <div class="cta-card reveal-zoom-in">
        <h3>Fund Us</h3>
        <p>Help Thousands Of Children & Young Women To Get Their Primary Needs & Education.</p>
        <a href="{{ route('contact') }}" class="btn-card">Donate Now</a>
    </div>
    <div class="cta-card reveal-zoom-in">
        <h3>News</h3>
        <p>Stay updated with our latest success stories and community milestones.</p>
        <a href="{{ route('news') }}" class="btn-card">Read More</a>
    </div>
</div>

<section class="section">
    <div class="about-grid">
        <div class="about-content reveal-zoom-out">
            <h3>{{ $about->title ?? 'About GPC' }}</h3>
            @foreach(preg_split("/\n\n/", $about->body ?? "GPC is a women-led organization focused on addressing the diverse needs of Adolescent Girls and Young Women (AGYW) in Malawi. We create a supportive environment for their development through sectoral programs in Lilongwe, Zomba, and Dowa Districts.\n\nOur mission is to empower AGYW through comprehensive programs that address gender equality, education, health, economic empowerment, climate action, and leadership development.") as $paragraph)
                <p>{{ $paragraph }}</p>
            @endforeach
        </div>
        <div class="reveal-zoom-in">
            <h3 class="impact-heading">{{ $about->subtitle ?? 'Our Impact' }}</h3>
            <div class="impact-counters">
                <div class="counter-item"><h4 class="counter" data-target="{{ $settings['impact_districts'] ?? 3 }}">0</h4><p>Districts</p></div>
                <div class="counter-item"><h4 class="counter" data-target="{{ $settings['impact_programs'] ?? 5 }}">0</h4><p>Programs</p></div>
                <div class="counter-item"><h4 class="counter" data-target="{{ $settings['impact_lives'] ?? 10000 }}">0</h4><p>Lives Impacted</p></div>
            </div>
        </div>
    </div>
</section>

@php
    $flashItems = $newsItems->map(function ($item) {
        return [
            'title' => $item->title,
            'date' => optional($item->published_at)->format('F j, Y') ?? 'Recent',
            'excerpt' => $item->excerpt(160),
            'image' => $item->coverUrl(),
            'content' => nl2br(e($item->content)),
            'images' => $item->images->map(fn ($img) => [
                'url' => $img->url(),
                'isCover' => (bool) $img->is_cover,
            ])->values()->all(),
        ];
    });

    if ($flashItems->isEmpty()) {
        $flashItems = collect([[
            'title' => 'GPC Impact Story',
            'date' => 'Latest Update',
            'excerpt' => 'Transforming lives of adolescent girls and young women across Malawi.',
            'image' => asset('images/image19.jpg'),
            'content' => 'Transforming lives of adolescent girls and young women across Malawi.',
            'images' => [],
        ]]);
    }

    $featuredFlash = $flashItems->first();
@endphp

<section class="news-flash-section reveal-zoom-in" id="newsFlash">
    <div class="news-flash-ticker" aria-hidden="true">
        <span class="news-flash-badge">News Flash</span>
        <div class="news-flash-ticker-mask">
            <div class="news-flash-ticker-track">
                @foreach([1, 2] as $loopPass)
                    @foreach($flashItems as $item)
                        <span>{{ $item['title'] }}</span>
                    @endforeach
                    <span>Updates from Girl Power for Change</span>
                @endforeach
            </div>
        </div>
    </div>

    <div class="news-flash-inner">
        <div class="news-flash-head">
            <div>
                <p class="news-flash-kicker">From the field</p>
                <h2>Latest stories</h2>
            </div>
            <a href="{{ route('news') }}" class="news-flash-all">View all news</a>
        </div>

        <div class="news-flash-layout {{ $flashItems->count() === 1 ? 'is-single' : '' }}">
            <article class="news-flash-feature">
                <button type="button" class="news-flash-photo" id="newsFlashOpen" aria-label="Read featured story">
                    <img id="newsFlashImage" src="{{ $featuredFlash['image'] }}" alt="{{ $featuredFlash['title'] }}">
                </button>
                <div class="news-flash-copy">
                    <div>
                        <p class="news-flash-date" id="newsFlashDate">{{ $featuredFlash['date'] }}</p>
                        <h3 id="newsFlashTitle">{{ $featuredFlash['title'] }}</h3>
                    <p class="news-flash-excerpt" id="newsFlashExcerpt" @if($featuredFlash['excerpt'] === $featuredFlash['title']) hidden @endif>{{ $featuredFlash['excerpt'] }}</p>
                    </div>
                    <button type="button" class="news-flash-read" id="newsFlashRead">Read story</button>
                </div>
            </article>

            @if($flashItems->count() > 1)
                <div class="news-flash-list" id="newsFlashList" role="list">
                    @foreach($flashItems as $index => $item)
                        <button
                            type="button"
                            class="news-flash-item {{ $index === 0 ? 'is-active' : '' }}"
                            role="listitem"
                            data-news='@json($item)'
                        >
                            <span class="news-flash-num">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                            <span class="news-flash-item-text">
                                <span class="news-flash-item-date">{{ $item['date'] }}</span>
                                <span class="news-flash-item-title">{{ $item['title'] }}</span>
                            </span>
                            <img src="{{ $item['image'] }}" alt="">
                        </button>
                    @endforeach
                </div>
            @else
                <div id="newsFlashList" hidden>
                    <button type="button" class="news-flash-item is-active" data-news='@json($featuredFlash)'></button>
                </div>
            @endif
        </div>
    </div>
</section>

<div id="newsModal" class="modal">
    <div class="modal-content">
        <button type="button" class="modal-close" id="closeModalBtn" aria-label="Close story"><i class="fas fa-times"></i></button>
        <div class="modal-body">
            <h2 id="modalTitle" class="modal-title"></h2>
            <div id="modalDate" class="modal-date"><i class="far fa-calendar-alt"></i> <span></span></div>
            <div id="modalStory" class="modal-story"></div>
            <div id="modalImagesSection" class="modal-images-section" hidden>
                <div class="modal-images-title">Story gallery</div>
                <div id="modalGallery" class="modal-gallery"></div>
            </div>
        </div>
    </div>
</div>

<section class="slider-section reveal-zoom-out">
    <div class="slider-container">
        <div class="slider-track" id="sliderTrack">
            @php
                $gallery = $galleryImages->count() ? $galleryImages : collect([
                    (object) ['url' => asset('images/image19.jpg'), 'alt' => 'GPC event'],
                    (object) ['url' => asset('images/image13.jpg'), 'alt' => 'Workshop'],
                    (object) ['url' => asset('images/image16.jpg'), 'alt' => 'Community'],
                    (object) ['url' => asset('images/image15.jpg'), 'alt' => 'Empowerment'],
                ]);
            @endphp
            @foreach($gallery as $image)
                <img src="{{ is_object($image) && method_exists($image, 'url') ? $image->url() : ($image->url ?? $image) }}" alt="{{ $image->alt ?? 'GPC' }}">
            @endforeach
            @foreach($gallery as $image)
                <img src="{{ is_object($image) && method_exists($image, 'url') ? $image->url() : ($image->url ?? $image) }}" alt="{{ $image->alt ?? 'GPC' }}">
            @endforeach
        </div>
    </div>
</section>

<section class="join-us-banner reveal-zoom-in">
    <div class="join-us-overlay"></div>
    <div class="container">
        <h2>Join Us in Empowering AGYW</h2>
        <p>{{ $about->extra ?? 'Your support can help us reach more adolescent girls and young women in Malawi with life-changing programs that create sustainable impact.' }}</p>
    </div>
</section>
@endsection

@push('scripts')
<script src="{{ asset('js/home.js') }}"></script>
@endpush
