<?php
// koneksi.php
$host     = "localhost";
$nama_db  = "db_apotek";
$username = "root"; // Sesuaikan dengan username database Anda (default XAMPP: root)
$password = "";     // Sesuaikan dengan password database Anda (default XAMPP: kosong)

// Data Source Name (DSN)[cite: 78]
$dsn = "mysql:host=" . $host . ";dbname=" . $nama_db . ";charset=utf8";

try {
    // Membuat instance PDO baru[cite: 78]
    $pdo = new PDO($dsn, $username, $password);
    
    // Set mode error PDO menjadi exception agar mudah ditangani[cite: 79]
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Koneksi berhasil (tidak perlu di-echo agar tidak mengganggu tampilan HTML nantinya)
} catch (PDOException $e) {
    // Jika gagal, hentikan program dan tampilkan pesan error[cite: 80]
    die("Koneksi gagal: " . $e->getMessage());
}
?>