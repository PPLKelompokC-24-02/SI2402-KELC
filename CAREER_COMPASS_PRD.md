# Product Requirements Document (PRD)
## Career Compass — Versi implementasi untuk Codex

**Status:** PRD implementasi awal (diturunkan dari *Proposal Pengembangan Produk Career Compass*, 8 Oktober 2026)  
**Pengguna sasaran:** Mahasiswa aktif S1 Sistem Informasi, Telkom University Kampus Jakarta  
**Platform:** Aplikasi web standalone  
**Metode delivery:** Agile/Scrum, 2 sprint fitur sesuai proposal, didahului setup fondasi teknis  
**Stack wajib sesuai proposal:** PHP, Laravel, MySQL, HTML, CSS, JavaScript.

> **Aturan membaca dokumen:** Bagian berlabel **[PROPOSAL]** merupakan kebutuhan dari proposal. Bagian berlabel **[REKOMENDASI IMPLEMENTASI]** adalah keputusan praktis untuk membantu Codex, bukan fakta/ketentuan eksplisit dari proposal. Jika bertentangan, prioritaskan kebutuhan fungsional (KF), user story (US), acceptance criteria (AC), batasan lingkup, dan kebutuhan teknis proposal. Jangan menyimpulkan bahwa aplikasi memakai integrasi akademik kampus atau AI otomatis.

## 1. Ringkasan produk

**[PROPOSAL]** Career Compass merupakan website untuk membantu mahasiswa mengeksplorasi profesi yang relevan dengan Sistem Informasi; memahami kecenderungan karier berdasarkan asesmen dan data akademik yang *ditinjau manusia (Assessor)*; membuat rencana pengembangan diri; mencari informasi kegiatan karier; menyusun CV; dan memantau checklist persiapan karier.

**Value proposition:** Seluruh aktivitas persiapan karier dalam satu website, namun fitur-fitur mahasiswa dapat digunakan secara mandiri. Hanya pengajuan *Career Assessment & Academic Analysis* yang mewajibkan kelengkapan jawaban asesmen dan data akademik sebelum peninjauan oleh Assessor.

**Prinsip:** Hasil analisis adalah bahan pertimbangan pengembangan diri, **bukan penentu pasti profesi yang cocok atau jaminan mendapatkan pekerjaan**. Konteks SDG 4 target 4.4 adalah dukungan pengembangan keterampilan relevan untuk dunia kerja.

### Tujuan produk
1. Menghadirkan katalog profesi, kompetensi, career path, tools, dan mata kuliah relevan.
2. Memfasilitasi asesmen + input akademik/KHS serta evaluasi manual oleh Assessor.
3. Membantu mahasiswa mengelola rencana aksi dan tenggat waktu.
4. Menyediakan informasi Career Event, CV Builder, dan checklist kemajuan persiapan karier.
5. Menjamin akses data dibatasi berdasarkan role dan kepemilikan.

### Tidak termasuk dalam scope
- Integrasi otomatis SIAKAD/API Telkom University atau sinkronisasi KHS.
- Pendaftaran atau pembayaran Career Event di dalam Career Compass; pengguna diarahkan ke tautan resmi penyelenggara.
- Penilaian/pemilihan karier dengan AI, model ML, rekomendasi deterministik, atau pencocokan otomatis yang belum memiliki rumus yang disetujui.
- Verifikasi otomatis bukti aktivitas checklist.
- Bursa kerja, rekrutmen, chat assessor real-time, manajemen alumni, gamifikasi, dan layanan mobile native.
- Jaminan kelayakan/ketepatan rekomendasi kerja.

## 2. Aktor dan izin

| Role | Bisa melakukan | Batasan kritis |
|---|---|---|
| **Mahasiswa** | Melihat katalog profesi dan event; menyimpan profesi; mengelola jawaban asesmen, data akademik, action plan, CV, checklist; mengajukan analisis; melihat hasil yang dipublikasi | Hanya data milik sendiri; tidak mengakses nilai/evaluasi yang belum dipublikasi |
| **Assessor** | Melihat pengajuan yang ditugaskan; meninjau asesmen dan akademik; memberi nilai/catatan/rekomendasi; meminta revisi; publikasi hasil | Tidak mengelola master data admin dan tidak mengakses pengajuan di luar wewenang |
| **Admin** | Mengelola bank soal, kategori, mata kuliah, profesi, career path, kompetensi/tools, template action plan, event; melihat hasil review yang sudah Published | Tidak memberi/mengubah skor, evaluasi, atau rekomendasi Assessor |

