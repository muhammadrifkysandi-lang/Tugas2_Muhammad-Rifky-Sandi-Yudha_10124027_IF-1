<?php
// bahasa.php

// Memulai session jika belum dimulai[cite: 97]
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Mengecek jika ada request pergantian bahasa melalui parameter GET (?lang=id atau ?lang=en)
if (isset($_GET['lang'])) {
    $lang = $_GET['lang'];
    // Validasi input agar hanya id atau en yang diterima
    if ($lang === 'id' || $lang === 'en') {
        $_SESSION['lang'] = $lang; // Menyimpan bahasa ke session[cite: 97]
    }
}

// Menentukan bahasa default (Indonesia) jika session belum tersetting[cite: 99]
if (!isset($_SESSION['lang'])) {
    $_SESSION['lang'] = 'id';
}

$active_lang = $_SESSION['lang'];

// Array kamus (dictionary) untuk menyimpan terjemahan
$kamus = [
    'id' => [
        'app_title' => 'Apotek (FEFO)',
        'login' => 'Masuk',
        'username' => 'Nama Pengguna',
        'password' => 'Kata Sandi',
        'btn_login' => 'Masuk Aplikasi',
        'logout' => 'Keluar',
        'dashboard' => 'Dasbor Obat (FEFO)',
        'add_data' => 'Tambah Data Obat',
        'edit_data' => 'Ubah Data',
        'delete' => 'Hapus',
        'med_code' => 'Kode Obat',
        'med_name' => 'Nama Obat',
        'category' => 'Kategori',
        'price' => 'Harga',
        'stock' => 'Stok',
        'exp_date' => 'Tgl Kedaluwarsa',
        'action' => 'Aksi',
        'save' => 'Simpan',
        'back' => 'Kembali',
        'lang_id' => 'Indonesia',
        'lang_en' => 'English',
        'err_empty' => 'Data tidak boleh kosong!',
        'err_auth' => 'Username atau password salah!'
    ],
    'en' => [
        'app_title' => 'Pharmacy (FEFO)',
        'login' => 'Login',
        'username' => 'Username',
        'password' => 'Password',
        'btn_login' => 'Login to App',
        'logout' => 'Logout',
        'dashboard' => 'Medicine Dashboard (FEFO)',
        'add_data' => 'Add Medicine Data',
        'edit_data' => 'Edit Data',
        'delete' => 'Delete',
        'med_code' => 'Medicine Code',
        'med_name' => 'Medicine Name',
        'category' => 'Category',
        'price' => 'Price',
        'stock' => 'Stock',
        'exp_date' => 'Expiration Date',
        'action' => 'Action',
        'save' => 'Save',
        'back' => 'Back',
        'lang_id' => 'Indonesia',
        'lang_en' => 'English',
        'err_empty' => 'Data cannot be empty!',
        'err_auth' => 'Invalid username or password!'
    ]
];

// Fungsi bantuan untuk memanggil teks sesuai bahasa yang aktif dengan mudah
function trans($key) {
    global $kamus, $active_lang;
    return isset($kamus[$active_lang][$key]) ? $kamus[$active_lang][$key] : $key;
}
?>