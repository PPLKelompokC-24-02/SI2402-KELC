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

Frontend US-11 dan US-12 belum tersedia pada saat integrasi ini dibuat. Menu Manajemen Profesi dan Career Exploration karena itu dinonaktifkan. Tidak ada perubahan pada endpoint, controller API, policies, atau database profesi. Saat frontend tersedia, gunakan named route `admin.professions.index` untuk `/admin/professions` dan `careers.index` untuk `/professions`, atau sesuaikan nama route di konfigurasi. Lindungi route tersebut dengan autentikasi dan role/policy masing-masing; konfigurasi menu bukan pengganti authorization.

Sidebar dapat ditutup pada desktop dan pilihannya disimpan di localStorage hanya sebagai preferensi UI. Pada layar hingga 900 px, sidebar menjadi drawer dengan backdrop, focus trap, tombol tutup, serta dukungan Escape. Dropdown profil menampilkan nama, email, role, dan POST logout. Tidak ada statistik, tabel ringkasan, grafik, atau fitur baru di luar Beranda.

## Pengujian

```powershell
php artisan test
php artisan view:cache
vendor/bin/pint --test
```

`WebAuthenticationTest` memeriksa render halaman, validasi, session login, remember cookie, registrasi mahasiswa, penolakan perubahan role, fallback tanpa JavaScript, rate limit, dan logout. `HomeTest` memeriksa redirect seluruh role, layout yang sama, isolasi menu, middleware, navigasi yang aman, dan logout. Pengujian berjalan menggunakan SQLite in-memory sesuai `phpunit.xml`.

Implementasi session mengikuti dokumentasi resmi [authentication Laravel](https://laravel.com/docs/13.x/authentication) dan [CSRF protection](https://laravel.com/docs/13.x/csrf).