**[REKOMENDASI IMPLEMENTASI]** Gunakan satu tabel `users` dengan kolom `role` (`mahasiswa`, `assessor`, `admin`); Laravel middleware/policies untuk izin. Mahasiswa boleh mendaftar sendiri bila fitur register diaktifkan, tetapi role selalu `mahasiswa` di server. Akun Admin/Assessor disediakan melalui seeder atau pembuatan akun terbatas Admin; jangan izinkan registrasi publik memilih role. Kebijakan verifikasi status mahasiswa kampus **belum ditentukan** dalam proposal.

## 3. Modul dan persyaratan fungsional

### M1 — Career Assessment & Academic Analysis (KF-01, KF-03, KF-04; US-01, US-03 s.d. US-10)

**[PROPOSAL]**
- Admin melakukan CRUD/penonaktifan bank soal, pilihan jawaban, kategori bidang karier, dan informasi/aturan penilaian.
- Mahasiswa mengisi jawaban asesmen bertahap (draft), menyimpan, melanjutkan, dan mengubahnya sebelum submit.
- Mahasiswa memasukkan data akademik **manual**: semester, mata kuliah dan nilai KHS. Perubahan dapat dilakukan sesuai status pengajuan. KHS tidak diimpor otomatis dari kampus.
- Sistem melakukan validasi kelengkapan asesmen + akademik; pengajuan tidak boleh lanjut jika data wajib belum lengkap.
- Mahasiswa mengonfirmasi ringkasan pengajuan, lalu sistem menyimpan status **Under Review** dan mengunci data yang ditinjau.
- Assessor yang berwenang melihat jawaban asesmen + data akademik dalam satu halaman; status nilai `Belum Dinilai` jika belum dinilai.
- Assessor menyimpan skor/penilaian, evaluasi, feedback, rekomendasi karier; bisa meminta perbaikan dengan alasan sehingga status **Revision Required**.
- Mahasiswa melihat alasan revisi, memperbaiki data yang diminta, dan mengajukan ulang menjadi **Under Review**.
- Assessor melakukan publikasi hanya setelah hasil evaluasi lengkap; status **Published**, dengan pencatatan `published_at` dan `published_by`.
- Hanya setelah Published mahasiswa melihat evaluasi akhir, skor, feedback, dan rekomendasi yang ditujukan kepadanya. Admin boleh melihat hasil Published *read-only*.
- Hasil analisis tidak boleh dipresentasikan sebagai kepastian kecocokan profesi.

**State machine [REKOMENDASI IMPLEMENTASI]:**

```text
DRAFT --submit valid--> UNDER_REVIEW
UNDER_REVIEW --request revision + notes--> REVISION_REQUIRED
REVISION_REQUIRED --resubmit valid--> UNDER_REVIEW
UNDER_REVIEW --complete evaluation + publish--> PUBLISHED
```

`DRAFT` merupakan keadaan sebelum diajukan. Jangan mengizinkan transisi lain (misal publikasi tanpa evaluasi, edit mahasiswa ketika UNDER_REVIEW/PUBLISHED). Simpan snapshot jawaban dan akademik di level submission atau gunakan versi yang tidak bisa diubah pada saat review.

**Catatan penting:** Proposal memberi hak Assessor melakukan penilaian, tetapi **tidak menetapkan rumus skor baku, bobot kategori, ambang kelulusan, maupun algoritme rekomendasi**. Untuk MVP, tampilkan jawaban yang tersimpan dan gunakan nilai serta rekomendasi yang dimasukkan Assessor. Sediakan struktur konfigurasi soal/kategori tanpa mengarang skoring psikometrik. Jika skor otomatis kelak diperlukan, sepakati aturan dulu.

### M2 — Career Exploration & Career Path (KF-02, KF-05; US-02, US-11 s.d. US-15)

**[PROPOSAL]**
- Admin CRUD katalog **mata kuliah**, **profesi**, **kompetensi**, **career path berurutan**, pemetaan profesi↔mata kuliah dan kompetensi pada tahapan.
- Informasi profesi meliputi nama, ringkasan, deskripsi, tanggung jawab, kategori, kompetensi, tools, tahapan karier dan mata kuliah relevan yang tersedia.
- Mahasiswa dapat mencari dan memfilter profesi, membuka detail dan melihat career path serta mata kuliah relevan.
- Mahasiswa dapat menyimpan/menghapus profesi favorit atau referensi target karier; cegah duplikasi per mahasiswa; menghapus favorit tidak menghapus profesi master.
- Bisa digunakan **tanpa pernah mengerjakan assessment**.
- Penghapusan/nonaktifkan data master yang sedang direferensikan tidak boleh merusak relasi historis.

