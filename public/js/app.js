document.addEventListener('DOMContentLoaded', function () {
    var yearEl = document.getElementById('currentYear');
    if (yearEl) {
        yearEl.innerText = new Date().getFullYear();
    }

    window.addEventListener('load', function () {
        setTimeout(function () {
            var loader = document.getElementById('loaderWrapper');
            if (!loader) return;
            loader.classList.add('hide');
            setTimeout(function () {
                loader.style.display = 'none';
            }, 500);
        }, 1000);
    });

    var hamburger = document.getElementById('hamburgerBtn');
    var navLinks = document.getElementById('navLinks');
    var overlayMenu = document.getElementById('menuOverlay');

    function toggleMenu() {
        if (!navLinks || !overlayMenu || !hamburger) return;
        navLinks.classList.toggle('active');
        overlayMenu.classList.toggle('active');
        hamburger.classList.toggle('active');
        document.body.style.overflow = navLinks.classList.contains('active') ? 'hidden' : '';
    }

    if (hamburger) hamburger.addEventListener('click', toggleMenu);
    if (overlayMenu) overlayMenu.addEventListener('click', toggleMenu);

    document.querySelectorAll('.nav-links a').forEach(function (link) {
        link.addEventListener('click', function (e) {
            if (window.innerWidth <= 768 && link.closest('.has-dropdown') && !link.closest('.dropdown-menu')) {
                e.preventDefault();
                link.closest('.has-dropdown').classList.toggle('active-dropdown');
                return;
            }
            if (navLinks && navLinks.classList.contains('active')) toggleMenu();
        });
    });

    var scrollBtn = document.getElementById('scrollUpBtn');
    if (scrollBtn) {
        window.addEventListener('scroll', function () {
            scrollBtn.classList.toggle('show', window.scrollY > 300);
        });
        scrollBtn.addEventListener('click', function () {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }

    var reveals = document.querySelectorAll('.reveal-zoom-in');
    if (reveals.length && 'IntersectionObserver' in window) {
        var observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) entry.target.classList.add('revealed');
            });
        }, { threshold: 0.1 });
        reveals.forEach(function (el) { observer.observe(el); });
    }

    document.body.style.overflowX = 'hidden';
});
