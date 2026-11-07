(() => {
    'use strict';

    // Competency search filtering
    document.querySelectorAll('[data-competency-search]').forEach((input) => {
        const picker = input.closest('.competency-picker');
        const list = picker.querySelector('.competency-list');
        const emptyMsg = picker.querySelector('.competency-picker-empty');
        const options = [...list.querySelectorAll('.competency-option')];

        input.addEventListener('input', () => {
            const query = input.value.trim().toLowerCase();
            let matches = 0;
            options.forEach((opt) => {
                const text = opt.textContent.toLowerCase();
                const matched = text.includes(query);
                opt.hidden = !matched;
                if (matched) matches++;
            });
            if (emptyMsg) emptyMsg.hidden = matches > 0;
        });
    });

    // Create Modal
    const createDialog = document.querySelector('[data-create-dialog]');
    const openCreateBtn = document.querySelectorAll('[data-open-create-modal]');
    if (createDialog && typeof createDialog.showModal === 'function') {
        openCreateBtn.forEach((btn) => btn.addEventListener('click', () => {
            const form = createDialog.querySelector('form');
            if (form) form.reset();
            // Reset filter
            const searchInput = createDialog.querySelector('[data-competency-search]');
            if (searchInput) {
                searchInput.value = '';
                searchInput.dispatchEvent(new Event('input'));
            }
            createDialog.showModal();
        }));
    }

    // Edit Modal
    const editDialog = document.querySelector('[data-edit-dialog]');
    if (editDialog && typeof editDialog.showModal === 'function') {
        const editForm = editDialog.querySelector('form');
        const titleInput = editDialog.querySelector('#edit-title');
        const descInput = editDialog.querySelector('#edit-description');
        const baseAction = editForm.dataset.baseAction; // e.g. /admin/professions/{profession}/career-paths

        document.querySelectorAll('[data-edit-stage]').forEach((btn) => {
            btn.addEventListener('click', () => {
                const stageId = btn.dataset.stageId;
                const stageTitle = btn.dataset.stageTitle;
                const stageDesc = btn.dataset.stageDesc;
                const stageCompIds = JSON.parse(btn.dataset.stageCompetencies || '[]');

                editForm.action = `${baseAction}/${stageId}`;
                if (titleInput) titleInput.value = stageTitle;
                if (descInput) descInput.value = stageDesc;

                // Set competency checkboxes
                editDialog.querySelectorAll('input[type="checkbox"][name="competency_ids[]"]').forEach((cb) => {
                    cb.checked = stageCompIds.includes(Number(cb.value));
                });

                // Reset filter
                const searchInput = editDialog.querySelector('[data-competency-search]');
                if (searchInput) {
                    searchInput.value = '';
                    searchInput.dispatchEvent(new Event('input'));
                }

                editDialog.showModal();
            });
        });
    }

    // Delete Modal
    const deleteDialog = document.querySelector('[data-delete-dialog]');
    if (deleteDialog && typeof deleteDialog.showModal === 'function') {
        const deleteForm = deleteDialog.querySelector('form');
        const deleteCopy = deleteDialog.querySelector('[data-delete-copy]');
        const baseAction = deleteForm.dataset.baseAction;

        document.querySelectorAll('[data-delete-stage]').forEach((btn) => {
            btn.addEventListener('click', () => {
                const stageId = btn.dataset.stageId;
                const stageTitle = btn.dataset.stageTitle;

                deleteForm.action = `${baseAction}/${stageId}`;
                if (deleteCopy) deleteCopy.textContent = `Hapus tahapan "${stageTitle}"?`;

                deleteDialog.showModal();
            });
        });
    }

    // Close Dialog handler
    document.querySelectorAll('[data-close-dialog]').forEach((btn) => {
        btn.addEventListener('click', () => {
            const dialog = btn.closest('dialog');
            if (dialog) dialog.close();
        });
    });

    // Close on backdrop click
    document.querySelectorAll('dialog').forEach((dialog) => {
        dialog.addEventListener('click', (event) => {
            if (event.target === dialog) {
                dialog.close();
            }
        });
    });

    // Reorder Handlers (Up / Down buttons)
    const reorderForm = document.querySelector('[data-reorder-form]');
    if (reorderForm) {
        const stageCards = [...document.querySelectorAll('[data-stage-card]')];
        const stageIds = stageCards.map((card) => Number(card.dataset.stageId));

        document.querySelectorAll('[data-order-action]').forEach((btn) => {
            btn.addEventListener('click', () => {
                const stageId = Number(btn.dataset.stageId);
                const action = btn.dataset.orderAction; // 'up' or 'down'
                const currentIndex = stageIds.indexOf(stageId);

                if (currentIndex === -1) return;

                if (action === 'up' && currentIndex > 0) {
                    const temp = stageIds[currentIndex - 1];
                    stageIds[currentIndex - 1] = stageIds[currentIndex];
                    stageIds[currentIndex] = temp;
                } else if (action === 'down' && currentIndex < stageIds.length - 1) {
                    const temp = stageIds[currentIndex + 1];
                    stageIds[currentIndex + 1] = stageIds[currentIndex];
                    stageIds[currentIndex] = temp;
                } else {
                    return;
                }

                // Populate form inputs and submit
                reorderForm.querySelectorAll('input[name="stage_ids[]"]').forEach((input) => input.remove());
                stageIds.forEach((id) => {
                    const hidden = document.createElement('input');
                    hidden.type = 'hidden';
                    hidden.name = 'stage_ids[]';
                    hidden.value = id;
                    reorderForm.appendChild(hidden);
                });

                reorderForm.submit();
            });
        });
    }
})();