**[REKOMENDASI IMPLEMENTASI]** Gunakan `is_active`/soft delete untuk katalog. Pada halaman detail buat tab: Overview, Career Path, Relevant Courses, Tools & Skills. Seed contoh bersifat demonstrasi, misalnya Data Analyst, Business Analyst, UI/UX Designer, Software Engineer, bukan rekomendasi otomatis.

### M3 — Career Action Plan (KF-06, KF-07; US-16, US-17)

**[PROPOSAL]**
- Admin CRUD template aktivitas pengembangan diri.
- Mahasiswa dapat membuat aktivitas sendiri atau berdasarkan template, melihat daftar/detail, mengedit deskripsi dan deadline, menghapus dan mengubah status ke **Not Started**, **In Progress**, atau **Completed**.
- Hanya data milik mahasiswa sendiri; menghapus/menonaktifkan template tidak menghapus aktivitas yang sudah dibuat darinya.

**[REKOMENDASI IMPLEMENTASI]** Form/list sebagai implementasi minimal; tambah tampilan papan Kanban tiga kolom jika tidak menghambat fungsi inti karena mockup menunjukkan alternatif papan. Tampilkan ringkasan jumlah/proporsi Completed, tetapi jangan campur metrik ini dengan checklist M6.

### M4 — Event Management (KF-08, KF-09; US-18, US-19)

**[PROPOSAL]**
- Admin CRUD event: judul, kategori, deskripsi, penyelenggara, tanggal/waktu, lokasi, poster, status, URL pendaftaran resmi.
- Mahasiswa dapat melihat daftar, mencari/filter kategori, membuka detail event dan membuka tautan pendaftaran **eksternal**.
- Bila tidak ditemukan, tampilkan empty state. Tidak tersedia transaksi/registrasi event internal.

**[REKOMENDASI IMPLEMENTASI]** Validasi URL `https://`/`http://` dan gunakan `rel="noopener noreferrer"` untuk tautan eksternal; simpan poster di Laravel public storage. Gunakan status `draft`/`published` atau status yang setara untuk kontrol visibilitas.

### M5 — CV Builder (KF-10; US-20, US-21)

**[PROPOSAL]**
- Mahasiswa CRUD data profil, kontak, pendidikan, skills, pengalaman, proyek, sertifikasi.
- Mahasiswa dapat memilih template yang tersedia, melihat pratinjau dengan data terbaru, lalu **download CV sebagai PDF yang dapat dibuka**.
- Semua data CV terkait akun mahasiswa; data lain tidak boleh diakses.

**[REKOMENDASI IMPLEMENTASI]** Mulai dari **1 template CV ATS-friendly**, bisa dikembangkan multi-template; gunakan Blade untuk HTML preview dan pustaka PDF Laravel yang stabil (mis. `barryvdh/laravel-dompdf`), cek kompatibilitas versi sebelum instalasi. Unggahan gambar profil CV bukan persyaratan inti.

### M6 — Career Progress Tracking (KF-11; US-22)

**[PROPOSAL]**
- Mahasiswa melihat checklist kegiatan persiapan karier, mencentang atau membatalkan cek, dan melihat total/selesai/belum serta persentase yang diperbarui ketika status berubah.
- Data terisolasi per mahasiswa. Centang merupakan **self-report**, tidak diverifikasi otomatis.

**Formula:** `persentase = completed_count / total_count * 100` bila `total_count > 0`; jika tidak ada checklist, tampilkan 0% dan empty state. Perhitungan harus konsisten dan mudah diuji.

**[REKOMENDASI IMPLEMENTASI]** Aktivitas checklist dapat berasal dari seed/master sederhana yang dibaca pengguna. Jangan mengasumsikan perubahan status M3 otomatis mencentang M6 (dua konsep berbeda pada proposal).

## 4. Matriks keterlacakan US ↔ sprint

Pembagian **Sprint 1** dan **Sprint 2** mengikuti tabel backlog proposal. Urutan di dalam sprint di bawah adalah urutan implementasi yang disarankan berdasarkan dependensi, bukan perubahan assignment sprint pada dokumen sumber.

