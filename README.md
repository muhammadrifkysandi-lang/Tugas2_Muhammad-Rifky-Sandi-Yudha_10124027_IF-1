# Tugas2_Muhammad-Rifky-Sandi-Yudha_10124027_IF-1
Tugas 2 - Membuat Sistem Informasi Manajemen Data Sederhana_Apotek (FEFO)

---

## 👨‍💻 Identitas Mahasiswa
* **Nama**: Muhammad Rifky Sandi Yudha
* **NIM**: 10124027
* **Kelas**: IF-1

---

## ✨ Fitur Utama
1. **Sistem Otentikasi & Keamanan (Session)**
   - Proteksi halaman CRUD menggunakan `$_SESSION` agar tidak dapat diakses tanpa login.
   - Enkripsi kata sandi menggunakan `password_verify()` / `password_hash()` (bcrypt).
   - Sanitasi input menggunakan `htmlspecialchars()` dan `filter_var()` untuk mencegah serangan XSS.
   - Menggunakan **PDO Prepared Statements** dengan *named parameters* untuk mencegah *SQL Injection*.
2. **Modul Pengolahan Data Obat (CRUD & FEFO)**
   - **Create**: Menambah data obat baru dengan validasi form POST.
   - **Read (FEFO)**: Menampilkan tabel obat yang otomatis diurutkan berdasarkan `tanggal_kedaluwarsa ASC` (mendorong obat yang hampir *expired* ke atas). Dilengkapi *badge* peringatan untuk obat H-30 kedaluwarsa.
   - **Update**: Mengubah data obat lama berdasarkan identitas unik (ID).
   - **Delete**: Menghapus data obat dengan konfirmasi *JavaScript alert*.
3. **Fitur Dual Bahasa (ID / EN)**
   - Pengaturan pilihan bahasa antarmuka (Bahasa Indonesia & English) yang disimpan persisten di dalam `$_SESSION['lang']`.
4. **Desain Antarmuka Modern**
   - Menggunakan gaya **Glassmorphism** (CSS Native) yang responsif, bersih, dan estetis tanpa bantuan *framework* CSS eksternal.

---

## 🛠️ Teknologi yang Digunakan
* **Language**: PHP 8.x (Native / Pure)
* **Database**: MySQL / MariaDB (via PDO Driver)
* **Frontend**: HTML5, CSS3 (Glassmorphism & Flexbox/Grid), JavaScript Native
* **Font**: Quicksand (Google Fonts)

---

## 📂 Struktur Berkas Proyek

```text
apotek-fefo/
├── Database/
│   └── db_apotek.sql                   # File ekspor database MySQL
├── Tampilan HTML dan Proses PHP/
│   ├── bahasa.php                      # Kamus terjemahan & logika Session bahasa (ID/EN)
│   ├── koneksi.php                     # Konfigurasi koneksi PDO MySQL (Try-Catch)
│   ├── login.php                       # Tampilan Form Login & Pemrosesan Autentikasi
│   ├── logout.php                      # Logika penghancuran session (session_destroy)
│   ├── index.php                       # Dasbor Utama (Read & Tampilan FEFO)
│   ├── tambah.php                      # Tampilan Form & Pemrosesan Create Data Obat
│   ├── edit.php                        # Tampilan Form & Pemrosesan Update Data Obat
│   └── hapus.php                       # Logika Pemrosesan Delete Data Obat
└── README.md                           # Dokumentasi Repositori

---

🚀 Cara Menjalankan Aplikasi di Lokal (XAMPP)
1. Clone / Unduh Repositori
   Unduh proyek ini dan simpan di dalam folder htdocs XAMPP Anda:
   D:/xampp/htdocs/apotek-fefo
   
2. Import Database
   * Buka browser dan jalankan phpMyAdmin (http://localhost/phpmyadmin).
   * Buat database baru dengan nama db_apotek.
   * Klik tab Import, pilih file Database/db_apotek.sql, lalu klik Go.
     
3. Akses Aplikasi
   * Buka browser dan kunjungi:
     http://localhost/Tugas2_Muhammad-Rifky-Sandi-Yudha_10124027_IF-1-main/apotek-fefo/Tampilan_HTML_dan_Proses_PHP/login.php
  
4. Kredensial Login Default
   * Username: admin
   * Password: password
