(() => {
    'use strict';

    const dialog = document.querySelector('[data-confirm-dialog]');
    if (dialog && typeof dialog.showModal === 'function') {
        let pendingForm = null;

        document.querySelectorAll('[data-confirm-action]').forEach((form) => {
            form.addEventListener('submit', (event) => {
                event.preventDefault();
                pendingForm = form;

                const title = form.dataset.confirmTitle || 'Konfirmasi';
                const message = form.dataset.confirmMessage || 'Apakah Anda yakin ingin melanjutkan?';
                const submitText = form.dataset.confirmSubmit || 'Lanjutkan';

                const titleEl = dialog.querySelector('[data-dialog-title]');
                const messageEl = dialog.querySelector('[data-dialog-message]');
                const submitBtn = dialog.querySelector('[data-dialog-submit]');

                if (titleEl) titleEl.textContent = title;
                if (messageEl) messageEl.textContent = message;
                if (submitBtn) submitBtn.textContent = submitText;

                dialog.showModal();
            });
        });

        const cancelBtn = dialog.querySelector('[data-dialog-cancel]');
        if (cancelBtn) {
            cancelBtn.addEventListener('click', () => dialog.close());
        }

        const confirmBtn = dialog.querySelector('[data-dialog-submit]');
        if (confirmBtn) {
            confirmBtn.addEventListener('click', () => {
                dialog.close();
                if (pendingForm) {
                    HTMLFormElement.prototype.submit.call(pendingForm);
                }
            });
        }
    }
})();

