@extends('layouts.app')

@section('title', 'GPC - Our Mission | Empowering AGYW in Malawi')
@section('css', asset('css/pages/mission.css'))

@section('content')
<section class="mission-hero">
    <div class="hero-overlay"></div>
    <div class="hero-content">
        <h1>{{ $page->title ?? 'Our Mission' }}</h1>
        <p>{{ $page->subtitle ?? 'Driving meaningful change for adolescent girls and young women' }}</p>
    </div>
</section>

<section class="mission-statement">
    <div class="mission-card">
        <div class="mission-text">
            {{ $page->body ?? 'Supporting Adolescent Girls and Young Women (AGYW) to become active citizens and individuals who can influence and challenge the status quo in addressing their diverse needs.' }}
        </div>
        <div class="supporting-text">
            {{ $page->extra ?? 'Through targeted programs and advocacy, we equip AGYW with the skills, knowledge, and confidence to shape their own futures and transform their communities.' }}
        </div>
    </div>
</section>
@endsection
