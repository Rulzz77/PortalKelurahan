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
