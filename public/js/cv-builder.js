/**
 * CV Builder Dynamic Form Script
 * Handles dynamic repeaters for education, skills, experience, projects, and certifications.
 */

document.addEventListener('DOMContentLoaded', function () {
    // Helper to generate unique incremental index
    function getNextIndex(container) {
        return container.querySelectorAll('.repeater-item').length;
    }

    // Bind remove button delegation
    document.addEventListener('click', function (e) {
        const removeBtn = e.target.closest('.btn-remove-row');
        if (!removeBtn) return;

        const item = removeBtn.closest('.repeater-item');
        const container = item.closest('.repeater-container');
        
        if (item) {
            item.style.opacity = '0';
            item.style.transform = 'scale(0.96)';
            item.style.transition = 'all 150ms ease';
            setTimeout(function () {
                item.remove();
                renumberRows(container);
            }, 150);
        }
    });

    function renumberRows(container) {
        if (!container) return;
        const prefix = container.getAttribute('data-prefix');
        const items = container.querySelectorAll('.repeater-item');
        items.forEach((item, idx) => {
            const titleEl = item.querySelector('.repeater-item-title');
            if (titleEl) {
                const label = titleEl.getAttribute('data-label') || 'Item';
                titleEl.textContent = label + ' #' + (idx + 1);
            }
        });
    }

    // 1. Pendidikan (Education) Repeater
    const addEduBtn = document.getElementById('btn-add-education');
    if (addEduBtn) {
        addEduBtn.addEventListener('click', function () {
            const container = document.getElementById('education-repeater');
            const idx = getNextIndex(container);
            const template = `
                <div class="repeater-item">
                    <div class="repeater-item-header">
                        <h3 class="repeater-item-title" data-label="Riwayat Pendidikan">Riwayat Pendidikan #${idx + 1}</h3>
                        <button type="button" class="btn-remove-row" aria-label="Hapus riwayat pendidikan ini">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 6h18M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" /></svg>
                            Hapus
                        </button>
                    </div>
                    <div class="form-grid-2">
                        <div class="editor-field">
                            <label>Nama Institusi / Universitas <span class="required-indicator">*</span></label>
                            <input type="text" name="educations[${idx}][institution]" class="form-control" placeholder="Contoh: Institut Teknologi Telkom Surabaya" required>
                        </div>
                        <div class="editor-field">
                            <label>Jenjang / Gelar</label>
                            <input type="text" name="educations[${idx}][degree]" class="form-control" placeholder="Contoh: Sarjana (S1)">
                        </div>
                    </div>
                    <div class="form-grid-3">
                        <div class="editor-field">
                            <label>Jurusan / Bidang Studi</label>
                            <input type="text" name="educations[${idx}][field_of_study]" class="form-control" placeholder="Contoh: Sistem Informasi">
                        </div>
                        <div class="editor-field">
                            <label>Tahun Masuk - Lulus</label>
                            <div style="display:flex;gap:10px;">
                                <input type="text" name="educations[${idx}][start_year]" class="form-control" style="flex:1;" placeholder="Mulai (2022)">
                                <input type="text" name="educations[${idx}][end_year]" class="form-control" style="flex:1;" placeholder="Lulus (2026)">
                            </div>
                        </div>
                        <div class="editor-field">
                            <label>IPK / Nilai Kelulusan</label>
                            <input type="text" name="educations[${idx}][gpa]" class="form-control" placeholder="Contoh: 3.80 / 4.00">
                        </div>
                    </div>
                    <div class="editor-field" style="margin-bottom:0;">
                        <label>Deskripsi Tambahan / Prestasi Akademik</label>
                        <textarea name="educations[${idx}][description]" class="form-control" style="min-height:70px;" placeholder="Tuliskan fokus studi, organisasi kampus, atau prestasi penting..."></textarea>
                    </div>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', template);
        });
    }

    // 2. Keterampilan (Skills) Repeater
    const addSkillBtn = document.getElementById('btn-add-skill');
    if (addSkillBtn) {
        addSkillBtn.addEventListener('click', function () {
            const container = document.getElementById('skills-repeater');
            const idx = getNextIndex(container);
            const template = `
                <div class="repeater-item">
                    <div class="repeater-item-header">
                        <h3 class="repeater-item-title" data-label="Kelompok Keterampilan">Kelompok Keterampilan #${idx + 1}</h3>
                        <button type="button" class="btn-remove-row" aria-label="Hapus kelompok keterampilan ini">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 6h18M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" /></svg>
                            Hapus
                        </button>
                    </div>
                    <div class="form-grid-2">
                        <div class="editor-field">
                            <label>Nama Kategori Keterampilan <span class="required-indicator">*</span></label>
                            <input type="text" name="skills[${idx}][category]" class="form-control" placeholder="Contoh: Bahasa Pemrograman, Tools Desain, Soft Skills" required>
                        </div>
                        <div class="editor-field">
                            <label>Daftar Keterampilan (Dipisahkan koma) <span class="required-indicator">*</span></label>
                            <input type="text" name="skills[${idx}][items]" class="form-control" placeholder="Contoh: Python, SQL, Laravel, Git, Docker" required>
                        </div>
                    </div>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', template);
        });
    }

    // 3. Pengalaman (Experience) Repeater
    const addExpBtn = document.getElementById('btn-add-experience');
    if (addExpBtn) {
        addExpBtn.addEventListener('click', function () {
            const container = document.getElementById('experience-repeater');
            const idx = getNextIndex(container);
            const template = `
                <div class="repeater-item">
                    <div class="repeater-item-header">
                        <h3 class="repeater-item-title" data-label="Pengalaman">Pengalaman #${idx + 1}</h3>
                        <button type="button" class="btn-remove-row" aria-label="Hapus pengalaman ini">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 6h18M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" /></svg>
                            Hapus
                        </button>
                    </div>
                    <div class="form-grid-2">
                        <div class="editor-field">
                            <label>Posisi / Peran <span class="required-indicator">*</span></label>
                            <input type="text" name="experiences[${idx}][position]" class="form-control" placeholder="Contoh: Business Analyst Intern" required>
                        </div>
                        <div class="editor-field">
                            <label>Nama Perusahaan / Organisasi <span class="required-indicator">*</span></label>
                            <input type="text" name="experiences[${idx}][company]" class="form-control" placeholder="Contoh: PT Bank Mandiri (Persero) Tbk" required>
                        </div>
                    </div>
                    <div class="form-grid-3">
                        <div class="editor-field">
                            <label>Lokasi</label>
                            <input type="text" name="experiences[${idx}][location]" class="form-control" placeholder="Contoh: Jakarta / Remote">
                        </div>
                        <div class="editor-field">
                            <label>Periode Mulai</label>
                            <input type="text" name="experiences[${idx}][start_date]" class="form-control" placeholder="Contoh: Jan 2024">
                        </div>
                        <div class="editor-field">
                            <label>Periode Selesai</label>
                            <input type="text" name="experiences[${idx}][end_date]" class="form-control" placeholder="Contoh: Jun 2024 / Sekarang">
                        </div>
                    </div>
                    <div class="editor-field" style="margin-bottom:0;">
                        <label>Deskripsi Tugas & Pencapaian</label>
                        <textarea name="experiences[${idx}][description]" class="form-control" style="min-height:80px;" placeholder="Jelaskan peran Anda, tugas utama, dan hasil nyata yang dicapai..."></textarea>
                    </div>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', template);
        });
    }

    // 4. Proyek (Projects) Repeater
    const addProjBtn = document.getElementById('btn-add-project');
    if (addProjBtn) {
        addProjBtn.addEventListener('click', function () {
            const container = document.getElementById('project-repeater');
            const idx = getNextIndex(container);
            const template = `
                <div class="repeater-item">
                    <div class="repeater-item-header">
                        <h3 class="repeater-item-title" data-label="Proyek">Proyek #${idx + 1}</h3>
                        <button type="button" class="btn-remove-row" aria-label="Hapus proyek ini">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 6h18M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" /></svg>
                            Hapus
                        </button>
                    </div>
                    <div class="form-grid-2">
                        <div class="editor-field">
                            <label>Nama Proyek <span class="required-indicator">*</span></label>
                            <input type="text" name="projects[${idx}][title]" class="form-control" placeholder="Contoh: Aplikasi E-Commerce Berbasis Laravel" required>
                        </div>
                        <div class="editor-field">
                            <label>Peran Anda</label>
                            <input type="text" name="projects[${idx}][role]" class="form-control" placeholder="Contoh: Lead Backend Developer">
                        </div>
                    </div>
                    <div class="form-grid-3">
                        <div class="editor-field">
                            <label>Waktu / Periode</label>
                            <input type="text" name="projects[${idx}][period]" class="form-control" placeholder="Contoh: Nov 2024">
                        </div>
                        <div class="editor-field">
                            <label>Teknologi yang Digunakan</label>
                            <input type="text" name="projects[${idx}][technologies]" class="form-control" placeholder="Contoh: Laravel, Tailwind, MySQL">
                        </div>
                        <div class="editor-field">
                            <label>Tautan / URL Proyek</label>
                            <input type="url" name="projects[${idx}][url]" class="form-control" placeholder="https://github.com/username/project">
                        </div>
                    </div>
                    <div class="editor-field" style="margin-bottom:0;">
                        <label>Deskripsi Proyek</label>
                        <textarea name="projects[${idx}][description]" class="form-control" style="min-height:75px;" placeholder="Jelaskan fitur utama, masalah yang dipecahkan, dan kontribusi Anda..."></textarea>
                    </div>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', template);
        });
    }

    // 5. Sertifikasi (Certifications) Repeater
    const addCertBtn = document.getElementById('btn-add-certification');
    if (addCertBtn) {
        addCertBtn.addEventListener('click', function () {
            const container = document.getElementById('certification-repeater');
            const idx = getNextIndex(container);
            const template = `
                <div class="repeater-item">
                    <div class="repeater-item-header">
                        <h3 class="repeater-item-title" data-label="Sertifikasi">Sertifikasi #${idx + 1}</h3>
                        <button type="button" class="btn-remove-row" aria-label="Hapus sertifikasi ini">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 6h18M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" /></svg>
                            Hapus
                        </button>
                    </div>
                    <div class="form-grid-2">
                        <div class="editor-field">
                            <label>Nama Sertifikasi <span class="required-indicator">*</span></label>
                            <input type="text" name="certifications[${idx}][name]" class="form-control" placeholder="Contoh: AWS Certified Cloud Practitioner" required>
                        </div>
                        <div class="editor-field">
                            <label>Organisasi / Lembaga Penerbit</label>
                            <input type="text" name="certifications[${idx}][issuer]" class="form-control" placeholder="Contoh: Amazon Web Services (AWS)">
                        </div>
                    </div>
                    <div class="form-grid-3">
                        <div class="editor-field">
                            <label>Tanggal Terbit</label>
                            <input type="text" name="certifications[${idx}][issue_date]" class="form-control" placeholder="Contoh: Okt 2024">
                        </div>
                        <div class="editor-field">
                            <label>ID Kredensial / Sertifikat</label>
                            <input type="text" name="certifications[${idx}][credential_id]" class="form-control" placeholder="Contoh: AWS-0012938">
                        </div>
                        <div class="editor-field">
                            <label>URL Verifikasi Kredensial</label>
                            <input type="url" name="certifications[${idx}][credential_url]" class="form-control" placeholder="https://www.credly.com/...">
                        </div>
                    </div>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', template);
        });
    }

    // Smooth section scrolling and pill active highlight
    const navPills = document.querySelectorAll('.cv-nav-pill');
    navPills.forEach(pill => {
        pill.addEventListener('click', function (e) {
            navPills.forEach(p => p.classList.remove('is-active'));
            this.classList.add('is-active');
        });
    });
});

