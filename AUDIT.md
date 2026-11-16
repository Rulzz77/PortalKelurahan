# Catatan audit source Portal Kelurahan

Tanggal: 16 November 2026

## Perbaikan pada paket ini

- Memperbaiki `ServiceController`: field `slug` dan `icon` kini aman ketika tidak dikirim. Saat edit, slug layanan lama dipertahankan jika tidak diberikan.
- Memperbaiki `PostController`: slug berita/pengumuman tidak lagi diakses sebagai array key wajib; URL lama dipertahankan saat judul diedit tanpa slug baru.
- Menyamakan validasi panjang `office_address` (maksimal 255 karakter) dengan kolom database `VARCHAR(255)`.
- Menampilkan pesan validasi pada layout publik, termasuk halaman formulir kontak.
- Tidak menyertakan database SQLite kosong pada ZIP agar proses ekstraksi pembaruan tidak menimpa database lokal yang sudah berisi data.

## Pemeriksaan yang dilakukan

- Seluruh file PHP pada folder `app`, `bootstrap`, `config`, `database`, `lang`, `routes`, dan `public` diperiksa dengan `php -l`.
- Referensi nama route pada Blade dicocokkan dengan route eksplisit dan nama route resource.
- Nama field pada form admin dibandingkan dengan validasi controller.
- Relasi model, nama tabel/kolom, upload file, dan middleware dashboard diperiksa secara statis.

## Batas pemeriksaan

Pemeriksaan runtime Laravel belum dapat dilakukan di lingkungan audit ini karena Composer, ekstensi PDO SQLite, dan dependensi `vendor` tidak tersedia. Karena itu, migrasi, login melalui browser, operasi CRUD, dan rendering Blade tetap perlu diuji di komputer yang menjalankan proyek. Ini bukan klaim bahwa aplikasi sudah lulus pengujian end-to-end.
