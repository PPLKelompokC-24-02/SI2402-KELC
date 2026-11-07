(() => {
    'use strict';

    const getCsrfToken = () => {
        const meta = document.querySelector('meta[name="csrf-token"]');
        return meta ? meta.getAttribute('content') : '';
    };

    const getToastContainer = () => {
        let container = document.getElementById('toast-container');
        if (!container) {
            container = document.createElement('div');
            container.id = 'toast-container';
            container.className = 'toast-container';
            container.setAttribute('aria-live', 'polite');
            document.body.appendChild(container);
        }
        return container;
    };

    const heartOutlineSvg = `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" class="heart-icon"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z" /></svg>`;
    const heartFilledSvg = `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" class="heart-icon"><path fill="currentColor" stroke="currentColor" d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z" /></svg>`;
    const checkSvg = `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m5 12 4 4L19 6" /></svg>`;
    const closeSvg = `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m6 6 12 12M6 18 18 6" /></svg>`;

    const showToast = (message, type = 'success') => {
        const container = getToastContainer();
        const toast = document.createElement('div');
        toast.className = `toast-message toast-message--${type}`;
        toast.setAttribute('role', type === 'error' ? 'alert' : 'status');

        const iconWrap = document.createElement('span');
        iconWrap.className = 'toast-icon';
        if (type === 'success') {
            iconWrap.innerHTML = checkSvg;
        } else if (type === 'error') {
            iconWrap.innerHTML = closeSvg;
        } else {
            iconWrap.innerHTML = heartFilledSvg;
        }

        const textWrap = document.createElement('span');
        textWrap.className = 'toast-text';
        textWrap.textContent = message;

        const closeBtn = document.createElement('button');
        closeBtn.type = 'button';
        closeBtn.className = 'toast-close';
        closeBtn.setAttribute('aria-label', 'Tutup notifikasi');
        closeBtn.innerHTML = '&times;';
        closeBtn.addEventListener('click', () => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateY(-8px)';
            setTimeout(() => toast.remove(), 200);
        });

        toast.appendChild(iconWrap);
        toast.appendChild(textWrap);
        toast.appendChild(closeBtn);
        container.appendChild(toast);

        setTimeout(() => {
            if (toast.isConnected) {
                toast.style.opacity = '0';
                toast.style.transform = 'translateY(-8px)';
                setTimeout(() => toast.remove(), 200);
            }
        }, 3500);
    };

    // Auto dismiss any server-rendered toast messages after 3.5s
    document.querySelectorAll('.toast-message').forEach((toast) => {
        setTimeout(() => {
            if (toast.isConnected) {
                toast.style.opacity = '0';
                toast.style.transform = 'translateY(-8px)';
                setTimeout(() => toast.remove(), 200);
            }
        }, 3500);
    });

    const updateSavedBadge = (delta) => {
        const badges = document.querySelectorAll('[data-saved-count]');
        badges.forEach((badge) => {
            const current = parseInt(badge.textContent.trim(), 10) || 0;
            const updated = Math.max(0, current + delta);
            badge.textContent = String(updated);
            if (updated === 0) {
                badge.style.display = 'none';
            } else {
                badge.style.display = 'inline-flex';
            }
        });
    };

    const handleFavoriteSubmit = async (event) => {
        const form = event.target.closest('[data-favorite-form]');
        if (!form) return;

        event.preventDefault();

        const submitBtn = form.querySelector('button[type="submit"]');
        if (submitBtn && submitBtn.disabled) return;

        const professionId = form.dataset.professionId;
        const professionName = form.dataset.professionName || 'Profesi';
        const isDestroyForm = form.dataset.isDestroy === 'true';

        if (submitBtn) submitBtn.disabled = true;

        const action = form.action;
        const methodInput = form.querySelector('input[name="_method"]');
        const method = methodInput ? methodInput.value : form.method || 'POST';
        const csrfToken = form.querySelector('input[name="_token"]')?.value || getCsrfToken();

        try {
            const response = await fetch(action, {
                method: method.toUpperCase(),
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify({}),
            });

            const data = await response.json();

            if (response.ok && data.success !== undefined) {
                const isSaved = data.saved;
                const message = data.message || (isSaved ? 'Profesi berhasil ditambahkan ke Favorit Saya.' : 'Profesi berhasil dihapus dari Favorit Saya.');
                const toastType = data.success === false ? 'info' : (isSaved ? 'success' : 'info');

                showToast(message, toastType);

                // Update badge count
                updateSavedBadge(isSaved ? 1 : -1);

                // Update all buttons for this profession ID on page
                const allMatchingForms = document.querySelectorAll(`[data-favorite-form][data-profession-id="${professionId}"]`);
                allMatchingForms.forEach((matchingForm) => {
                    const btn = matchingForm.querySelector('button[type="submit"]');
                    if (!btn) return;

                    if (isSaved) {
                        btn.classList.add('is-active');
                        btn.title = 'Hapus dari Favorit';
                        btn.setAttribute('aria-label', `Hapus ${professionName} dari Favorit`);
                        const iconSpan = btn.querySelector('.heart-icon');
                        if (iconSpan) iconSpan.outerHTML = heartFilledSvg;
                    } else {
                        btn.classList.remove('is-active');
                        btn.title = 'Simpan ke Favorit';
                        btn.setAttribute('aria-label', `Simpan ${professionName} ke Favorit`);
                        const iconSpan = btn.querySelector('.heart-icon');
                        if (iconSpan) iconSpan.outerHTML = heartOutlineSvg;
                    }

                    const heroLabel = btn.querySelector('.favorite-hero-label');
                    if (heroLabel) {
                        heroLabel.textContent = isSaved ? 'Tersimpan di Favorit' : 'Simpan ke Favorit';
                    }
                });

                // If currently on "Favorit Saya" tab and item was un-favorited or destroyed
                const currentTab = new URLSearchParams(window.location.search).get('tab');
                if (currentTab === 'favorites' && (!isSaved || isDestroyForm)) {
                    const card = form.closest('[data-profession-card]');
                    if (card) {
                        card.style.transition = 'opacity 200ms ease, transform 200ms ease';
                        card.style.opacity = '0';
                        card.style.transform = 'scale(0.96)';
                        setTimeout(() => {
                            card.remove();
                            const list = document.querySelector('[data-profession-list]');
                            if (list && list.querySelectorAll('[data-profession-card]').length === 0) {
                                window.location.reload();
                            }
                        }, 220);
                    }
                }
            } else {
                const errorMsg = data.message || 'Gagal memperbarui status favorit. Silakan coba kembali.';
                showToast(errorMsg, 'error');
            }
        } catch (error) {
            console.error('Favorite toggle failed:', error);
            showToast('Gagal memproses favorit. Silakan coba kembali.', 'error');
        } finally {
            if (submitBtn) submitBtn.disabled = false;
        }
    };

    document.addEventListener('submit', (event) => {
        if (event.target.matches('[data-favorite-form]')) {
            handleFavoriteSubmit(event);
        }
    });
})();

