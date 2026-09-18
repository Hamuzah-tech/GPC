@php
    $address = $settings['address'] ?? 'Lilongwe District, Malawi';
    $phone = $settings['phone'] ?? '+265 881 234 567';
    $email = $settings['email'] ?? 'info@girlpowerforchange.org';
    $aboutFooter = $settings['about_footer'] ?? 'Empowering Adolescent Girls and Young Women in Malawi to become agents of change for economic and social development.';
    $facebook = $settings['facebook_url'] ?? 'https://facebook.com/gpcmalawi';
    $instagram = $settings['instagram_url'] ?? 'https://instagram.com/gpcmalawi';
@endphp
<footer>
    <div class="footer-grid">
        <div class="footer-col">
            <h4>About GPC</h4>
            <p>{{ $aboutFooter }}</p>
            <div class="social-icons">
                <a href="{{ $facebook }}" target="_blank" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                <a href="{{ $instagram }}" target="_blank" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
            </div>
        </div>
        <div class="footer-col">
            <h4>Contact Us</h4>
            <p><i class="fas fa-map-marker-alt"></i> {{ $address }}</p>
            <p><i class="fas fa-phone-alt"></i> <a href="tel:{{ preg_replace('/\s+/', '', $phone) }}">{{ $phone }}</a></p>
            <p><i class="fas fa-envelope"></i> <a href="mailto:{{ $email }}">{{ $email }}</a></p>
        </div>
    </div>
    <div class="footer-bottom">
        &copy; <span id="currentYear">{{ date('Y') }}</span> GPC - Empowering AGYW. All Rights Reserved.
    </div>
</footer>
