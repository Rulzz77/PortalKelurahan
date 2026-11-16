# Portal Informasi Kelurahan — Laravel

Starter project portal informasi kelurahan dengan halaman publik dan dashboard administrator. Proyek ini memakai **Laravel 13 + Blade + Bootstrap 5 + CSS/JavaScript biasa**. Tidak memakai React, Vue, atau proses `npm build` untuk aset aplikasi.

> ZIP ini berisi source code aplikasi, bukan folder `vendor`. Setelah diekstrak, jalankan `composer install` untuk mengunduh dependensi Laravel.

## Fitur

### Website publik
- Beranda dengan akses cepat ke konten dan layanan.
- Profil, sejarah, visi/misi (isi profil, sejarah, visi, dan misi melalui pengaturan).
- Struktur organisasi bertingkat (atasan dan bawahan) beserta foto.
- Daftar layanan dengan detail persyaratan, prosedur, estimasi, dan informasi biaya.
- Berita dan pengumuman, termasuk pencarian berita.
- Galeri foto dengan pratinjau lightbox.
- Kontak dan formulir pesan warga.
- Peta kantor interaktif OpenStreetMap. Administrator dapat memilih koordinat dengan klik peta atau menggeser pin.

### Dashboard administrator
- Login administrator; tidak ada pendaftaran publik.
- Ringkasan jumlah konten dan pesan masuk.
- CRUD berita/pengumuman, upload poster/gambar, draft atau terbit.
- CRUD layanan kelurahan.
- CRUD perangkat dan struktur organisasi.
- CRUD foto galeri.
- Membaca dan menghapus pesan warga.
- Pengaturan nama kantor, logo, alamat, kontak, jam layanan, profil, media sosial, judul beranda, dan titik peta.
- Ganti nama dan password akun admin.

## Persyaratan

- PHP **8.3 atau lebih baru**.
- Composer 2.
- Ekstensi PHP umum Laravel: `ctype`, `curl`, `dom`, `fileinfo`, `filter`, `hash`, `mbstring`, `openssl`, `pcre`, `PDO`, `session`, `tokenizer`, dan `xml`.
- Untuk database bawaan SQLite: `pdo_sqlite` dan `sqlite3` aktif.
- Browser modern dan koneksi internet untuk Bootstrap Icons, Google Fonts, Leaflet, dan ubin peta OpenStreetMap.

## Instalasi cepat (Windows / Laragon)

1. Ekstrak ZIP ke folder proyek, contoh `C:\laragon\www\portal-kelurahan`.
2. Buka terminal di folder proyek.
3. Salin `.env.example` menjadi `.env` (PowerShell: `Copy-Item .env.example .env`). Jika menggunakan SQLite dan file `database/database.sqlite` belum ada, buat dengan PowerShell: `New-Item -ItemType File database/database.sqlite`. (Linux/macOS: `touch database/database.sqlite`.)
4. Jalankan perintah berikut satu per satu:

```bash
composer install
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
php artisan serve
```

5. Buka `http://127.0.0.1:8000` untuk website publik.
6. Buka `http://127.0.0.1:8000/admin/login` untuk login administrator.

### Akun admin awal (lokal)

Nilai bawaan dari `.env.example`:

- Email: `admin@kelurahan.test`
- Password: `GantiPassword!2026`

**Segera ganti password dari menu Admin → Akun Admin.** Sebelum server dipublikasikan, ganti juga `ADMIN_EMAIL`, `ADMIN_PASSWORD`, `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL`, dan konfigurasi database. Jangan memakai kredensial contoh di server publik.

## Menggunakan MySQL / MariaDB

Jika ingin memakai MySQL, ubah `.env` menjadi misalnya:

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=portal_kelurahan
DB_USERNAME=root
DB_PASSWORD=
```

Buat database `portal_kelurahan` terlebih dahulu, lalu jalankan:

```bash
php artisan migrate --seed
```

## Mengisi identitas dan data kelurahan

Setelah login admin:

1. Buka **Identitas & Peta** untuk memasukkan nama kelurahan, alamat kantor, nomor telepon, email, jam layanan, deskripsi beranda, profil, dan logo.
2. Pada peta, klik lokasi kantor yang benar. Pin dapat digeser; koordinat latitude dan longitude akan terisi otomatis. Tekan **Simpan Semua Pengaturan**.
3. Buka **Struktur Organisasi**. Tambahkan pimpinan terlebih dahulu, lalu tambahkan perangkat lain dan pilih atasan langsungnya. Jangan membuat hubungan atasan/bawahan melingkar.
4. Tambahkan daftar layanan beserta persyaratan dan prosedur sesuai ketentuan resmi kantor.
5. Buat berita atau pengumuman. Untuk poster, unggah gambar pada bagian Poster/Gambar Utama lalu terbitkan.
6. Tambahkan foto di Galeri Kegiatan.

Konten contoh dan identitas resmi tidak diisi secara otomatis agar tidak mengarang data kelurahan. Pastikan semua informasi layanan diverifikasi oleh pihak kantor sebelum dipublikasikan.

## Memperbarui source tanpa menghapus data

Jika ZIP ini dipakai untuk memperbarui instalasi yang sudah berjalan, cadangkan folder `database` dan `storage` terlebih dahulu. Timpa file source aplikasi, tetapi **jangan menimpa `.env`, `database/database.sqlite`, atau file upload di `storage/app/public`**. Setelah mengganti source, jalankan `php artisan optimize:clear`.

## Struktur folder penting

- `app/Models` — model database.
- `app/Http/Controllers` — controller publik dan admin.
- `app/Http/Middleware/EnsureAdmin.php` — proteksi dashboard admin.
- `database/migrations` — struktur tabel.
- `database/seeders/DatabaseSeeder.php` — pembuatan admin awal dan pengaturan dasar.
- `resources/views` — template Blade.
- `public/css/app.css` — stylesheet.
- `public/js/app.js` — interaksi browser.
- `routes/web.php` — rute website.

## Catatan peta dan privasi

Peta menggunakan Leaflet dan tile OpenStreetMap. Peta memerlukan internet dan ketentuan atribusi OpenStreetMap berlaku. Isi hanya koordinat kantor yang memang layak dipublikasikan. Jangan menyimpan NIK, nomor KK, alamat rumah pribadi, atau data sensitif warga di konten publik. Formulir kontak hanya untuk pertanyaan umum dan memiliki pembatasan laju pengiriman.

## Pemeriksaan source

File PHP pada source telah diperiksa menggunakan `php -l`. Instalasi dependensi Composer, migrasi database, dan pengujian browser perlu dijalankan di komputer yang memiliki Composer dan ekstensi database terkait; folder `vendor` memang tidak disertakan di ZIP.
