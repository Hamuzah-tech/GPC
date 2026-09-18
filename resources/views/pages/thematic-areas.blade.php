@extends('layouts.app')

@section('title', 'GPC - Thematic Areas | Empowering AGYW in Malawi')
@section('css', asset('css/pages/thematic-areas.css'))

@section('content')
<section class="thematic-hero">
    <div class="hero-overlay"></div>
    <div class="hero-content">
        <h1>Our Thematic Areas</h1>
        <p>Five interconnected focus areas driving holistic empowerment for AGYW in Malawi</p>
    </div>
</section>

<section class="thematic-section">
    <div class="section-header">
        <h2>What We Do</h2>
        <p>Comprehensive programs addressing the diverse needs of adolescent girls and young women</p>
    </div>
    <div class="thematic-grid">
        @foreach($areas as $area)
            <div class="thematic-card">
                <h3>{{ $area->title }}</h3>
                <p>{{ $area->description }}</p>
            </div>
        @endforeach
    </div>
</section>
@endsection
