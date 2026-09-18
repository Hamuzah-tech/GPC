@extends('layouts.app')

@section('title', 'GPC - Contact Us | Empowering AGYW in Malawi')
@section('css', asset('css/pages/contact.css'))

@section('content')
<div class="modal-overlay" id="centerModal">
    <div class="center-modal">
        <div class="modal-icon" id="modalIcon">
            <div class="spinner"></div>
        </div>
        <h3 class="modal-title" id="modalTitle">Sending Message</h3>
        <p class="modal-message" id="modalMessage">Please wait while we process your request...</p>
        <button class="modal-btn" id="modalBtn" style="display: none;">Okay</button>
    </div>
</div>

<section class="contact-hero">
    <div class="hero-overlay"></div>
    <div class="hero-content">
        <h1>Contact Us</h1>
        <p>Get in touch with our team — we're here to help</p>
    </div>
</section>

<div class="contact-section">
    <div class="contact-details">
        <div class="detail-item">
            <div class="detail-icon"><i class="fas fa-map-marker-alt"></i></div>
            <h3>Location</h3>
            <p>{{ $settings['address'] ?? 'Lilongwe District, Malawi' }}</p>
            <p>Operations also in Zomba & Dowa Districts</p>
        </div>
        <div class="detail-item">
            <div class="detail-icon"><i class="fas fa-phone-alt"></i></div>
            <h3>Phone</h3>
            <p><a href="tel:{{ preg_replace('/\s+/', '', $settings['phone'] ?? '+265 881 234 567') }}">{{ $settings['phone'] ?? '+265 881 234 567' }}</a></p>
            <p>{{ $settings['office_hours'] ?? 'Mon–Fri, 8:00 AM – 5:00 PM' }}</p>
        </div>
        <div class="detail-item">
            <div class="detail-icon"><i class="fas fa-envelope"></i></div>
            <h3>Email</h3>
            <p><a href="mailto:{{ $settings['email'] ?? 'info@girlpowerforchange.org' }}">{{ $settings['email'] ?? 'info@girlpowerforchange.org' }}</a></p>
            <div class="response-note"><i class="fas fa-clock"></i> We reply within 24–48 hours</div>
        </div>
    </div>

    <div class="form-location-grid">
        <div class="contact-form">
            <h3>Send Us a Message</h3>
            <p style="margin-bottom: 20px; color: var(--text-muted);">We'd love to hear from you</p>
            <form id="contactForm" action="{{ route('contact.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label>Full Name <span class="required">*</span></label>
                    <input type="text" id="fullName" name="name" required>
                </div>
                <div class="form-group">
                    <label>Email Address <span class="required">*</span></label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label>Phone Number</label>
                    <input type="tel" id="phone" name="phone">
                </div>
                <div class="form-group">
                    <label>Message <span class="required">*</span></label>
                    <textarea id="message" name="message" required></textarea>
                </div>
                <button type="submit" class="submit-btn" id="submitBtn">Send Message</button>
            </form>
        </div>

        <div class="location-info">
            <h3>Our Presence in Malawi</h3>
            <div class="location-detail">
                <h4>Headquarters</h4>
                <p>GPC main office is located in Lilongwe District, the capital city of Malawi. This is where our central coordination, administration, and strategic planning take place.</p>
            </div>
            <div class="location-detail">
                <h4>Operational Districts</h4>
                <p>In addition to Lilongwe, we actively implement programs in <strong>Zomba</strong> and <strong>Dowa</strong> Districts, reaching adolescent girls and young women in both urban and rural communities.</p>
            </div>
            <div class="location-detail">
                <h4>Community Reach</h4>
                <p>Through our sectoral programs, we partner with local leaders, schools, and health facilities to ensure AGYW across all three districts have access to empowerment opportunities.</p>
            </div>
        </div>
    </div>

    <div class="faq-section">
        <h3>Frequently Asked Questions</h3>
        <div class="accordion" id="faqAccordion">
            @foreach($faqs as $faq)
                <div class="accordion-item">
                    <div class="accordion-header">
                        {{ $faq->question }}
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="accordion-content">
                        <p>{{ $faq->answer }}</p>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="faq-footer">
            <p><i class="fas fa-question-circle"></i> Didn't find the answer? <a href="#contactForm">Ask your question</a> directly through our contact form.</p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/forms.js') }}"></script>
@endpush
