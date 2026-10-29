<?php
// logout.php
session_start(); // Wajib dipanggil untuk mengakses session saat ini[cite: 97]

// Menghancurkan seluruh data session yang ada di server secara permanen[cite: 100]
session_destroy();

// Mengarahkan kembali ke halaman login
header("Location: login.php");
exit;
?>