# Perancangan Sistem Bel Sekolah Otomatis

## 1. Pendahuluan
Dokumen ini berisi alur perancangan (blueprint) untuk Sistem Bel Sekolah Otomatis. Sistem ini akan berjalan di atas infrastruktur XAMPP (Apache, MySQL, PHP) yang terinstal secara lokal dan dirancang sedemikian rupa agar mudah dipahami serta diimplementasikan oleh Junior Developer.

## 2. Arsitektur Sistem
Sistem ini menggunakan arsitektur Client-Server sederhana yang berjalan di satu mesin (localhost):
- **Server-Side (Backend):** Menggunakan PHP untuk menangani logika bisnis (CRUD ke database, upload file).
- **Database:** MySQL (bawaan XAMPP) untuk menyimpan data konfigurasi, jadwal, dan path/lokasi file audio.
- **Client-Side (Frontend & Player):** HTML, CSS, dan Vanilla JavaScript. JavaScript akan berfungsi sebagai "mesin waktu" (cron) yang berjalan di tab browser terbuka, bertugas mencocokkan waktu sistem komputer dengan waktu jadwal untuk kemudian memutar audio secara otomatis menggunakan HTML5 Audio API.

## 3. Desain Database (Schema)
Akan dibuat database dengan nama `db_bel_sekolah`. Berikut adalah rancangan tabel inti yang dibutuhkan:

### A. Tabel `sekolah` (Pengaturan Profil)
| Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| id | INT (PK, AI) | ID Pengaturan |
| nama_sekolah | VARCHAR(100) | Nama institusi sekolah |
| zona_waktu | VARCHAR(50) | Misalnya: 'Asia/Jakarta' |

### B. Tabel `suara` (Master Audio)
| Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| id | INT (PK, AI) | ID Suara |
| nama_suara | VARCHAR(100) | Label Suara (Misal: 'Bel Masuk', 'Bel Istirahat') |
| file_path | VARCHAR(255) | Lokasi file (.mp3/.wav) di dalam folder proyek |

### C. Tabel `jadwal` (Master Jadwal Bel)
| Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| id | INT (PK, AI) | ID Jadwal |
| hari | VARCHAR(20) | Hari berlakunya jadwal (Senin, Selasa, dll) |
| waktu | TIME | Jam bel berbunyi (misal 07:00:00) |
| kegiatan | VARCHAR(100) | Nama kegiatan (Masuk, Istirahat Pertama, Solat Dhuha, Pulang) |
| id_suara | INT (FK) | Relasi ke tabel `suara` |
| status | ENUM('aktif', 'nonaktif') | Untuk menghidupkan/mematikan jadwal tertentu |

## 4. Alur Kerja Aplikasi (Application Flow)

Aplikasi dibagi menjadi dua bagian utama: **Admin Panel** dan **Client Player**.

### 4.1. Admin Panel (Dashboard Pengaturan)
Admin Panel adalah antarmuka untuk staf sekolah/tata usaha mengelola bel.
1. **Kelola Sekolah:** Halaman untuk mengubah nama sekolah.
2. **Kelola Suara:** Halaman berisi form untuk mengunggah (upload) file audio (.mp3). File disimpan ke direktori `/uploads` dan path-nya dicatat ke dalam database.
3. **Kelola Jadwal:** Halaman utama berupa tabel CRUD (Create, Read, Update, Delete) untuk mengatur jam berapa bel bunyi. Admin bisa memilih hari, memasukkan jam (`<input type="time">`), menuliskan nama kegiatan, dan memilih jenis suara dari *dropdown* yang datanya diambil dari tabel `suara`.

