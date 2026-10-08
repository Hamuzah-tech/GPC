<header>
    <nav class="navbar">
        <a href="{{ route('home') }}" class="site-logo">
            <img src="{{ asset('images/logo.png') }}" alt="GPC Malawi" class="custom-logo" onerror="this.src='https://placehold.co/200x70/1A237E/white?text=GPC+Malawi'">
        </a>
        <button class="hamburger" id="hamburgerBtn" aria-label="Menu">
            <span></span>
            <span></span>
            <span></span>
        </button>
        <ul class="nav-links" id="navLinks">
            <li><a href="{{ route('home') }}">Home</a></li>
            <li class="has-dropdown">
                <a href="#">About Us</a>
                <ul class="dropdown-menu">
                    <li><a href="{{ route('leadership') }}">Leadership</a></li>
                    <li><a href="{{ route('volunteer') }}">Volunteer</a></li>
                    <li><a href="{{ route('mission') }}">Mission</a></li>
                    <li><a href="{{ route('vision') }}">Vision</a></li>
                    <li><a href="{{ route('pillars') }}">Pillars</a></li>
                    <li><a href="{{ route('thematic-areas') }}">Thematic Areas</a></li>
                    <li><a href="{{ route('strategic-approach') }}">Strategic Approach</a></li>
                </ul>
            </li>
            <li><a href="{{ route('programs') }}">Programs</a></li>
            <li><a href="{{ route('events.register') }}">Register</a></li>
            <li><a href="{{ route('contact') }}">Contact</a></li>
            <li><a href="{{ route('news') }}">News</a></li>
        </ul>
    </nav>
    <div class="menu-overlay" id="menuOverlay"></div>
</header>
