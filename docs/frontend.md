# Landing page dan autentikasi web

Frontend memakai Laravel Blade, CSS, dan JavaScript native. Tidak memerlukan npm, Vite, atau framework frontend tambahan. CSS dan JavaScript dilayani langsung dari `public/css/auth.css` dan `public/js/auth.js`.

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
- Login atau registrasi berhasil mengembalikan pengguna ke landing page. Card menampilkan status autentikasi dan tombol keluar. Halaman dashboard dan fitur lain belum ditambahkan.
- Reset password belum tersedia sehingga tidak ada tautan “Lupa kata sandi?”.
- Header, hero, dan card dibagikan lewat layout Blade; input, compass, dan arrow menggunakan komponen kecil. Tidak ada font atau aset yang wajib diunduh dari layanan eksternal.

## Pengujian

```powershell
php artisan test
php artisan view:cache
vendor/bin/pint --test
```

`WebAuthenticationTest` memeriksa render halaman, validasi, session login, remember cookie, registrasi mahasiswa, penolakan perubahan role, fallback tanpa JavaScript, rate limit, dan logout. Pengujian berjalan menggunakan SQLite in-memory sesuai `phpunit.xml`.

Implementasi session mengikuti dokumentasi resmi [authentication Laravel](https://laravel.com/docs/13.x/authentication) dan [CSRF protection](https://laravel.com/docs/13.x/csrf).
