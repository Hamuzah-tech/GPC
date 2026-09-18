document.addEventListener('DOMContentLoaded', function () {
    var slidesHero = document.querySelectorAll('.hero-slide');
    var heroCurrent = 0;
    if (slidesHero.length > 1) {
        setInterval(function () {
            slidesHero[heroCurrent].classList.remove('active');
            heroCurrent = (heroCurrent + 1) % slidesHero.length;
            slidesHero[heroCurrent].classList.add('active');
        }, 5000);
    }

    var counters = document.querySelectorAll('.counter');
    var triggered = false;
    function startCounters() {
        if (triggered) return;
        var section = document.querySelector('.impact-counters');
        if (section && section.getBoundingClientRect().top < window.innerHeight - 100) {
            triggered = true;
            counters.forEach(function (c) {
                var target = +c.getAttribute('data-target');
                var count = 0;
                var update = function () {
                    count += Math.ceil(target / 70);
                    if (count < target) {
                        c.innerText = count;
                        requestAnimationFrame(update);
                    } else {
                        c.innerText = target.toLocaleString();
                    }
                };
                update();
            });
        }
    }
    window.addEventListener('scroll', startCounters);
    startCounters();

    var items = Array.prototype.slice.call(document.querySelectorAll('#newsFlashList .news-flash-item'));
    var featureImage = document.getElementById('newsFlashImage');
    var featureDate = document.getElementById('newsFlashDate');
    var featureTitle = document.getElementById('newsFlashTitle');
    var featureExcerpt = document.getElementById('newsFlashExcerpt');
    var currentFlash = 0;
    var flashInterval;
    var currentPayload = items[0] ? parseNewsPayload(items[0]) : null;

    function parseNewsPayload(item) {
        try {
            return JSON.parse(item.getAttribute('data-news') || '{}');
        } catch (e) {
            return {};
        }
    }

    function showFlash(index) {
        if (!items.length) return;
        var item = items[index];
        var payload = parseNewsPayload(item);
        items[currentFlash].classList.remove('is-active');
        currentFlash = index;
        item.classList.add('is-active');
        currentPayload = payload;
        if (featureImage) {
            featureImage.src = payload.image || '';
            featureImage.alt = payload.title || '';
        }
        if (featureDate) featureDate.textContent = payload.date || '';
        if (featureTitle) featureTitle.textContent = payload.title || '';
        if (featureExcerpt) {
            featureExcerpt.textContent = payload.excerpt || '';
            featureExcerpt.hidden = !payload.excerpt || payload.excerpt === payload.title;
        }
    }

    function resetFlashInterval() {
        if (flashInterval) clearInterval(flashInterval);
        if (items.length < 2) return;
        flashInterval = setInterval(function () {
            showFlash((currentFlash + 1) % items.length);
        }, 7000);
    }

    items.forEach(function (item, index) {
        item.addEventListener('click', function () {
            showFlash(index);
            resetFlashInterval();
        });
    });

    var newsFlash = document.getElementById('newsFlash');
    if (newsFlash && items.length > 1) {
        newsFlash.addEventListener('mouseenter', function () {
            if (flashInterval) clearInterval(flashInterval);
        });
        newsFlash.addEventListener('mouseleave', function () {
            if (!modal || !modal.classList.contains('active')) resetFlashInterval();
        });
        resetFlashInterval();
    }

    var modal = document.getElementById('newsModal');
    var closeModalBtn = document.getElementById('closeModalBtn');
    var modalTitle = document.getElementById('modalTitle');
    var modalDateSpan = document.querySelector('#modalDate span');
    var modalStory = document.getElementById('modalStory');
    var modalImagesSection = document.getElementById('modalImagesSection');
    var modalGallery = document.getElementById('modalGallery');

    function openNewsModal() {
        if (!modal || !currentPayload) return;
        modal.removeAttribute('hidden');
        modalTitle.innerText = currentPayload.title || '';
        if (modalDateSpan) modalDateSpan.innerText = currentPayload.date || '';
        modalStory.innerHTML = currentPayload.content || '';
        if (currentPayload.images && currentPayload.images.length) {
            modalGallery.innerHTML = currentPayload.images.map(function (img) {
                return '<img src="' + img.url + '" class="gallery-image" alt="News image">';
            }).join('');
            modalImagesSection.hidden = false;
        } else {
            modalImagesSection.hidden = true;
            modalGallery.innerHTML = '';
        }
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
        if (flashInterval) clearInterval(flashInterval);
    }

    function closeNewsModal() {
        if (!modal) return;
        modal.classList.remove('active');
        document.body.style.overflow = '';
        resetFlashInterval();
    }

    ['newsFlashRead', 'newsFlashOpen'].forEach(function (id) {
        var el = document.getElementById(id);
        if (el) el.addEventListener('click', openNewsModal);
    });
    if (closeModalBtn) closeModalBtn.addEventListener('click', closeNewsModal);
    if (modal) {
        modal.addEventListener('click', function (e) {
            if (e.target === modal) closeNewsModal();
        });
    }
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closeNewsModal();
    });
});
