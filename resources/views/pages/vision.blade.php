@extends('layouts.app')

@section('title', 'GPC - Our Vision | Empowering AGYW in Malawi')
@section('css', asset('css/pages/vision.css'))

@section('content')
<section class="vision-hero">
    <div class="hero-overlay"></div>
    <div class="hero-content">
        <h1>{{ $page->title ?? 'Our Vision' }}</h1>
        <p>{{ $page->subtitle ?? 'Imagining a brighter future for every adolescent girl and young woman in Malawi' }}</p>
    </div>
</section>

<section class="vision-statement">
    <div class="vision-card">
        <div class="vision-text">
            {{ $page->body ?? 'A Malawian society where adolescent girls and young women are empowered and supported to become agents of change for both economic and social development.' }}
        </div>
        <div class="supporting-text">
            {{ $page->extra ?? 'We envision a future where every AGYW in Malawi has the opportunity, resources, and support to reach her full potential and contribute meaningfully to her community.' }}
        </div>
    </div>
</section>
@endsection
