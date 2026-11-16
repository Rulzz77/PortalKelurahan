# Portal Informasi Kelurahan — Laravel

Starter project portal informasi kelurahan dengan halaman publik dan dashboard administrator. Proyek ini memakai Laravel 13 + Blade + Bootstrap 5 + CSS/JavaScript biasa. Tidak memakai React, Vue, atau proses npm build untuk aset aplikasi.

## Menjalankan automated tests

Test suite menggunakan PHPUnit 12 dan SQLite in-memory. Jalankan dari folder proyek:

    composer update
    php artisan test

GitHub Actions menjalankan tes otomatis saat push atau Pull Request ke branch main. Pipeline sementara menjalankan composer update untuk menyelesaikan dependency PHPUnit karena composer.lock pada repository belum diperbarui dari lingkungan pembuatan ini. Setelah menjalankan composer update di komputer pengembang, commit composer.lock agar versi dependency terkunci dan konsisten.

Cakupan tes meliputi autentikasi dan role admin, akses dashboard, CRUD layanan dan berita tanpa slug manual, update akun tanpa password baru, penyimpanan pengaturan, upload galeri, pencegahan struktur organisasi melingkar, render halaman publik, formulir kontak, serta pembuatan admin melalui seeder.

Tes otomatis bukan pengganti uji penerimaan pengguna, audit keamanan deployment, atau verifikasi data resmi kelurahan.
