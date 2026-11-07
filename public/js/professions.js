(() => {
    'use strict';
    const form = document.querySelector('[data-profession-form]');
    if (form) {
        document.getElementById('validation-summary')?.focus();
        const saveState = form.querySelector('[data-save-state]');
        const status = form.querySelector('[data-editor-status]');
        let imageUrl;
        form.querySelector('#name').required = true;
        const dirty = () => { saveState.textContent = 'Perubahan belum disimpan.'; };
        const syncRows = (group) => {
            const rows = [...group.querySelector('[data-repeat-rows]').children];
            const field = group.dataset.repeatGroup;
            rows.forEach((row, index) => {
                const old = row.dataset.rowIndex;
                row.querySelectorAll('[name]').forEach((input) => {
                    input.name = input.name.replace(new RegExp('^' + field + '\\[[^\\]]+\\]'), field + '[' + index + ']');
                });
                row.querySelectorAll('[id], [for], [aria-describedby]').forEach((element) => {
                    for (const attribute of ['id', 'for', 'aria-describedby']) {
                        if (element.hasAttribute(attribute)) element.setAttribute(attribute, element.getAttribute(attribute).replace(new RegExp('-' + old + '(-error)?$'), '-' + index + '$1'));
                    }
                });
                row.dataset.rowIndex = index;
            });
            const empty = group.querySelector('[data-empty-rows]');
            if (empty) empty.hidden = rows.length > 0;
            group.querySelector('[data-add-row]').disabled = rows.length >= Number(group.dataset.maxRows);
        };
        form.querySelectorAll('[data-repeat-group]').forEach((group) => {
            syncRows(group);
            group.querySelector('[data-add-row]').addEventListener('click', () => {
                const rows = group.querySelector('[data-repeat-rows]');
                const index = rows.children.length;
                const html = group.querySelector('template').innerHTML.replaceAll('__INDEX__', String(index));
                rows.insertAdjacentHTML('beforeend', html);
                syncRows(group);
                rows.lastElementChild.querySelector('input:not([type="hidden"]), select')?.focus();
                dirty();
                status.textContent = 'Isian baru ditambahkan.';
            });
            group.addEventListener('click', (event) => {
                const button = event.target.closest('[data-remove-row]');
                if (!button) return;
                button.closest('[data-repeat-row]').remove();
                syncRows(group);
                group.querySelector('[data-add-row]').focus();
                dirty();
                status.textContent = 'Isian dihapus.';
            });
        });
        const summary = form.querySelector('#summary');
        const count = document.querySelector('[data-count-for="summary"]');
        summary.addEventListener('input', () => { count.textContent = `${[...summary.value].length}/200`; });
        form.addEventListener('input', dirty);
        form.addEventListener('change', (event) => {
            dirty();
            const input = event.target;
            if (!input.matches('input[type="file"]')) return;
            input.setCustomValidity('');
            const file = input.files[0];
            if (file && (!['image/png', 'image/jpeg'].includes(file.type) || file.size > Number(input.dataset.fileMax))) {
                input.setCustomValidity(`Pilih gambar PNG/JPG maksimal ${Number(input.dataset.fileMax) / 1048576} MB.`);
                input.reportValidity();
                return;
            }
            if (input.matches('[data-illustration]')) {
                if (imageUrl) URL.revokeObjectURL(imageUrl);
                const preview = form.querySelector('[data-image-preview]');
                if (file) {
                    imageUrl = URL.createObjectURL(file);
                    const image = new Image(); image.alt = 'Pratinjau ilustrasi profesi'; image.src = imageUrl;
                    preview.replaceChildren(image);
                    form.querySelector('[data-image-filename]').textContent = file.name;
                }
            }
        });
        const prepareAction = (action) => {
            form.querySelectorAll('[data-publish-required]').forEach((input) => { input.required = action === 'publish'; });
            const illustration = form.querySelector('[data-illustration]');
            illustration.required = action === 'publish' && (illustration.dataset.existingImage !== 'true' || form.elements.remove_illustration?.checked);
        };
        // Set requirements before the browser's native validation runs.
        form.querySelectorAll('button[type="submit"]').forEach((button) => button.addEventListener('click', () => prepareAction(button.value)));
        form.addEventListener('submit', (event) => {
            const action = event.submitter?.value || 'draft';
            prepareAction(action);
            if (!form.reportValidity()) { event.preventDefault(); return; }
            // Disabled submit buttons are omitted from the POST; retain the chosen action.
            const intent = document.createElement('input'); intent.type = 'hidden'; intent.name = 'action'; intent.value = action; form.append(intent);
            form.setAttribute('aria-busy', 'true');
            form.querySelectorAll('button[type="submit"]').forEach((button) => { button.disabled = true; });
            if (event.submitter) event.submitter.textContent = 'Menyimpan…';
            status.textContent = 'Menyimpan informasi profesi.';
        });
    }
    const dialog = document.querySelector('[data-deactivate-dialog]');
    if (dialog && typeof dialog.showModal === 'function') {
        let pending;
        document.querySelectorAll('[data-deactivate-form]').forEach((form) => form.addEventListener('submit', (event) => {
            event.preventDefault(); pending = form;
            dialog.querySelector('[data-deactivate-copy]').textContent = `Nonaktifkan ${form.dataset.professionName}?`;
            dialog.showModal();
        }));
        dialog.querySelector('[data-cancel-deactivate]').addEventListener('click', () => dialog.close());
        dialog.querySelector('[data-confirm-deactivate]').addEventListener('click', () => { dialog.close(); if (pending) HTMLFormElement.prototype.submit.call(pending); });
    }
})();
