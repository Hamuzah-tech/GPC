document.addEventListener('DOMContentLoaded', function () {
    var modal = document.getElementById('leaderModal');
    var modalClose = document.getElementById('modalClose');
    var modalImage = document.getElementById('modalImage');
    var modalName = document.getElementById('modalName');
    var modalPosition = document.getElementById('modalPosition');
    var modalBio = document.getElementById('modalBio');

    function openModal(card) {
        modalImage.src = card.dataset.image;
        modalImage.alt = card.dataset.name;
        modalName.textContent = card.dataset.name;
        modalPosition.textContent = card.dataset.position;
        modalBio.textContent = card.dataset.bio;
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        modal.classList.remove('active');
        document.body.style.overflow = '';
    }

    document.querySelectorAll('.read-more-btn').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            e.stopPropagation();
            openModal(btn.closest('.leader-card'));
        });
    });

    if (modalClose) modalClose.addEventListener('click', closeModal);
    if (modal) {
        modal.addEventListener('click', function (e) {
            if (e.target === modal) closeModal();
        });
    }
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && modal && modal.classList.contains('active')) closeModal();
    });
});
