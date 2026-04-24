# Sistem Bel Sekolah Otomatis

<p align="center">
  <img src="https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP">
  <img src="https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL">
  <img src="https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black" alt="JavaScript">
  <img src="https://img.shields.io/badge/CSS-1572B6?style=for-the-badge&logo=css3&logoColor=white" alt="CSS">
  <img src="https://img.shields.io/badge/License-MIT-green?style=for-the-badge" alt="License">
</p>

---

## deskripsi

Sistem Bel Sekolah Otomatis adalah aplikasi berbasis web yang dirancang untuk membantu pengelolaan bel sekolah secara otomatis. Sistem ini memungkinkan sekolah untuk menjadwalkan kapan bel harus berbunyi berdasarkan hari dan waktu yang ditentukan, dengan fitur manajemen suara dan jadwal melalui panel admin yang intuitif.

Sistem ini sangat cocok untuk sekolah dasar, menengah, maupun atas yang ingin mengotomatiskan proses bel sekolah tanpa perlu menggunakan perangkat keras tambahan yang mahal.

---

## Teknologi yang Digunakan

| Teknologi | Deskripsi |
|-----------|----------|
| **PHP 7.4+** | Bahasa pemrograman server-side untuk logika backend |
| **MySQL** | Database untuk menyimpan data jadwal dan suara |
| **JavaScript (ES6+)** | Logika client-side untuk pemutaran bel otomatis |
| **HTML5/CSS3** | Tampilan modern dengan desain responsif |
| **Apache/Nginx** | Web server (disarankan XAMPP/WAMP) |

---

## Fitur

### Fitur Utama

- ✅ **Pemutaran Otomatis** - Bel berbunyi otomatis sesuai jadwal yang telah ditentukan
- ✅ **Manajemen Jadwal** - Tambah, edit, hapus jadwal dengan mudah lewat panel admin
- ✅ **Pustaka Suara** - Upload file audio (MP3, WAV, OGG) untuk berbagai jenis bel
- ✅ **Multi-Hari** - Jadwal berbeda untuk setiap hari dalam seminggu
- ✅ **Panel Admin** - Interface lengkap untuk mengelola sistem
- ✅ **Desain Modern** - Tampilan elegan dengan efek glassmorphism
- ✅ **Responsif** - Tampilan optimal di desktop maupun mobile
- ✅ **Real-time Clock** - Jam digital yang selalu akurat

### Fitur Tambahan

- 🔔 **Notifikasi Visual** - Status pemutaran bel tampak jelas di layar
- 🌙 **Dark Mode** - Tampilan gelap yang nyaman di mata
- 📱 **Mobile Friendly** - Bisa diakses dari smartphone

---

## Tampilan Antarmuka

### Halaman Utama (Player)

```
┌─────────────────────────────────────────────────────┐
│                    ⚙️ Admin                         │
│                                                     │
│              SISTEM BEL SEKOLAH                      │
│           Jumat, 24 April 2026                       │
│                                                     │
│  ╔═══════════════════════════════════════════════╗  │
│  ║           14:30:45                           ║  │
│  ╚═══════════════════════════════════════════════╝  │
│                                                     │
│  ┌───────────────────────────────────────────┐    │
│  │              STATUS BEL                     │    │
│  │   ● Bel Otomatis Sedang Aktif            │    │
│  │     [Mulai Bel Otomatis]               │    │
│  └──��────────────────────────────────────────┘    │
│                                                     │
│  ┌───────────────────────────────────────────┐    │
│  │           JADWAL HARI INI                  │    │
│  │   Jam Masuk          ████  07:00          │    │
│  │   Jam Kosong I       ████  09:00          │    │
│  │   Jam Pulang        ████  14:00          │    │
│  └───────────────────────────────────────────┘    │
└─────────────────────────────────────────────────────┘
```

### Panel Admin

```
┌──────────────┬────────────────────────────────────┐
│  Dashboard  │   Dashboard                       │
│  Suara     │   Total Jadwal: 15                │
│  Jadwal    │   Total Suara: 5                │
│             │                                   │
└──────────────┴────────────────────────────────────┘
```

---

## Struktur Proyek

```
WEB-Smart-Bell-Automation/
├── admin/
│   └── index.php         # Panel admin
├── api/
│   └── get_jadwal.php    # API mengambil data jadwal
├── assets/
│   ├── player.js        # Logika pemutaran bel
│   └── style.css       # Stylesheet tampilan
├── uploads/
│   └── audio/           # Folder penyimpanan audio
├── index.php           # Halaman utama player
├── koneksi.php        # Konfigurasi database
├── setup.sql         # Skrip setup database
└── README.md         # Dokumentasi ini
```

