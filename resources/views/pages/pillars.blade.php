@extends('layouts.app')

@section('title', 'GPC - Our Key Pillars | Empowering AGYW in Malawi')
@section('css', asset('css/pages/pillars.css'))

@section('content')
<section class="pillars-hero">
    <div class="hero-overlay"></div>
    <div class="hero-content">
        <h1>Our Key Pillars</h1>
        <p>The foundational principles that guide our work with AGYW in Malawi</p>
    </div>
</section>

<section class="pillars-section">
    <div class="section-header">
        <h2>What We Stand For</h2>
        <p>Four core pillars that shape our programs, partnerships, and advocacy efforts</p>
    </div>
    <div class="pillars-grid">
        @foreach($pillars as $pillar)
            <div class="pillar-card">
                <h3>{{ $pillar->title }}</h3>
                <p>{{ $pillar->description }}</p>
            </div>
        @endforeach
    </div>
</section>
@endsection
