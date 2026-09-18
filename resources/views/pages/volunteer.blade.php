@extends('layouts.app')

@section('title', 'GPC - Volunteer Registration | Empowering AGYW in Malawi')
@section('css', asset('css/pages/volunteer.css'))

@section('content')
<div class="modal-overlay" id="centerModal">
    <div class="center-modal">
        <div class="modal-icon" id="modalIcon">
            <div class="spinner"></div>
        </div>
        <h3 class="modal-title" id="modalTitle">Submitting Registration</h3>
        <p class="modal-message" id="modalMessage">Please wait while we process your volunteer application...</p>
        <button class="modal-btn" id="modalBtn" style="display: none;">Okay</button>
    </div>
</div>

<section class="volunteer-hero">
    <div class="hero-overlay"></div>
    <div class="hero-content">
        <h1>Volunteer Registration</h1>
        <p>Join our mission to empower adolescent girls and young women in Malawi</p>
    </div>
</section>

<section class="form-section">
    <div class="section-header">
        <h2>Become a Volunteer</h2>
        <p>Your time and skills can transform lives. Fill out the form below to get started.</p>
    </div>

    <div class="volunteer-form">
        <form id="volunteerForm" action="{{ route('volunteer.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-grid">
                <div class="form-group">
                    <label>Full Name <span class="required">*</span></label>
                    <input type="text" id="fullName" name="full_name" required placeholder="Enter your full name">
                </div>
                <div class="form-group">
                    <label>Email Address <span class="required">*</span></label>
                    <input type="email" id="email" name="email" required placeholder="you@example.com">
                </div>
                <div class="form-group">
                    <label>Phone Number <span class="required">*</span></label>
                    <input type="tel" id="phone" name="phone" required placeholder="+265 XXX XXX XXX">
                </div>
                <div class="form-group">
                    <label>Age</label>
                    <input type="number" id="age" name="age" placeholder="Your age" min="18" max="100">
                </div>
                <div class="form-group">
                    <label>Gender</label>
                    <select id="gender" name="gender">
                        <option value="">Select gender</option>
                        <option value="Female">Female</option>
                        <option value="Male">Male</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Location <span class="required">*</span></label>
                    <select id="location" name="location" required>
                        <option value="">Select district</option>
                        <option value="Lilongwe">Lilongwe</option>
                        <option value="Zomba">Zomba</option>
                        <option value="Dowa">Dowa</option>
                        <option value="Other">Other (specify in message)</option>
                    </select>
                </div>
                <div class="form-group full-width">
                    <label>Skills / Expertise</label>
                    <input type="text" id="skills" name="skills" placeholder="e.g., Teaching, Healthcare, IT, Fundraising, Administration">
                </div>
                <div class="form-group full-width">
                    <label>Volunteer Interests <span class="required">*</span></label>
                    <div class="checkbox-group" id="interestsGroup">
                        <label><input type="checkbox" value="Mentoring"> Mentoring</label>
                        <label><input type="checkbox" value="Education"> Education</label>
                        <label><input type="checkbox" value="Health"> Health</label>
                        <label><input type="checkbox" value="Economic Empowerment"> Economic Empowerment</label>
                        <label><input type="checkbox" value="Climate Action"> Climate Action</label>
                        <label><input type="checkbox" value="Leadership Training"> Leadership Training</label>
                        <label><input type="checkbox" value="Fundraising"> Fundraising</label>
                        <label><input type="checkbox" value="Administration"> Administration</label>
                    </div>
                </div>
                <div class="form-group full-width">
                    <label>Availability</label>
                    <select id="availability" name="availability">
                        <option value="">Select availability</option>
                        <option value="Weekdays (Morning)">Weekdays (Morning)</option>
                        <option value="Weekdays (Afternoon)">Weekdays (Afternoon)</option>
                        <option value="Weekends">Weekends</option>
                        <option value="Flexible">Flexible</option>
                        <option value="Evenings">Evenings</option>
                    </select>
                </div>
                <div class="form-group full-width">
                    <label>CV / Resume <span class="required">*</span></label>
                    <input type="file" id="cv" name="cv" accept="application/pdf,.pdf" required>
                    <p class="file-hint">PDF only, maximum 5MB. Files are stored privately and are only visible to GPC staff.</p>
                </div>
                <div class="form-group full-width">
                    <label>Why do you want to volunteer? <span class="required">*</span></label>
                    <textarea id="message" name="message" required placeholder="Tell us about your motivation and how you'd like to contribute..."></textarea>
                </div>
            </div>
            <button type="submit" class="submit-btn" id="submitBtn">Submit Registration</button>
        </form>
    </div>
</section>
@endsection

@push('scripts')
<script src="{{ asset('js/forms.js') }}"></script>
@endpush
