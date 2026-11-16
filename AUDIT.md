# Catatan audit source Portal Kelurahan

Tanggal audit: 16 November 2026

## Temuan yang sudah diperbaiki
- Seeder admin tidak tersedia meski README meminta migrate --seed. Seeder idempoten sekarang membuat akun administrator dan pengaturan awal.
- AccountController membaca password opsional sebagai key wajib. Akses sudah diberi fallback null sehingga admin dapat mengganti nama tanpa mengganti password.
- Migration users menggunakan role admin sebagai default. Default instalasi baru diganti menjadi user; hanya seeder yang memberi role admin pada akun pertama.
- Penolakan login role non-admin kini membatalkan sesi dan meregenerasi token CSRF.
- Workflow awal memanggil php artisan test tanpa Collision. Sekarang workflow memanggil PHPUnit secara langsung.
- Test pertama membutuhkan Mockery melalui Laravel RefreshDatabase; Mockery sekarang menjadi dependency pengujian eksplisit.

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

## Batas audit
Status lulus/gagal tiap run harus dilihat pada tab Actions. Lingkungan pembuatan tidak memiliki akses jaringan ke Composer, jadi composer.lock belum bisa diperbarui dan pipeline menjalankan composer update. Belum ada klaim bahwa seluruh tes runtime sudah lulus.
