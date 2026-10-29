# Landing page, autentikasi, dan Beranda

Frontend memakai Laravel Blade, CSS, dan JavaScript native. Tidak memerlukan npm, Vite, atau framework frontend tambahan. Landing page menggunakan `public/css/auth.css` dan `public/js/auth.js`; layout aplikasi menggunakan `public/css/app.css` dan `public/js/app.js`.

## Menjalankan

Gunakan konfigurasi database yang sudah tersedia di `.env`. Untuk instalasi baru, jalankan `composer install`, salin `.env.example` ke `.env`, isi kredensial MySQL, lalu jalankan `php artisan key:generate` dan `php artisan migrate --seed`.

```powershell
php artisan serve
```

Buka `http://127.0.0.1:8000/` untuk landing page dengan login, `/login` untuk URL login, atau `/register` untuk registrasi. Landing page tetap dapat dibuka tanpa koneksi database selama session menggunakan driver `file` seperti `.env.example`. Login dan registrasi memerlukan database yang telah dimigrasikan dan role yang telah di-seed.

## Perilaku

- Form browser menggunakan `POST /login`, `POST /register`, dan `POST /logout` dengan CSRF protection dan session Laravel. API Sanctum `/api/auth/*` tetap tersedia untuk klien API.
- “Ingat saya” memakai remember cookie Laravel. Token API tidak disimpan di localStorage maupun sessionStorage.
- Registrasi hanya membuat mahasiswa dan memakai aturan validasi backend yang sama dengan registrasi API. Nama lengkap, email, kata sandi, dan konfirmasi kata sandi wajib diisi.
- JavaScript menambahkan validasi inline, toggle visibilitas kata sandi, loading state, dan pesan koneksi/error backend. Form tetap berfungsi melalui POST dan redirect ketika JavaScript dinonaktifkan.
- Login atau registrasi berhasil mengarahkan pengguna ke `/home`. Mahasiswa, Admin, dan Assessor memakai satu layout Beranda dan welcome card yang sama. Pengguna yang sudah masuk juga diarahkan ke Beranda ketika membuka `/`, `/login`, atau `/register`. Logout mengembalikan pengguna ke landing page.
- Reset password belum tersedia sehingga tidak ada tautan “Lupa kata sandi?”.
- Header, hero, dan card dibagikan lewat layout Blade; input, compass, dan arrow menggunakan komponen kecil. Tidak ada font atau aset yang wajib diunduh dari layanan eksternal.

## Beranda dan navigasi berdasarkan role

`GET /home` dilindungi middleware `auth:web` dan `role:mahasiswa,admin,assessor`. Nama, email, dan role berasal dari pengguna yang sedang login. Avatar memakai inisial nama; tidak ada foto pribadi atau NIM yang ditambahkan ke halaman.

`resources/views/layouts/app.blade.php` adalah satu layout untuk seluruh role. Topbar, sidebar, profil pengguna, dan welcome card berada di `resources/views/components/app/`. Logo SVG pada welcome card mengikuti referensi Career Compass yang diberikan, dengan compass yang sudah tersedia tetap digunakan pada topbar.

Seluruh menu didefinisikan di `config/navigation.php`. `App\Support\Navigation` hanya mengaktifkan item jika named route web dengan HTTP method yang sesuai benar-benar tersedia. Route API tidak dijadikan tautan halaman. Menu yang belum tersedia ditampilkan sebagai teks `aria-disabled="true"` dengan label **Segera Hadir**, tanpa `href`.

Frontend US-11 tersedia di `/admin/professions` dan terhubung ke menu **Manajemen Profesi** Admin. Frontend US-12 tersedia di `/career-exploration` (named route `careers.index`) dan terhubung ke menu **Career Exploration & Career Path** Mahasiswa. Daftar lengkap berada di `/professions`. Konfigurasi menu bukan pengganti authorization.

Sidebar dapat ditutup pada desktop dan pilihannya disimpan di localStorage hanya sebagai preferensi UI. Pada layar hingga 900 px, sidebar menjadi drawer dengan backdrop, focus trap, tombol tutup, serta dukungan Escape. Dropdown profil menampilkan nama, email, role, dan POST logout. Beranda tetap berupa welcome card tanpa statistik atau grafik.

## US-11 — Admin mengelola profesi

Jalankan `php artisan migrate` sebelum memakai editor baru. Migrasi `2026_10_27_000001_extend_professions_for_admin_editor` menambahkan field ringkasan, ilustrasi, profil/prospek karier, industri, tools, dan status publikasi. Kategori, deskripsi, dan tanggung jawab dapat kosong pada draf; seluruh isian wajib divalidasi saat penerbitan. Data lama tetap berstatus terbit dengan nilai `is_active` semula, dan metadata tambahan awalnya kosong.

- `/admin/professions`: daftar, pencarian nama, filter status, pagination, dan penonaktifan.
- `/admin/professions/create`: tambah profesi.
- `/admin/professions/{id}`: detail seluruh isian yang disimpan.
- `/admin/professions/{id}/edit`: ubah, simpan draf, terbitkan, atau terbitkan ulang profesi nonaktif.