| User story | Hasil yang harus dapat didemokan | Sprint |
|---|---|---|
| US-01 | Admin CRUD bank soal + pilihan + kategori, validasi | 2 |
| US-02 | Admin CRUD mata kuliah dan pemetaan karier | 2 |
| US-03 | Mahasiswa isi, simpan, lanjutkan draft assessment | 2 |
| US-04 | Mahasiswa CRUD KHS/semester/mata kuliah/nilai manual | 2 |
| US-05 | Validasi & submit assessment+KHS → Under Review | 2 |
| US-06 | Assessor melihat pengajuan yang berwenang & nilai | 2 |
| US-07 | Assessor evaluasi, feedback, rekomendasi, minta revisi | 2 |
| US-08 | Assessor validasi + publish hasil | 2 |
| US-09 | Mahasiswa melihat hasil Published miliknya saja | 2 |
| US-10 | Admin lihat hasil Published read-only | 2 |
| US-11 | Admin CRUD katalog profesi | 1 |
| US-12 | Mahasiswa browse/cari/detail profesi | 1 |
| US-13 | Admin CRUD career path, skill & pemetaan | 1 |
| US-14 | Mahasiswa melihat career path, skill, course relevan | 1 |
| US-15 | Mahasiswa simpan/hapus profesi favorit | 1 |
| US-16 | Admin CRUD template Action Plan | 1 |
| US-17 | Mahasiswa CRUD Action Plan + deadline/status | 1 |
| US-18 | Admin CRUD event + URL resmi | 1 |
| US-19 | Mahasiswa katalog, cari/filter, detail, tautan eksternal event | 1 |
| US-20 | Mahasiswa CRUD data CV lengkap | 1 |
| US-21 | Mahasiswa preview & download CV PDF valid | 1 |
| US-22 | Mahasiswa checklist independen + persentase progress | 2 |

**Fondasi lintas fitur sebelum Sprint 1:** setup Laravel/MySQL, konfigurasi `.env`, autentikasi login/logout, role-based authorization, layout responsive, halaman dashboard masing-masing role, migrasi, seeders, testing, README. Pekerjaan fondasi ini diperlukan untuk menjalankan US tetapi bukan US baru dari proposal.

## 5. Navigasi, layar, dan pengalaman pengguna

**[PROPOSAL]** Bab Mockup memuat wireframe dengan sidebar, layar Assessment & Academic Profile, halaman peninjauan Assessor, katalog dan detail Career Exploration, tahapan Career Path, relevant courses dan tools, Action Plan (ringkasan, detail, daftar, papan), Event Management, CV Builder (form + preview), serta Career Progress Tracking. Desain diimplementasikan secara konsisten sambil menjaga fungsi wireframe; proposal **tidak** menetapkan design system atau palet warna wajib.

**[REKOMENDASI IMPLEMENTASI]** Gunakan Laravel Blade, Tailwind CSS, dan JavaScript ringan/Alpine bila diperlukan. Tidak wajib membuat SPA/React karena proposal memilih Laravel + PHP dan tidak mensyaratkan frontend framework spesifik. Semua layar harus responsive (desktop dan mobile), dengan sidebar yang menjadi drawer di HP, loading/error/empty state, validasi field inline, badge status yang konsisten, dan konfirmasi untuk delete/publish. Gunakan bahasa Indonesia untuk UI, sementara istilah produk `Career Action Plan`/`Under Review` boleh dipertahankan.

### Struktur navigasi yang disarankan

**Mahasiswa**
- `/dashboard` — greeting, ringkasan kegiatan, navigasi modul (jangan tampilkan fake metrics).
- `/career-exploration` — katalog dan pencarian/filter profesi.
- `/career-exploration/{slug}` — detail, career path, relevant courses, tools, favorite.
- `/my-careers` — profesi tersimpan.
- `/action-plan` — daftar + status; `/action-plan/{id}` detail; formulir add/edit.
- `/events` dan `/events/{slug}` — event listing/detail + external registration.
- `/cv-builder` dan `/cv-builder/preview` — edit, preview dan unduh PDF.
- `/assessment` — assessment draft + status pengajuan.
- `/academic-profile` — input akademik manual.
- `/assessment/result` — hasil Published atau status waiting/revision.
- `/progress-tracking` — checklist dan ringkasan progres.
- `/profile` — pengaturan profil minimal.

**Assessor**
- `/assessor/dashboard` — ringkasan beban review nyata.
- `/assessor/submissions` — daftar pengajuan yang menjadi wewenangnya, filter status.
- `/assessor/submissions/{id}` — satu halaman jawaban + akademik + form evaluasi, minta revisi atau publikasi.

**Admin**
- `/admin/dashboard` — ringkasan data master.
- `/admin/professions`, `/admin/career-paths`, `/admin/courses`, `/admin/competencies` — master profesi dan keterkaitan (boleh nested agar sederhana).
- `/admin/action-templates` — template pengembangan diri.
- `/admin/events` — kelola event.
- `/admin/assessment-questions`, `/admin/assessment-categories` — bank soal.
- `/admin/published-results` — hasil evaluasi Published, hanya view.

URL boleh disesuaikan dengan konvensi Laravel; yang harus konsisten adalah fungsi dan otorisasinya.

## 6. Alur bisnis utama

