@extends('layouts.app')

@section('title', 'GPC - Our Programs | Empowering AGYW in Malawi')
@section('css', asset('css/pages/programs.css'))

@section('content')
<section class="programs-hero">
    <div class="hero-overlay"></div>
    <div class="hero-content">
        <h1>Our Programs</h1>
        <p>Comprehensive initiatives empowering adolescent girls and young women across Malawi</p>
    </div>
</section>

<section class="programs-section">
    <div class="section-header">
        <h2>What We Offer</h2>
        <p>Six transformative programs designed to address the diverse needs of AGYW in Lilongwe, Zomba, and Dowa Districts</p>
    </div>
    <div class="programs-grid">
        @foreach($programs as $program)
            <div class="program-card reveal-zoom-in">
                <img src="{{ $program->imageUrl() }}" alt="{{ $program->title }}" class="program-image" onerror="this.src='https://placehold.co/600x400/1A237E/white?text=GPC'">
                <div class="program-content">
                    <h3>{{ $program->title }}</h3>
                    <p>{{ $program->description }}</p>
                </div>
            </div>
        @endforeach
    </div>
</section>
@endsection
