document.addEventListener('DOMContentLoaded', function () {
    var modal = document.getElementById('newsModal');
    var closeModalBtn = document.getElementById('closeModalBtn');
    var modalTitle = document.getElementById('modalTitle');
    var modalDateSpan = document.querySelector('#modalDate span');
    var modalStory = document.getElementById('modalStory');
    var modalImagesSection = document.getElementById('modalImagesSection');
    var modalGallery = document.getElementById('modalGallery');

    function openModal(card) {
        var payload = JSON.parse(card.dataset.news);
        modalTitle.innerText = payload.title;
        modalDateSpan.innerText = payload.date;
        modalStory.innerHTML = payload.content;
        if (payload.images && payload.images.length) {
            modalGallery.innerHTML = payload.images.map(function (img) {
                return '<img src="' + img.url + '" class="gallery-image' + (img.isCover ? ' cover-highlight' : '') + '" alt="News image">';
            }).join('');
            modalImagesSection.style.display = 'block';
        } else {
            modalImagesSection.style.display = 'none';
        }
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        modal.classList.remove('active');
        document.body.style.overflow = '';
    }

    document.querySelectorAll('.news-card').forEach(function (card) {
        card.addEventListener('click', function () { openModal(card); });
    });

    if (closeModalBtn) closeModalBtn.addEventListener('click', closeModal);
    if (modal) {
        modal.addEventListener('click', function (e) {
            if (e.target === modal) closeModal();
        });
    }
});