### A. Eksplorasi profesi (mandiri)
1. Mahasiswa login → klik Career Exploration.
2. Sistem menampilkan profesi aktif dari database, tidak perlu asesmen.
3. Mahasiswa mencari/filter → buka detail → lihat overview, responsibilities, skills/tools, path, relevant courses.
4. Mahasiswa menyimpan profesi; halaman My Careers berisi profesi milik akun itu.

### B. Career Assessment & Academic Analysis
1. Admin menyediakan bank soal/kategori dan katalog mata kuliah.
2. Mahasiswa login → jawab assessment dan simpan draft (bisa lanjut setelah logout-login).
3. Mahasiswa mengisi semester, mata kuliah, nilai KHS secara manual.
4. Mahasiswa menekan ajukan → server validasi kelengkapan dua jenis data → tampilkan ringkasan/konfirmasi.
5. Setelah submit, status Under Review; jawaban/data terkait dikunci.
6. Assessor membuka pengajuan yang ditugaskan → mengisi skor/penilaian, evaluasi dan rekomendasi.
7. Jika data kurang: Assessor request revision dengan catatan → mahasiswa perbaiki → resubmit ke Under Review.
8. Jika lengkap: Assessor memublikasikan → hasil Published terlihat hanya oleh mahasiswa pemilik dan admin (read-only).

### C. Rencana pengembangan mandiri
1. Mahasiswa membuka Action Plan → membuat aktivitas (kosong atau dari template).
2. Mahasiswa menetapkan deadline, mengubah status, mengedit dan menghapus.
3. Sistem memperbarui ringkasan status berdasarkan data yang tersimpan.

### D. CV Builder
1. Mahasiswa mengisi profil, pendidikan, skill, pengalaman, proyek dan sertifikasi.
2. Mahasiswa membuka preview → melihat data CV terakhir.
3. Mahasiswa unduh PDF → file valid dan hanya berisi data CV dirinya.

### E. Progress Tracking
1. Mahasiswa membuka checklist.
2. Centang/batal centang disimpan per akun.
3. Sistem menghitung total dan persentase terbaru, termasuk kondisi 0 checklist.

## 7. Rancangan data relasional (usulan implementasi)

> **[REKOMENDASI IMPLEMENTASI]** Ini merupakan usulan tabel teknis untuk mewujudkan KF/US dan relasi ERD proposal, **bukan transkripsi satu-per-satu semua entitas/atribut gambar ERD**. Periksa kembali diagram ERD/Class Diagram proposal sebelum finalisasi migrasi untuk tugas akademik. Gunakan primary key, foreign key, index, unique constraint, timestamps, dan soft delete hanya pada model yang memerlukannya.

| Tabel/model | Field penting (ringkas) | Relasi / aturan |
|---|---|---|
| `users` | `id`, `name`, `email` unique, `password`, `role`, `is_active` | 1 akun = 1 role |
| `student_profiles` | `user_id` unique, `student_number` nullable, `study_program`, `campus`, `current_semester` | milik mahasiswa; field identitas tambahan tidak dijadikan syarat kecuali disetujui |
| `career_categories` | `name`, `slug`, `is_active` | kategori profesi/asesmen bisa berbeda jika memerlukan taksonomi terpisah |
| `professions` | `category_id`, `name`, `slug`, `summary`, `description`, `responsibilities`, `is_active` | banyak profesi tiap kategori |
| `competencies` | `name`, `description`, `type` | master keterampilan/alat, dikaitkan ke profesi/tahapan |
| `profession_competency` | `profession_id`, `competency_id` | pivot unique pasangan |
| `career_path_stages` | `profession_id`, `title`, `description`, `sort_order` | urutan jelas per profesi; tahap bisa punya kompetensi tambahan |
| `career_path_stage_competency` | `career_path_stage_id`, `competency_id` | pivot stage-skill |
| `courses` | `code`, `name`, `credits` nullable, `is_active` | mata kuliah yang dikelola admin |
| `course_profession` | `course_id`, `profession_id` | mapping course dengan profesi |
| `saved_professions` | `user_id`, `profession_id`, timestamps | unique(user_id,profession_id) |
| `action_plan_templates` | `title`, `description`, `is_active` | milik master admin |
| `action_plans` | `user_id`, `template_id` nullable, `title`, `description`, `due_date`, `status` | status enum 3 nilai; template di-copy sebagai data mahasiswa (tidak cascade delete) |
| `career_events` | `title`, `slug`, `category`, `description`, `organizer`, `start_at`, `end_at` nullable, `location`, `poster_path`, `status`, `registration_url` | admin master, event published ditampilkan mahasiswa |
| `cv_profiles` | `user_id` unique, `headline`, `summary`, `phone`, `location`, `email`, `links` nullable | hanya milik mahasiswa |
| `cv_educations`, `cv_experiences`, `cv_projects`, `cv_certifications`, `cv_skills` | `user_id`/`cv_profile_id`, konten, `sort_order`, dates relevan | entitas anak CV; field sesuai jenis |
| `assessment_categories` | `name`, `description`, `is_active` | kategori bank soal |
| `assessment_questions` | `category_id`, `prompt`, `sort_order`, `is_active` | master admin |
| `assessment_options` | `question_id`, `label`, `sort_order`, kategori terkait bila perlu | opsi jawaban; **tidak memaksakan bobot skoring** |
| `assessment_drafts` / `assessment_answers` | `user_id`, `question_id`, `option_id`, waktu simpan | satu jawaban aktif per soal per akun; pertahankan draft lintas sesi |
| `student_academic_records` | `user_id`, `semester`, `course_id`, `grade`, keterangan opsional | input manual; nilai disimpan secara konsisten |
| `assessment_submissions` | `user_id`, `assessor_id` nullable, `status`, `submitted_at`, `published_at`, `published_by`, `revision_notes` | alur status resmi; pertimbangkan revisi/versioning/snapshot |
| `submission_answer_snapshots`, `submission_academic_snapshots` | `submission_id`, isi jawaban/data akademik saat submit | snapshot menjaga integritas selama review; boleh pakai JSON dengan validasi atau tabel relasional |
| `assessment_evaluations` | `submission_id` unique, `assessor_id`, `score` nullable, `evaluation`, `feedback`, `recommendations`, timestamps | draft evaluasi tidak tampak ke mahasiswa sebelum Published |
| `progress_checklist_items` | `title`, `description`, `sort_order`, `is_active` | daftar tugas yang bisa digunakan mahasiswa |
| `student_progress_checks` | `user_id`, `checklist_item_id`, `is_completed`, `completed_at` nullable | unique(user_id,checklist_item_id) |

