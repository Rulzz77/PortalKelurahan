# Portal Informasi Kelurahan — Laravel

Starter project portal informasi kelurahan dengan halaman publik dan dashboard administrator. Proyek ini memakai Laravel 13 + Blade + Bootstrap 5 + CSS/JavaScript biasa. Tidak memakai React, Vue, atau proses npm build untuk aset aplikasi.

## Menjalankan automated tests

Test suite menggunakan PHPUnit 12 dan SQLite in-memory. Jalankan dari folder proyek:

    composer update
    composer test

Atau jalankan langsung:

    vendor/bin/phpunit

GitHub Actions menjalankan tes otomatis saat push atau Pull Request ke branch main. Run terakhir pada 16 November 2026 lulus: 15 tests, 56 assertions, menggunakan PHP 8.3.36 dan PHPUnit 12.5.44. Lihat tab Actions di repository untuk hasil run terbaru.

Pipeline sementara menjalankan composer update untuk menyelesaikan dependency PHPUnit dan Mockery karena composer.lock pada repository belum diperbarui dari lingkungan pembuatan ini. Setelah menjalankan composer update di komputer pengembang, commit composer.lock agar dependency terkunci dan konsisten.

Cakupan tes meliputi autentikasi dan role admin, akses dashboard, CRUD layanan dan berita tanpa slug manual, update akun tanpa password baru, penyimpanan pengaturan, upload galeri, pencegahan struktur organisasi melingkar, render halaman publik, formulir kontak, serta pembuatan admin melalui seeder.

Tes otomatis bukan pengganti uji penerimaan pengguna, audit keamanan deployment, atau verifikasi data resmi kelurahan.


## Security hardening

- Global security headers: nosniff, same-origin framing, strict referrer policy, and restricted browser permissions.
- Responses from authenticated admin pages are marked private and no-store.
- The session cookie can be configured as Secure. Keep `SESSION_SECURE_COOKIE=false` for local HTTP development; set it to `true` when the production site is served over HTTPS.
- New passwords must contain at least 12 characters, uppercase and lowercase letters, numbers, and symbols.
- Social media links accept only HTTP/HTTPS URLs.
- Image uploads accept raster images only (JPG, PNG, WebP) and are size-limited. SVG uploads are not accepted.
- User-provided article and profile text is escaped on output; this portal intentionally does not render raw user HTML.
- Admin pages are protected by role middleware; login and public contact form have request throttling.

A Content-Security-Policy header is intentionally not enabled yet because the current Blade pages contain inline JavaScript and load styles/scripts from specific CDNs. Add CSP only after moving inline scripts to nonce-protected or external assets and explicitly allow the required tile/font/CDN origins; a generic restrictive CSP would break the map and page interactions.


The latest completed Security & Quality Audit v2 workflow run before the seeder password-policy addition passed **28 tests and 104 assertions** on PHP 8.3.36. The latest commit adds an additional regression test that requires a strong admin password from environment configuration.
