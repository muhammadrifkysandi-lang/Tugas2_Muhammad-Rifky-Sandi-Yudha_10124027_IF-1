<?php
// hapus.php
session_start();
require_once 'koneksi.php';

// Proteksi: Hanya yang sudah login yang bisa menghapus data
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Cek apakah parameter ID dikirim via URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    try {
        // Menghapus data menggunakan prepared statements untuk keamanan[cite: 90]
        $stmt = $pdo->prepare("DELETE FROM obat WHERE id = :id");
        $stmt->execute([':id' => $id]);
    } catch (PDOException $e) {
        die("Gagal menghapus data: " . $e->getMessage());
    }
}

// Setelah selesai (berhasil atau tidak), arahkan kembali ke index
header("Location: index.php");
exit;
?>