### 4.2. Client Player (Mesin Bel Otomatis)
Client Player adalah halaman khusus (misal `index.php` atau `player.php`) yang harus selalu dalam keadaan terbuka di browser server TU/Piket.
1. Halaman menampilkan **Jam Digital** besar secara *real-time*.
2. **Logika JavaScript:**
   - Melakukan *fetch* (request) data jadwal hari ini ke backend PHP (`get_jadwal.php`).
   - Menggunakan fungsi `setInterval` (berjalan setiap 1 detik) untuk mengecek waktu komputer saat ini (`new Date()`).
   - Jika detik pada waktu komputer bernilai `00` (tepat pergantian menit), sistem mencocokkan *Jam:Menit* saat ini dengan *Jam:Menit* di data jadwal.
   - Jika ada yang **cocok (Match)**, JavaScript membuat objek audio (`new Audio(file_path)`) dari jadwal terkait dan memanggil perintah `.play()`.

---

## 5. Alur Pengerjaan (Task List) untuk Junior Developer
Berikut adalah urutan tugas yang dapat langsung di-eksekusi oleh *Junior Developer* secara bertahap:

### Tahap 1: Setup Lingkungan & Database
- [ ] Buka XAMPP, pastikan modul **Apache** dan **MySQL** berjalan.
- [ ] Buka phpMyAdmin (`http://localhost/phpmyadmin`).
- [ ] Buat database bernama `db_bel_sekolah`.
- [ ] Buat ketiga tabel (`sekolah`, `suara`, `jadwal`) sesuai dengan skema di atas.
- [ ] Buat struktur folder proyek (misal: `C:\xampp\htdocs\bel-sekolah`). Buat sub-folder: `assets`, `uploads`, dan `api`.

### Tahap 2: Backend (Koneksi & API)
- [ ] Buat file `koneksi.php` untuk menghubungkan PHP dengan MySQL. (Gunakan detail koneksi: Host `localhost`, Database `db_bel_sekolah`, Username `root`, dan Password `@Mongsidialok01`).
- [ ] Buat file `api/get_jadwal.php` yang mengembalikan data jadwal dalam format JSON (di-filter berdasarkan hari saat ini).

### Tahap 3: Halaman Admin (Manajemen Data)
- [ ] Buat antarmuka dasar dengan HTML/CSS (boleh pakai Bootstrap agar cepat).
- [ ] Buat fitur Upload Audio (CRUD Suara) yang menangani pemindahan file upload ke folder `/uploads`.
- [ ] Buat antarmuka CRUD Jadwal. Pastikan relasi *dropdown* pilihan suara berfungsi dan menyimpan ID suara dengan benar.

### Tahap 4: Halaman Player (Mesin Utama Bel)
- [ ] Buat halaman `index.php` (Tampilan Utama Bel).
- [ ] Buat jam digital dengan JavaScript yang memperbarui teks jam setiap detik di layar.
- [ ] Tarik data dari `api/get_jadwal.php` saat halaman dimuat menggunakan Fetch API / AJAX.
- [ ] Tambahkan logika komparasi waktu (`setInterval`). Jika waktu komputer == waktu jadwal, mainkan file mp3 yang sesuai.
- *Catatan Penting (Autoplay Policy): Browser modern memblokir audio yang diputar tanpa interaksi user. Buatlah tombol "Mulai Bel" di halaman ini yang harus diklik sekali oleh petugas agar audio bisa diputar otomatis selanjutnya.*

### Tahap 5: Pengujian (Testing)
- [ ] Buat jadwal bayangan (waktu diset 1-2 menit dari waktu sekarang).
- [ ] Biarkan halaman *Player* terbuka, perhatikan apakah bel berbunyi tepat waktu.
- [ ] Tes mengubah jadwal (Update waktu/suara), *reload* halaman player, dan pastikan perubahannya bekerja.

---
**Pesan Teknis untuk Junior Developer:**
Fokuslah pada kelancaran operasi CRUD (Create, Read, Update, Delete) di PHP terlebih dahulu. Setelah data bisa dikelola, barulah beralih ke logika JavaScript di halaman player. Pastikan format waktu (`H:i:s`) konsisten antara database PHP dan output di JavaScript. Semangat!
