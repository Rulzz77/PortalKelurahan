# Catatan audit source Portal Kelurahan

Tanggal audit: 16 November 2026

## Temuan yang sudah diperbaiki
- Seeder admin tidak tersedia meski README meminta migrate --seed. Seeder idempoten sekarang membuat akun administrator dan pengaturan awal.
- AccountController membaca password opsional sebagai key wajib. Akses sudah diberi fallback null sehingga admin dapat mengganti nama tanpa mengganti password.
- Migration users menggunakan role admin sebagai default. Default instalasi baru diganti menjadi user; hanya seeder yang memberi role admin pada akun pertama.
- Penolakan login role non-admin kini membatalkan sesi dan meregenerasi token CSRF.
- Workflow memanggil PHPUnit secara langsung dan menggunakan Mockery sebagai dependency pengujian Laravel.

## Automated tests yang ditambahkan
- Autentikasi admin, guest redirect, penolakan akun non-admin, dan logout.
- Membuat service tanpa slug manual.
- Membuat berita terbit tanpa slug manual.
- Memperbarui nama akun tanpa mengirim password baru.
- Menyimpan pengaturan situs tanpa unggah logo.
- Upload foto galeri ke public disk.
- Mencegah relasi struktur organisasi membentuk siklus.
- Render halaman publik utama.
- Submit dan validasi formulir kontak.
- Seeder membuat admin awal dan tidak menimpa password admin yang sudah ada.

## Hasil CI yang terverifikasi
- GitHub Actions run: https://github.com/Rulzz77/PortalKelurahan/actions/runs/43822979987
- Status: success.
- PHP 8.3.36, PHPUnit 12.5.44.
- 15 tests passed, 56 assertions passed.

## Batas audit
Hasil di atas membuktikan test suite otomatis yang tersedia lulus pada lingkungan CI tersebut. Ini belum merupakan jaminan bebas bug, bukan pengujian browser/manual menyeluruh, dan bukan audit keamanan deployment penuh. composer.lock belum diperbarui dari lingkungan pembuatan ini; workflow sementara menjalankan composer update. Setelah Composer tersedia di komputer pengembang, jalankan composer update lalu commit composer.lock supaya versi dependency terkunci dan konsisten.


## Security & Quality Audit v2 — 16 November 2026

### Hardening added
- Added global response security headers: `X-Content-Type-Options`, `X-Frame-Options`, `Referrer-Policy`, `Permissions-Policy`, and `X-Permitted-Cross-Domain-Policies`.
- Added HSTS on HTTPS responses (without `includeSubDomains`).
- Admin responses now set `Cache-Control: private, no-store, max-age=0`.
- Production session cookies default to Secure when `APP_ENV=production`; local HTTP stays usable. Set `SESSION_SECURE_COOKIE=true` explicitly in HTTPS production.
- Password updates require at least 12 characters, mixed case, numbers, and symbols.
- Login input lengths are bounded; social profile URLs are restricted to HTTP/HTTPS.
- Upload replacement now stores the replacement before deleting the previous image. Database records are deleted before their associated public image files are removed.
- Added migration 000008 so existing databases also use `user` as the default role for newly inserted users.
- Added tests for login throttling, role defaults, protected mutations, weak password rejection, HTTPS-only social URLs, non-image upload rejection, image replacement/deletion, HTML escaping, and security headers.

### Remaining deliberate limitations
- Content Security Policy is not enabled yet because several current pages use inline scripts and third-party CDN assets. A correct CSP requires moving inline scripts to nonce-protected/external files and allowlisting the required asset and OpenStreetMap tile origins.
- Uploads are raster images only; SVG is intentionally disallowed.
- HSTS is emitted only when Laravel detects an HTTPS request. Reverse-proxy deployments must configure trusted proxies and HTTPS termination correctly.
- This audit and its tests do not replace production infrastructure review, dependency vulnerability monitoring, backup/restore testing, or manual browser testing.



### CI verification
- Latest complete pipeline: https://github.com/Rulzz77/PortalKelurahan/actions/runs/43824382975
- Result: success — 29 tests, 105 assertions, PHP 8.3.36 / PHPUnit 12.5.44.
- Composer audit: no security vulnerability advisories found.
- PHP syntax lint: all PHP files under app, bootstrap, config, database, lang, routes, and tests passed.
- Seeder now enforces initial admin passwords of at least 12 characters with uppercase, lowercase, number, and symbol.