**Detail implementasi data:**
- Buat constraint pencegah duplikasi favorit; pastikan penghapusan katalog yang direferensikan memakai soft delete, bukan cascade destruktif.
- Format grade KHS **belum dibakukan proposal**. Pilih satu skema input yang jelas dan dokumentasikan. Jangan menghitung IPK atau melakukan konversi nilai otomatis tanpa aturan resmi.
- Jika multi-submission/revisi diterapkan, rekam sejarah review tanpa kehilangan jejak perubahan. Jangan membuat hasil asesmen menjadi terlihat karena hanya punya `evaluation_id`; cek `status == PUBLISHED` di sisi server.
- Mekanisme *assignment* ke Assessor **belum dijelaskan secara operasional dalam proposal**. Pada tahap fitur M1, implementasikan mekanisme sederhana yang eksplisit (misalnya Admin menugaskan Assessor, atau penugasan otomatis terkontrol) dan dokumentasikan. Yang penting, Assessor tidak bebas mengakses seluruh pengajuan.

## 8. Non-functional requirements dan keamanan

**[PROPOSAL: NFR-01..06]** Performance, Security, Privacy, Usability, Data Integrity, Reliability.

**Checklist teknis [REKOMENDASI IMPLEMENTASI]:**
- Semua route yang memuat data pribadi menggunakan autentikasi; otorisasi menggunakan middleware + policy **server-side**, tidak hanya menyembunyikan tombol di UI.
- Lindungi user ownership (`user_id`) pada query dan update Action Plan, CV, KHS, answer, submission, progress, favorites. Cegah IDOR ketika user mengubah `{id}` di URL.
- Validasi server-side dengan Laravel Form Request; escape output untuk mencegah XSS; gunakan CSRF protection, password hashing Laravel, session cookie aman.
- Simpan rahasia di `.env`, sertakan `.env.example` tanpa credential; jangan commit `.env` atau data mahasiswa sebenarnya.
- Gunakan pagination/search pada daftar, transactions untuk operasi perubahan status dan submit, unique/index/foreign keys untuk integritas.
- Berikan feedback error yang jelas tanpa membocorkan data sensitif; dokumen PDF dan akses file sensitif harus dibatasi per pemilik.
- Render mobile/desktop, keyboard-friendly, label yang jelas dan kontras yang memadai.
- Penanganan jaringan gagal tidak boleh menghapus jawaban atau data yang sebelumnya tersimpan; tampilkan status penyimpanan.
- Proposal tidak menetapkan SLA angka tertentu; hindari klaim seperti "semua respons harus < 1 detik" sebagai requirement proposal.

## 9. Arsitektur implementasi yang direkomendasikan

