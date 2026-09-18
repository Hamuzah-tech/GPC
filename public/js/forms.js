document.addEventListener('DOMContentLoaded', function () {
    var csrf = document.querySelector('meta[name="csrf-token"]');
    var token = csrf ? csrf.getAttribute('content') : '';

    var modalOverlay = document.getElementById('centerModal');
    var modalIcon = document.getElementById('modalIcon');
    var modalTitle = document.getElementById('modalTitle');
    var modalMessage = document.getElementById('modalMessage');
    var modalBtn = document.getElementById('modalBtn');

    function showLoading(title, message) {
        modalIcon.innerHTML = '<div class="spinner"></div>';
        modalTitle.textContent = title;
        modalMessage.textContent = message;
        modalBtn.style.display = 'none';
        modalOverlay.classList.add('active');
    }

    function showSuccess(title, message) {
        modalIcon.innerHTML = '<i class="fas fa-check-circle" style="font-size: 3rem;"></i>';
        modalTitle.textContent = title;
        modalMessage.textContent = message;
        modalBtn.style.display = 'inline-block';
        modalBtn.textContent = 'Okay';
    }

    function showError(title, message) {
        modalIcon.innerHTML = '<i class="fas fa-exclamation-circle" style="font-size: 3rem;"></i>';
        modalTitle.textContent = title;
        modalMessage.textContent = message;
        modalBtn.style.display = 'inline-block';
        modalBtn.textContent = 'Try Again';
    }

    function closeModal() {
        modalOverlay.classList.remove('active');
    }

    if (modalBtn) modalBtn.addEventListener('click', closeModal);
    if (modalOverlay) {
        modalOverlay.addEventListener('click', function (e) {
            if (e.target === modalOverlay) closeModal();
        });
    }

    document.querySelectorAll('.accordion-item .accordion-header').forEach(function (header) {
        header.addEventListener('click', function () {
            header.closest('.accordion-item').classList.toggle('active');
        });
    });

    var contactForm = document.getElementById('contactForm');
    if (contactForm) {
        contactForm.addEventListener('submit', async function (e) {
            e.preventDefault();
            var submitBtn = document.getElementById('submitBtn');
            var name = document.getElementById('fullName').value.trim();
            var email = document.getElementById('email').value.trim();
            var phone = document.getElementById('phone').value.trim();
            var message = document.getElementById('message').value.trim();

            if (!name || !email || !message) {
                showError('Connection Error', 'Please fill all required fields (Full Name, Email, and Message).');
                return;
            }

            showLoading('Sending Message', 'Please wait while we process your request...');
            submitBtn.disabled = true;
            submitBtn.textContent = 'Sending...';

            try {
                var response = await fetch(contactForm.action, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': token
                    },
                    body: JSON.stringify({ name: name, email: email, phone: phone, message: message })
                });
                var payload = await response.json();
                if (response.ok && payload.ok) {
                    showSuccess('Message Sent!', payload.message);
                    contactForm.reset();
                } else {
                    var firstError = payload.message || 'Unable to send message. Please try again later.';
                    if (payload.errors) {
                        firstError = Object.values(payload.errors)[0][0];
                    }
                    showError('Connection Error', firstError);
                }
            } catch (err) {
                showError('Connection Error', 'Unable to send message. Please check your connection or contact us directly.');
            } finally {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Send Message';
            }
        });
    }

    var volunteerForm = document.getElementById('volunteerForm');
    if (volunteerForm) {
        volunteerForm.addEventListener('submit', async function (e) {
            e.preventDefault();
            var submitBtn = document.getElementById('submitBtn');
            var fullName = document.getElementById('fullName').value.trim();
            var email = document.getElementById('email').value.trim();
            var phone = document.getElementById('phone').value.trim();
            var age = document.getElementById('age').value.trim();
            var gender = document.getElementById('gender').value;
            var location = document.getElementById('location').value;
            var skills = document.getElementById('skills').value.trim();
            var availability = document.getElementById('availability').value;
            var message = document.getElementById('message').value.trim();
            var cvInput = document.getElementById('cv');
            var cv = cvInput ? cvInput.files[0] : null;
            var interests = [];
            document.querySelectorAll('#interestsGroup input[type="checkbox"]:checked').forEach(function (cb) {
                interests.push(cb.value);
            });

            if (!fullName || !email || !phone || !location || !message) {
                showError('Submission Failed', 'Please fill in all required fields (*).');
                return;
            }

            if (!cv) {
                showError('Submission Failed', 'Please upload your CV as a PDF file.');
                return;
            }

            if (cv.type !== 'application/pdf' && !/\.pdf$/i.test(cv.name)) {
                showError('Submission Failed', 'The CV must be a PDF file.');
                return;
            }

            if (cv.size > 5 * 1024 * 1024) {
                showError('Submission Failed', 'The CV must be 5MB or smaller.');
                return;
            }

            showLoading('Submitting Registration', 'Please wait while we process your volunteer application...');
            submitBtn.disabled = true;
            submitBtn.textContent = 'Submitting...';

            try {
                var formData = new FormData();
                formData.append('_token', token);
                formData.append('full_name', fullName);
                formData.append('email', email);
                formData.append('phone', phone);
                formData.append('age', age);
                formData.append('gender', gender);
                formData.append('location', location);
                formData.append('skills', skills);
                formData.append('availability', availability);
                formData.append('message', message);
                formData.append('cv', cv);
                interests.forEach(function (item) {
                    formData.append('interests[]', item);
                });

                var response = await fetch(volunteerForm.action, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': token
                    },
                    body: formData
                });
                var payload = await response.json();
                if (response.ok && payload.ok) {
                    showSuccess('Registration Submitted!', payload.message);
                    volunteerForm.reset();
                } else {
                    var firstError = payload.message || 'Unable to submit registration. Please try again later.';
                    if (payload.errors) {
                        firstError = Object.values(payload.errors)[0][0];
                    }
                    showError('Submission Failed', firstError);
                }
            } catch (err) {
                showError('Submission Failed', 'Unable to submit registration. Please check your connection or contact us directly at info@girlpowerforchange.org');
            } finally {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Submit Registration';
            }
        });
    }
});
