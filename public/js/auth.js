(() => {
    'use strict';

    document.querySelectorAll('[data-password-toggle]').forEach((button) => {
        button.addEventListener('click', () => {
            const input = document.getElementById(button.getAttribute('aria-controls'));
            const visible = input.type === 'password';
            input.type = visible ? 'text' : 'password';
            button.setAttribute('aria-pressed', String(visible));
            const label = document.querySelector(`label[for="${input.id}"]`).textContent.toLowerCase();
            button.setAttribute('aria-label', `${visible ? 'Sembunyikan' : 'Tampilkan'} ${label}`);
        });
    });

    document.querySelector('[data-focus-auth]')?.addEventListener('click', () => {
        requestAnimationFrame(() => document.querySelector('[data-auth-form] input:not([type="hidden"])')?.focus({ preventScroll: true }));
    });

    const form = document.querySelector('[data-auth-form]');
    if (!form || !window.fetch) return;

    const submit = form.querySelector('[data-submit-button]');
    const submitLabel = form.querySelector('[data-submit-label]');
    const status = form.querySelector('[data-form-status]');
    const notice = form.querySelector('[data-form-error]');
    const originalLabel = submitLabel.textContent;
    const isRegister = form.dataset.mode === 'register';
    const inputs = [...form.querySelectorAll('input:not([type="hidden"]):not([type="checkbox"])')];

    const setError = (input, message = '') => {
        input.setAttribute('aria-invalid', String(Boolean(message)));
        const error = document.getElementById(`${input.id}-error`);
        if (error) {
            error.textContent = message;
            error.hidden = !message;
        }
    };

    const inputError = (input) => {
        if (!input.value.trim()) return `${input.labels[0].textContent} wajib diisi.`;
        if (input.type === 'email' && input.validity.typeMismatch) return 'Masukkan alamat email yang valid.';
        if (input.maxLength > 0 && input.value.length > input.maxLength) return `Maksimal ${input.maxLength} karakter.`;
        if (input.name.startsWith('password')) {
            if (new TextEncoder().encode(input.value).length > 72 || input.value.includes('\0')) return 'Kata sandi maksimal 72 byte dan tidak boleh mengandung karakter null.';
            if (isRegister && input.name === 'password' && (input.value.length < 8 || !/\p{L}/u.test(input.value) || !/\p{N}/u.test(input.value))) return 'Gunakan minimal 8 karakter dengan huruf dan angka.';
            if (input.name === 'password_confirmation' && input.value !== form.elements.password.value) return 'Konfirmasi kata sandi belum sesuai.';
        }
        return '';
    };

    // Keep native validation when JavaScript is unavailable; enhance errors inline here.
    form.noValidate = true;
    inputs.forEach((input) => {
        input.addEventListener('input', () => {
            if (input.getAttribute('aria-invalid') === 'true') setError(input);
            notice.hidden = true;
        });
        input.addEventListener('blur', () => {
            if (input.value || input.getAttribute('aria-invalid') === 'true') setError(input, inputError(input));
        });
    });

    form.addEventListener('submit', async (event) => {
        event.preventDefault();
        if (form.getAttribute('aria-busy') === 'true') return;

        notice.hidden = true;
        const invalid = inputs.filter((input) => {
            const message = inputError(input);
            setError(input, message);
            return Boolean(message);
        });
        if (invalid.length) {
            status.textContent = 'Periksa kembali isian formulirmu.';
            invalid[0].focus();
            return;
        }

        form.setAttribute('aria-busy', 'true');
        submit.disabled = true;
        submitLabel.textContent = isRegister ? 'Membuat akun…' : 'Sedang masuk…';
        status.textContent = submitLabel.textContent;

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                credentials: 'same-origin',
                headers: { Accept: 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                body: new FormData(form),
            });
            const data = await response.json().catch(() => ({}));

            if (response.ok && data.redirect) {
                status.textContent = data.message;
                window.location.assign(data.redirect);
                return;
            }

            let firstInvalid;
            for (const [name, messages] of Object.entries(data.errors || {})) {
                const input = form.elements.namedItem(name);
                if (input?.matches?.('input')) {
                    setError(input, Array.isArray(messages) ? messages[0] : messages);
                    firstInvalid ||= input;
                }
            }

            const messages = {
                401: 'Email atau kata sandi tidak sesuai. Silakan coba lagi.',
                419: 'Sesi halaman telah berakhir. Muat ulang halaman sebelum mencoba lagi.',
                422: 'Ada isian yang perlu diperiksa. Silakan coba lagi.',
                429: 'Terlalu banyak percobaan. Tunggu satu menit, lalu coba lagi.',
            };
            notice.textContent = messages[response.status] || 'Kami belum bisa memproses permintaanmu. Silakan coba beberapa saat lagi.';
            notice.hidden = false;
            (firstInvalid || notice).focus();
        } catch {
            notice.textContent = 'Koneksi terputus. Periksa internetmu, lalu coba lagi.';
            notice.hidden = false;
            notice.focus();
        } finally {
            form.removeAttribute('aria-busy');
            submit.disabled = false;
            submitLabel.textContent = originalLabel;
        }
    });
})();
