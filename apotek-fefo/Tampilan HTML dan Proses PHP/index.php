<?php
// index.php
require_once 'bahasa.php';
require_once 'koneksi.php';

// Memeriksa keberadaan session login[cite: 99]
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

try {
    // Mengambil seluruh data dengan PDO dan mengurutkannya secara FEFO[cite: 81, 82, 88]
    $stmt = $pdo->query("SELECT * FROM obat ORDER BY tanggal_kedaluwarsa ASC");
    $obat_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error mengambil data: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="<?php echo $active_lang; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo trans('dashboard'); ?></title>
    <!-- Menggunakan font Quicksand agar terlihat lebih ramah dan unik dibanding Arial/Roboto standar -->
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Quicksand', sans-serif;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            color: #2c3e50;
            /* Background gradien apotek cerah & cantik */
            background: linear-gradient(135deg, #00C9FF 0%, #92FE9D 100%);
            background-attachment: fixed;
            position: relative;
            overflow-x: hidden;
        }

        /* Dekorasi background bulat (Blob) untuk memperkuat efek kaca */
        .bg-shape1 { position: fixed; top: -10%; left: -5%; width: 400px; height: 400px; background: rgba(255,255,255,0.3); border-radius: 50%; filter: blur(40px); z-index: -1; }
        .bg-shape2 { position: fixed; bottom: -15%; right: -5%; width: 500px; height: 500px; background: rgba(0, 201, 255, 0.4); border-radius: 50%; filter: blur(60px); z-index: -1; }

        /* Container dengan efek Kaca Buram (Glassmorphism) */
        .glass-container {
            max-width: 1150px;
            margin: 40px auto;
            background: rgba(255, 255, 255, 0.4);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.6);
            border-radius: 24px;
            padding: 40px;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.1);
        }

        /* Navigasi / Header */
        .glass-nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 20px;
            border-bottom: 2px solid rgba(255, 255, 255, 0.6);
            margin-bottom: 30px;
        }
        .nav-title { font-size: 1.8em; font-weight: 700; color: #005c7a; margin: 0; text-shadow: 1px 1px 2px rgba(255,255,255,0.5);}
        .nav-menus { display: flex; align-items: center; gap: 15px; font-weight: 600; }
        .lang-btn { text-decoration: none; padding: 5px 12px; background: rgba(255,255,255,0.5); border-radius: 20px; color: #005c7a; transition: 0.3s; }
        .lang-btn:hover { background: #fff; }
        .btn-logout { background: rgba(255, 71, 87, 0.8); color: white; padding: 8px 18px; border-radius: 20px; text-decoration: none; transition: 0.3s; }
        .btn-logout:hover { background: #ff4757; box-shadow: 0 4px 10px rgba(255, 71, 87, 0.3); }

        /* Area Aksi & Tambah Data */
        .action-area { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; }
        .btn-add { 
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); 
            color: white; text-decoration: none; padding: 12px 25px; 
            border-radius: 30px; font-weight: 700; font-size: 1em;
            box-shadow: 0 4px 15px rgba(56, 239, 125, 0.4); transition: all 0.3s ease;
        }
        .btn-add:hover { transform: translateY(-3px); box-shadow: 0 6px 20px rgba(56, 239, 125, 0.6); }

        /* Tabel Gaya Modern (Floating Rows) */
        table { width: 100%; border-collapse: separate; border-spacing: 0 12px; }
        th { padding: 15px; text-transform: uppercase; font-size: 0.85em; letter-spacing: 1px; color: #005c7a; border-bottom: none; text-align: left;}
        
        td { 
            padding: 16px; 
            background: rgba(255, 255, 255, 0.6); 
            backdrop-filter: blur(5px);
            border-top: 1px solid rgba(255,255,255,0.8);
            border-bottom: 1px solid rgba(255,255,255,0.8);
            transition: 0.3s;
        }
        /* Efek lengkung pada ujung baris tabel */
        tr td:first-child { border-top-left-radius: 15px; border-bottom-left-radius: 15px; border-left: 1px solid rgba(255,255,255,0.8); }
        tr td:last-child { border-top-right-radius: 15px; border-bottom-right-radius: 15px; border-right: 1px solid rgba(255,255,255,0.8); }
        
        /* Efek saat baris tabel disorot */
        tr:hover td { background: rgba(255, 255, 255, 0.9); transform: scale(1.01); box-shadow: 0 5px 15px rgba(0,0,0,0.05); }

        /* Badge FEFO */
        .fefo-badge { padding: 6px 12px; border-radius: 20px; font-size: 0.85em; font-weight: 700; display: inline-block;}
        .fefo-danger { background: rgba(255, 71, 87, 0.2); color: #e84118; border: 1px solid rgba(255, 71, 87, 0.3);}
        .fefo-safe { background: rgba(46, 213, 115, 0.2); color: #2ed573; border: 1px solid rgba(46, 213, 115, 0.3);}

        /* Tombol Aksi Tabel */
        .btn-action { text-decoration: none; padding: 6px 12px; border-radius: 8px; font-size: 0.85em; font-weight: 700; margin-right: 5px; transition: 0.3s;}
        .btn-edit { background: rgba(255, 165, 2, 0.2); color: #ff9f43; }
        .btn-edit:hover { background: #ff9f43; color: white; }
        .btn-delete { background: rgba(255, 71, 87, 0.2); color: #ff4757; }
        .btn-delete:hover { background: #ff4757; color: white; }

        /* Gaya Khusus Pesan Kosong */
        .empty-data { text-align: center; padding: 40px !important; color: #005c7a; font-style: italic; background: rgba(255,255,255,0.4) !important;}
    </style>
</head>
<body>

<!-- Dekorasi Background -->
<div class="bg-shape1"></div>
<div class="bg-shape2"></div>

<div class="glass-container">
    <!-- Navbar -->
    <div class="glass-nav">
        <h1 class="nav-title">💊 <?php echo trans('app_title'); ?></h1>
        <div class="nav-menus">
            <span style="color: #005c7a;">Halo, <strong><?php echo $_SESSION['nama_lengkap']; ?></strong></span>
            <div style="width: 2px; height: 20px; background: rgba(0,0,0,0.1); margin: 0 10px;"></div>
            <a href="?lang=id" class="lang-btn" style="<?php echo $active_lang == 'id' ? 'background: white; box-shadow: 0 2px 5px rgba(0,0,0,0.1);' : ''; ?>">ID</a>
            <a href="?lang=en" class="lang-btn" style="<?php echo $active_lang == 'en' ? 'background: white; box-shadow: 0 2px 5px rgba(0,0,0,0.1);' : ''; ?>">EN</a>
            <a href="logout.php" class="btn-logout" onclick="return confirm('Yakin ingin keluar?');"><?php echo trans('logout'); ?></a>
        </div>
    </div>

    <!-- Area Tambah Data -->
    <div class="action-area">
        <h2 style="margin: 0; color: #005c7a; font-weight: 700;"><?php echo trans('dashboard'); ?></h2>
        <a href="tambah.php" class="btn-add">+ <?php echo trans('add_data'); ?></a>
    </div>

    <!-- Tabel Data Obat -->
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th><?php echo trans('med_code'); ?></th>
                <th><?php echo trans('med_name'); ?></th>
                <th><?php echo trans('category'); ?></th>
                <th><?php echo trans('price'); ?></th>
                <th><?php echo trans('stock'); ?></th>
                <th><?php echo trans('exp_date'); ?></th>
                <th><?php echo trans('action'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($obat_list) > 0): ?>
                <?php $no = 1; foreach ($obat_list as $row): ?>
                    <tr>
                        <td style="font-weight: 700; color: #005c7a;"><?php echo $no++; ?></td>
                        <td><strong><?php echo htmlspecialchars($row['kode_obat']); ?></strong></td>
                        <td><?php echo htmlspecialchars($row['nama_obat']); ?></td>
                        <td><?php echo htmlspecialchars($row['kategori']); ?></td>
                        <td style="font-weight: 600;">Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?></td>
                        <td><?php echo htmlspecialchars($row['stok']); ?></td>
                        <td>
                            <?php 
                                $exp_date = strtotime($row['tanggal_kedaluwarsa']);
                                $now = time();
                                $diff_days = ($exp_date - $now) / (60 * 60 * 24);
                                
                                if ($diff_days <= 30) {
                                    echo "<span class='fefo-badge fefo-danger'>⏳ " . $row['tanggal_kedaluwarsa'] . "</span>";
                                } else {
                                    echo "<span class='fefo-badge fefo-safe'>✔️ " . $row['tanggal_kedaluwarsa'] . "</span>";
                                }
                            ?>
                        </td>
                        <td>
                            <a href="edit.php?id=<?php echo $row['id']; ?>" class="btn-action btn-edit"><?php echo trans('edit_data'); ?></a>
                            <a href="hapus.php?id=<?php echo $row['id']; ?>" class="btn-action btn-delete" onclick="return confirm('<?php echo trans('delete'); ?>?');"><?php echo trans('delete'); ?></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="8" class="empty-data">Belum ada data obat yang terdaftar. Yuk, tambah data sekarang!</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>