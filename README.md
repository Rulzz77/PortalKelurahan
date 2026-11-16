# Portal Informasi Kelurahan — Laravel

Starter project portal informasi kelurahan dengan halaman publik dan dashboard administrator. Proyek ini memakai Laravel 13 + Blade + Bootstrap 5 + CSS/JavaScript biasa. Tidak memakai React, Vue, atau proses npm build untuk aset aplikasi.

## Setup dan automated tests

Project memakai PHPUnit 12 dan SQLite in-memory untuk pengujian. Pastikan menggunakan PHP 8.3 atau yang lebih baru sesuai batas dependency, lalu instal versi paket yang sudah dikunci:

    composer install --no-interaction --prefer-dist

Jalankan test suite:

    composer test

Atau jalankan langsung:

    vendor/bin/phpunit

GitHub Actions memvalidasi `composer.json` dan `composer.lock`, memasang dependency dengan `composer install`, menjalankan `composer audit`, memeriksa sintaks PHP, dan menjalankan test suite saat push atau Pull Request ke branch main.

**Penting:** gunakan `composer install` untuk setup biasa agar versi dependency mengikuti `composer.lock`. Jalankan `composer update` hanya saat memang ingin memperbarui versi paket. Setelah update, periksa hasilnya dengan:

    composer validate --strict
    composer audit
    composer test

Cakupan tes meliputi autentikasi dan role admin, akses dashboard, CRUD layanan dan berita tanpa slug manual, update akun tanpa password baru, penyimpanan pengaturan, upload galeri, pencegahan struktur organisasi melingkar, render halaman publik, formulir kontak, pembuatan admin melalui seeder, validasi URL, dan performa query struktur organisasi.

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


The latest completed Security & Quality Audit v3 workflow passed **33 tests and 116 assertions** on PHP 8.3.36. Composer audit reported no known security advisories, and all checked PHP source files passed syntax lint. [View the successful GitHub Actions run](https://github.com/Rulzz77/PortalKelurahan/actions/runs/43825942879). The dependency lockfile has since been refreshed and the main workflow now uses `composer install` for reproducible dependency installation.