---

## Instalasi

### Prasyarat

- XAMPP (direkomendasikan) atau WAMP
- Web browser modern (Chrome, Firefox, Edge)
- File audio bel (MP3/WAV/OGG)

### Langkah Instalasi

#### 1. Clone atau Download Repo

```bash
git clone https://github.com/hendrazulpiadi/WEB-Smart-Bell-Automation.git
```

Atau download file ZIP dan extract ke folder `htdocs` (XAMPP) atau `www` (WAMP).

#### 2. Setup Database

1. Buka **phpMyAdmin** (`http://localhost/phpmyadmin`)
2. Buat database baru dengan nama: `db_bel_sekolah`
3. Klik tab **Import**
4. Pilih file `setup.sql` yang ada di folder project
5. Klik **Go** untuk import

#### 3. Konfigurasi Koneksi Database

Buka file `koneksi.php` dan sesuaikan konfigurasi:

```php
<?php
$host = "localhost";      // Host database
$user = "root";        // Username (default: root)
$pass = "";            // Password (sesuaikan dengan settings XAMPP)
$db   = "db_bel_sekolah"; // Nama database
?>
```

> **Catatan:** Jika menggunakan XAMPP default, password biasanya kosong. Untuk WAMP, password default `@Mongsidialok01` sesuai konfigurasi di `koneksi.php`.

#### 4. Buat Folder Uploads

Buat folder `uploads/audio` jika belum ada:

```bash
mkdir uploads/audio
```

#### 5. Jalankan Aplikasi

1. Pastikan Apache dan MySQL sudah running di XAMPP/WAMP
2. Buka browser dan akses: `http://localhost/WEB-Smart-Bell-Automation/`
3. Halaman utama akan muncul

#### 6. Setup Pertama (Opsional)

1. Buka panel admin: `http://localhost/WEB-Smart-Bell-Automation/admin/`
2. **Upload Suara**: Upload file audio bel sesuai kebutuhan
3. **Tambah Jadwal**: Tambahkan jadwal bel untuk setiap hari
4. Kembali ke halaman utama dan klik **Mulai Bel Otomatis**

---

## Cara Penggunaan

### Menggunakan Sistem

1. **Buka Halaman Utama** - Akses `index.php`
2. **Aktifkan Pemutaran** - Klik tombol "Mulai Bel Otomatis"
3. **Biarkan Tab Terbuka** - Pastikan tab tidak ditutup agar bel otomatis berjalan
4. **Tutup Browser Tidak Masalah** - Tab bisa diminimize, yang penting tidak ditutup

### Mengelola Jadwal (Admin)

1. Klik tombol ⚙️ Admin di pojok kanan atas
2. Pilih menu:
   - **Pustaka Suara**: Upload file audio baru
   - **Atur Jadwal**: Tambah/edit/hapus jadwal

---

## Troubleshooting

### Bel Tidak Berbunyi

- **Cek apakah MySQL running** - Pastikan MySQL aktif di XAMPP
- **Cek file audio** - Pastikan file audio sudah diupload dan path benar
- **Cek jadwal** - Pastikan jadwal berstatus "aktif"
- **Cek browser** - Jangan gunakan Incognito mode

### Gagal Koneksi Database

- **Cek username/password** - Sesuaikan di `koneksi.php`
- **Cek nama database** - Pastikan `db_bel_sekolah` sudah dibuat
- **Cek MySQL service** - Restart Apache dan MySQL

### Audio Tidak Bisa Dimainkan

- **Format tidak didukung** - Gunakan format MP3, WAV, atau OGG
- **File corrupted** - Upload ulang file audio
- **Browser blocking** - Klik "Mulai Bel" terlebih dahulu

---

## Lisensi

Proyek ini dilisensikan di bawah **MIT License** - bebas digunakan dan dimodifikasi.

---

## Kontribusi

Silakan fork repo ini dan buat pull request jika ingin berkontribusi!

---

## Credits

- Dibuat dengan ❤️ oleh [hendrazulpiadi](https://github.com/hendrazulpiadi)
- Diinspirasi dari kebutuhan pengelolaan bel sekolah tradisional

---

<p align="center">Made with passion for education.</p>