- **Framework:** Laravel versi stabil terbaru yang kompatibel dengan PHP dan lingkungan eksekusi saat implementasi. Prioritaskan konvensi Laravel, bukan microservices.
- **Frontend:** Laravel Blade + Tailwind CSS + JavaScript/Alpine untuk aksi interaktif. Pilihan ini bukan kewajiban eksplisit proposal, tetapi meminimalkan kompleksitas.
- **Database:** MySQL dengan migration, Eloquent ORM, factories/seeders.
- **Authentication:** mekanisme bawaan Laravel / starter auth yang kompatibel; role middleware, policies, guards jika benar-benar perlu; tidak perlu sistem auth kustom.
- **Services:** pindahkan aturan domain yang cukup kompleks seperti `SubmitAssessment`, `PublishAssessment`, `GenerateCvPdf` dari controller ke service/action class. CRUD biasa tetap bisa controller resource.
- **PDF:** DOMPDF atau pustaka yang kompatibel, dengan PDF output dibangun dari server-owned data.
- **Storage:** filesystem Laravel untuk event poster; private disk untuk lampiran KHS jika fitur unggah ditambahkan. Untuk MVP, input nilai KHS terstruktur lebih utama daripada upload/parse dokumen.
- **Testing:** Laravel/PHPUnit atau Pest (pilih salah satu). Jalankan tests, formatter/lint, dan migration/seed.
- **Deployment:** aplikasi bisa dijalankan secara lokal. Deployment production belum ditentukan proposal; jangan menambahkan Firebase/Supabase/Node backend sebagai sumber data utama tanpa persetujuan.

## 10. Definisi siap dan kriteria penerimaan

### Acceptance test utama (harus otomatis jika memungkinkan)
1. Role Mahasiswa tidak bisa membuka route `/admin` atau `/assessor`.
2. Mahasiswa A tidak bisa mengambil/mengubah Action Plan, KHS, CV, progress, answers, favorites Mahasiswa B meskipun ID ditebak.
3. Admin bisa CRUD profesi; profesi aktif muncul di katalog mahasiswa; profesi nonaktif tidak tampil di katalog, tetapi tidak merusak favorites lama.
4. Search profesi dan event mengembalikan hasil sesuai keyword/filter; hasil kosong memunculkan empty state.
5. Favorite profesi tidak duplikat; user dapat menghapus favorite tanpa menghapus master.
6. Action Plan bisa tambah/edit/hapus, deadline tersimpan, status hanya salah satu dari tiga yang sah.
7. Event detail menampilkan tautan pendaftaran menuju domain eksternal; tidak ada form registrasi event internal.
8. CV preview berisi data terbaru milik akun login; PDF berhasil diunduh dan dapat dibuka.
9. Assessment draft tersimpan setelah logout-login; pengajuan yang belum lengkap ditolak dengan pesan jelas.
10. Setelah submit valid, status Under Review dan mahasiswa tidak bisa mengubah data yang dikunci.
11. Assessor berwenang bisa meminta revisi dengan catatan; mahasiswa memperbaiki, submit ulang; status kembali Under Review.
12. Hasil evaluasi draft tidak terlihat mahasiswa; publish hanya jika evaluasi/rekomendasi lengkap.
13. Setelah Published, mahasiswa pemilik melihat hasilnya, mahasiswa lain tidak; Admin hanya baca.
14. Checklist bisa dicentang/batal centang; persentase dan total tersimpan per mahasiswa dan dihitung dengan benar.
15. Semua form penting memiliki validasi gagal serta feedback berhasil.

### Definition of Done (per user story)
- Alur utama benar-benar menyimpan dan membaca data MySQL, bukan `localStorage`, hard-coded array atau mock endpoint.
- Tampilan, validasi, aksi server, izin role, migrasi/seed dan automated tests relevan tersedia.
- Tidak ada link/button mati, placeholder yang diklaim selesai, atau penggantian requirement dengan widget cantik tanpa fungsi.
- `README.md` berisi cara instal, setup MySQL, `.env`, migrasi, seed, test, dan login akun demo.
- Setelah satu pekerjaan selesai, kode dapat dijalankan, dibuktikan dengan hasil perintah dan daftar file berubah.

## 11. Delivery plan dan prioritas untuk Codex

### Phase 0 — Foundation (mulai di sini)
- Periksa kondisi repo. Jika kosong, inisialisasi satu project Laravel; jika sudah ada, adaptasi dan jangan overwrite kode pengguna.
- Konfigurasi MySQL + `.env.example`; migration User/Role; autentikasi login/logout; guard/middleware/policies.
- Layout dan dashboard per role, navigation, auth error/empty states, base UI responsive.
- Seeder untuk akun **demo development** (3 role), README, perintah test dan smoke test basic.
- Tidak mengerjakan assessment/event/CV kompleks pada fase ini.