Keempat bagian form mengikuti low-fi: **Informasi dasar**, **Profil dan prospek karier**, **Tanggung jawab dan kompetensi**, serta **Tools / teknologi**. Mata kuliah dan tahapan jalur karier tidak dibuat. Rentang jenjang pada profil merupakan metadata profesi, bukan CRUD Career Path. Kompetensi dipilih dari master yang sudah ada; editor ini tidak membuat master kompetensi baru.

Nama profesi wajib dan unik, termasuk pada draf. Penerbitan memerlukan kategori, ringkasan maksimal 200 karakter, deskripsi lengkap, ilustrasi, tingkat/jenjang, industri, minimal satu prospek, tanggung jawab, dan kompetensi. Tools opsional; jika ditambahkan saat penerbitan, nama dan kegunaannya wajib. Ilustrasi menerima PNG/JPG maksimal 2 MB; logo tool opsional maksimal 1 MB. Gambar disimpan di disk `local` dan ditampilkan melalui route Admin yang terautentikasi, sehingga tidak memerlukan `storage:link`.

Simpan Draf membuat profesi tidak tampil di katalog mahasiswa. Penonaktifan mempertahankan baris profesi, pivot kompetensi, dan berkas. Penggantian gambar atau penghapusan tool membersihkan berkas lama sesudah penyimpanan berhasil. Data profesi dan relasinya disimpan dalam transaksi.

Integrasi backend mempertahankan endpoint REST yang sudah ada. Model dan skema diperluas untuk isian low-fi; query/policy katalog mahasiswa mengecualikan draf, dan resource API Admin menangani kategori kosong pada draf. Kontrak field inti API lama tetap berlaku. Metadata editor tambahan ditampilkan pada frontend Admin dan detail profesi Mahasiswa di US-12.

Seluruh route editor, gambar, dan logo dilindungi `auth:web`, `role:admin`, serta policy profesi. Mahasiswa dan Assessor tidak dapat mengakses atau mengubah data lewat editor ini. Tidak ada perubahan pada autentikasi atau penilaian assessment.

## US-12 — Mahasiswa menjelajahi profesi

- `/career-exploration`: halaman eksplorasi dengan pencarian, kategori dari master data, dan tiga pilihan profesi. Pilihan ini merupakan daftar umum berdasarkan nama, bukan rekomendasi personal atau hasil assessment.
- `/professions`: katalog dengan pencarian nama (`keyword`), filter kategori (`category`), dan pagination. Ukuran halaman default 10, dapat diatur dengan `per_page` dalam rentang 1–100.
- `/professions/{id}`: detail profesi. Parameter `tab` memilih `overview`, `responsibilities`, `competencies`, `path`, `courses`, atau `tools`.

Ketiga halaman memakai shared layout aplikasi, Blade, dan CSS `public/css/careers.css`. Navigasi pencarian, kategori, pagination, dan bagian detail memakai GET biasa sehingga tetap berfungsi tanpa JavaScript. Kata kunci dan kategori tetap dibawa saat berganti halaman serta kembali dari detail.

Hanya profesi dengan `is_active=true` dan `publication_status=published` yang dapat dilihat. Draf dan profesi nonaktif mengembalikan 404 pada detail, ilustrasi, serta logo tool. Semua route US-12 dilindungi `auth:web`, `role:mahasiswa`, dan policy profesi; tidak ada kewajiban menyelesaikan assessment. Endpoint API dan editor Admin tidak diubah oleh fitur ini, dan tidak ada migrasi baru.

Konten detail mengambil deskripsi, prospek, industri, tanggung jawab, kompetensi, ilustrasi, dan tools dari data US-11. Jika ilustrasi belum tersedia, kartu menampilkan ikon visual lokal sesuai bidang profesi. Logo tool yang belum tersedia memakai ikon netral, tanpa mengambil logo dari sumber eksternal. Gambar tersimpan di disk `local` dilayani lewat route khusus mahasiswa yang memeriksa visibilitas profesi pada setiap request.

Bagian **Mata Kuliah** menampilkan **Data belum tersedia**. **Jalur Karier** menampilkan rentang jenjang awal–akhir jika telah diisi Admin; tahapan jabatan dan durasi pengalaman belum tersedia dan tidak dibuat-buat. Fitur simpan/favorit pada low-fi tidak ditambahkan karena berada di user story terpisah. Data lama yang belum memiliki metadata tambahan tetap bisa dibaca dengan empty state yang jelas.

## Pengujian

```powershell
php artisan test
php artisan view:cache
vendor/bin/pint --test
```

`WebAuthenticationTest` memeriksa autentikasi browser; `HomeTest` memeriksa layout, role, dan navigasi; `AdminProfessionWebTest` memeriksa editor US-11; `CareerExplorationWebTest` memeriksa pencarian/filter/pagination, konten dan empty state US-12, visibilitas draf, akses media, authorization, serta escaping output. Pengujian cepat memakai SQLite in-memory sesuai `phpunit.xml`.

Implementasi session mengikuti dokumentasi resmi [authentication Laravel](https://laravel.com/docs/13.x/authentication) dan [CSRF protection](https://laravel.com/docs/13.x/csrf).
