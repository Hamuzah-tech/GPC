(() => {
    const toastStack = document.getElementById('toast-stack');
    const modal = document.getElementById('confirm-modal');
    const modalTitle = document.getElementById('confirm-title');
    const cancelBtn = document.getElementById('confirm-cancel');
    const okBtn = document.getElementById('confirm-ok');
    const sidebar = document.getElementById('sidebar');
    const backdrop = document.getElementById('sidebar-backdrop');
    const menuBtn = document.getElementById('mobileMenuBtn');
    let pendingForm = null;

    const setNavOpen = (open) => {
        sidebar?.classList.toggle('open', open);
        backdrop?.classList.toggle('open', open);
        document.body.classList.toggle('admin-nav-open', open);
        menuBtn?.setAttribute('aria-expanded', open ? 'true' : 'false');
    };

    window.showAdminToast = function (message, type = 'success') {
        if (!toastStack || !message) {
            return;
        }

        const toast = document.createElement('div');
        toast.className = `admin-toast admin-toast-${type}`;
        toast.innerHTML = `
            <span class="admin-toast-icon">${type === 'error' ? '!' : '✓'}</span>
            <span class="admin-toast-message"></span>
        `;
        toast.querySelector('.admin-toast-message').textContent = message;
        toastStack.appendChild(toast);

        window.setTimeout(() => {
            toast.classList.add('hiding');
            window.setTimeout(() => toast.remove(), 250);
        }, 4200);
    };

    menuBtn?.addEventListener('click', (event) => {
        event.preventDefault();
        event.stopPropagation();
        setNavOpen(!sidebar?.classList.contains('open'));
    });

    backdrop?.addEventListener('click', () => setNavOpen(false));

    sidebar?.querySelectorAll('a.menu-item').forEach((link) => {
        link.addEventListener('click', () => setNavOpen(false));
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            setNavOpen(false);
            closeModal();
        }
    });

    window.addEventListener('resize', () => {
        if (window.innerWidth > 968) {
            setNavOpen(false);
        }
    });

    document.addEventListener('click', (event) => {
        const trigger = event.target.closest('[data-confirm]');
        if (!trigger) {
            return;
        }

        event.preventDefault();
        pendingForm = trigger.closest('form');
        if (!pendingForm || !modal) {
            return;
        }

        modalTitle.textContent = trigger.getAttribute('data-confirm') || 'Are you sure?';
        modal.classList.add('active');
    });

    const closeModal = () => {
        modal?.classList.remove('active');
        pendingForm = null;
    };

    cancelBtn?.addEventListener('click', closeModal);
    modal?.addEventListener('click', (event) => {
        if (event.target === modal) {
            closeModal();
        }
    });

    okBtn?.addEventListener('click', () => {
        if (pendingForm) {
            pendingForm.submit();
        }
        closeModal();
    });
})();