**Exit criteria Phase 0:** Aplikasi bisa dibuka dari browser; Mahasiswa, Assessor, Admin dapat login/logout; user masuk ke dashboard sesuai role; route lintas role ditolak oleh server; migrasi dan seed jalan; basic tests lulus.

### Phase 1 — Sprint 1 (US-11..US-21)
- **P1a:** Admin profesi (US-11) → katalog mahasiswa (US-12) → Admin path/courses/skills/tools (US-13) → display detail path dan course (US-14) → simpan profesi (US-15).
- **P1b:** Admin template action plan (US-16) → Mahasiswa action plan CRUD/status (US-17).
- **P1c:** Admin events (US-18) → Mahasiswa events/filter/detail/link eksternal (US-19).
- **P1d:** CV form lengkap (US-20) → preview + valid PDF download (US-21).
- Lakukan tes per vertical slice, dan demo data.

### Phase 2 — Sprint 2 (US-01..US-10 dan US-22)
- Admin bank soal (US-01) dan mata kuliah/relasi tambahan (US-02).
- Draft assessment (US-03) dan input akademik/KHS (US-04) → validasi dan submit (US-05).
- Assessor queue/review (US-06), evaluasi/revisi (US-07), publikasi (US-08).
- Student result Published (US-09) dan Admin Published read-only (US-10).
- Progress tracking independen (US-22).
- End-to-end regression untuk perubahan status + keamanan data.

### Phase 3 — Final QA / Demo
- Validasi NFR, test role/ownership, bug fixing, mobile view, README, dokumentasi route/model dan demo scenario.
- Buat script seeding dataset tiruan untuk demonstrasi tanpa data nyata mahasiswa.

## 12. Hal yang memang belum diputuskan oleh proposal

Sebelum implementasi fitur yang bergantung padanya, buat keputusan kecil lalu catat dalam `docs/decisions.md`, tanpa mengarang seolah berasal dari proposal:

1. Mekanisme pendaftaran akun dan verifikasi mahasiswa aktif (tanpa integrasi kampus).
2. Kriteria/alur penugasan pengajuan kepada Assessor.
3. Format nilai KHS yang diterima, serta apakah ada upload berkas KHS tambahan.
4. Kategori/skor/rubrik Career Assessment. **Jangan otomatis menyimpulkan profesi tanpa formula yang disepakati**.
5. Apakah mahasiswa dapat membuat lebih dari satu riwayat submission assessment; minimal mempertahankan histori revisi dan Published.
6. Jumlah dan tampilan template CV versi pertama.
7. Katalog default checklist progress: aktivitas apa yang diinisialisasi.

**Aturan Codex:** Jika detail belum ada, ambil keputusan yang sederhana, reversible dan aman, tulis sebagai *assumption*, dan jangan memperbesar ruang lingkup. Untuk keputusan yang berdampak pada interpretasi hasil asesmen, hentikan pembuatan algoritme tersebut dan tetap gunakan input/penilaian manusia.

## 13. Referensi ke proposal yang dilampirkan

- **Bab 1.1–1.3:** latar, tujuan, enam fitur utama, batasan produk (halaman dokumen ±4–7).
- **Bab 2.1–2.3:** usulan solusi, As-Is vs To-Be (±8–14).
- **Bab 3.1.1:** KF-01..KF-11, US-01..US-22 dan Acceptance Criteria (±15–35).
- **Bab 3.1.2:** karakteristik dan hak akses ketiga role (±35–38).
- **Bab 3.2–3.3:** NFR-01..06 dan KT-01..08, PHP/Laravel/MySQL (±39–42).
- **Bab 4.1–4.3:** Use Case Scenario, ERD, Class Diagram (±43–54).
- **Bab 4.4:** Mockup layar tiap modul (±55–76).
- **Bab 4.5:** Agile, sprint backlog dan jadwal pengembangan (±77–88).

**Catatan konsistensi sumber:** Bagian 2.2 merangkum pengelompokan fitur dengan penyebutan yang sedikit berbeda dari Bab 1.3 dan daftar kebutuhan. PRD ini memakai enam kelompok produk dari Bab 1.3 serta menggunakan KF-01..11 dan US-01..22 sebagai acuan perilaku yang lebih rinci. Beberapa tabel karakteristik aktor mengandung fitur yang bersyarat (contoh pendaftaran event internal 'apabila tersedia'); requirement KF-09/US-19 secara eksplisit mengatakan registrasi event **eksternal**, sehingga implementasi mengikuti itu.
