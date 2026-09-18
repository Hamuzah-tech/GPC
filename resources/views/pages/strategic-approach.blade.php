@extends('layouts.app')

@section('title', 'GPC - Strategic Approach | Empowering AGYW in Malawi')
@section('css', asset('css/pages/strategic-approach.css'))

@section('content')
<section class="strategic-hero">
    <div class="hero-overlay"></div>
    <div class="hero-content">
        <h1>Our Strategic Approach</h1>
        <p>How we create lasting impact for adolescent girls and young women in Malawi</p>
    </div>
</section>

<section class="strategic-section">
    <div class="section-header">
        <h2>How We Work</h2>
        <p>Three interconnected strategies that guide our programs, partnerships, and advocacy efforts</p>
    </div>
    <div class="strategic-grid">
        @foreach($approaches as $approach)
            <div class="strategic-card">
                <h3>{{ $approach->title }}</h3>
                <p>{{ $approach->description }}</p>
            </div>
        @endforeach
    </div>
</section>
@endsection
