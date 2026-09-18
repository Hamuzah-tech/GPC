@php
    $tabs = [
        ['label' => 'Overview', 'route' => 'admin.dashboard', 'match' => 'admin.dashboard'],
        ['label' => 'Leadership', 'route' => 'admin.leaders.index', 'match' => 'admin.leaders.*'],
        ['label' => 'Programs', 'route' => 'admin.programs.index', 'match' => 'admin.programs.*'],
        ['label' => 'Pillars', 'route' => 'admin.pillars.index', 'match' => 'admin.pillars.*'],
        ['label' => 'Thematic Areas', 'route' => 'admin.thematic-areas.index', 'match' => 'admin.thematic-areas.*'],
        ['label' => 'Strategy', 'route' => 'admin.strategic-approaches.index', 'match' => 'admin.strategic-approaches.*'],
        ['label' => 'FAQs', 'route' => 'admin.faqs.index', 'match' => 'admin.faqs.*'],
        ['label' => 'Pages', 'route' => 'admin.pages.index', 'match' => 'admin.pages.*'],
        ['label' => 'Gallery', 'route' => 'admin.gallery.index', 'match' => 'admin.gallery.*'],
    ];
@endphp

<nav class="content-tabs" aria-label="Content sections">
    @foreach($tabs as $tab)
        <a class="content-tab {{ request()->routeIs($tab['match']) ? 'active' : '' }}" href="{{ route($tab['route']) }}">
            {{ $tab['label'] }}
        </a>
    @endforeach
</nav>
