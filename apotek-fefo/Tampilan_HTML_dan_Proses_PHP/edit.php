<?php
// edit.php
require_once 'bahasa.php';
require_once 'koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$error_msg = '';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}
$id = $_GET['id'];

try {
    $stmt = $pdo->prepare("SELECT * FROM obat WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $obat = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$obat) {
        header("Location: index.php");
        exit;
    }
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $kode_obat = htmlspecialchars(trim($_POST['kode_obat']));
    $nama_obat = htmlspecialchars(trim($_POST['nama_obat']));
    $kategori = htmlspecialchars(trim($_POST['kategori']));
    $harga = filter_var($_POST['harga'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $stok = filter_var($_POST['stok'], FILTER_SANITIZE_NUMBER_INT);
    $tanggal_kedaluwarsa = $_POST['tanggal_kedaluwarsa'];

    if (empty($kode_obat) || empty($nama_obat) || empty($kategori) || empty($harga) || empty($stok) || empty($tanggal_kedaluwarsa)) {
        $error_msg = trans('err_empty');
    } else {
        try {
            $stmt = $pdo->prepare("UPDATE obat SET kode_obat = :kode, nama_obat = :nama, kategori = :kategori, harga = :harga, stok = :stok, tanggal_kedaluwarsa = :tgl WHERE id = :id");
            $stmt->execute([
                ':kode' => $kode_obat,
                ':nama' => $nama_obat,
                ':kategori' => $kategori,
                ':harga' => $harga,
                ':stok' => $stok,
                ':tgl' => $tanggal_kedaluwarsa,
                ':id' => $id
            ]);
            header("Location: index.php");
            exit;
        } catch (PDOException $e) {
            $error_msg = "Gagal mengubah data: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="<?php echo $active_lang; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo trans('edit_data'); ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }
        body { 
            font-family: 'Quicksand', sans-serif; 
            background: linear-gradient(135deg, #00C9FF 0%, #92FE9D 100%); background-attachment: fixed;
            padding: 40px 20px; margin: 0; position: relative; min-height: 100vh;
        }
        .bg-shape1 { position: fixed; top: -10%; left: -5%; width: 400px; height: 400px; background: rgba(255,255,255,0.3); border-radius: 50%; filter: blur(40px); z-index: -1; }
        .bg-shape2 { position: fixed; bottom: -15%; right: -5%; width: 500px; height: 500px; background: rgba(0, 201, 255, 0.4); border-radius: 50%; filter: blur(60px); z-index: -1; }

        .glass-card { 
            max-width: 650px; margin: 0 auto; 
            background: rgba(255, 255, 255, 0.4);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.6);
            border-radius: 24px; padding: 40px; 
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.1); 
        }
        .glass-card h2 { margin-top: 0; color: #005c7a; font-weight: 700; margin-bottom: 25px; border-bottom: 2px solid rgba(255,255,255,0.6); padding-bottom: 15px;}
        
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .form-group { margin-bottom: 15px; }
        .form-group.full-width { grid-column: span 2; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: 700; color: #005c7a;}
        .form-group input, .form-group select { 
            width: 100%; padding: 12px; 
            background: rgba(255, 255, 255, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.8); 
            border-radius: 12px; transition: 0.3s; 
            font-family: 'Quicksand', sans-serif; font-weight: 600; color: #005c7a;
        }
        .form-group input:focus, .form-group select:focus { outline: none; background: rgba(255, 255, 255, 0.9); box-shadow: 0 0 10px rgba(255, 165, 2, 0.3); border-color: #ff9f43; }
        
        .action-buttons { display: flex; gap: 15px; margin-top: 30px; }
        
        /* Tombol Save Edit dibuat warna gradien Orange/Kuning */
        .btn-save { 
            flex: 1; background: linear-gradient(135deg, #f6d365 0%, #fda085 100%); 
            color: white; border: none; padding: 12px; border-radius: 12px; cursor: pointer; 
            font-weight: 700; font-family: 'Quicksand', sans-serif; transition: 0.3s; font-size: 1em;
            box-shadow: 0 4px 15px rgba(253, 160, 133, 0.4); text-shadow: 1px 1px 2px rgba(0,0,0,0.1);
        }
        .btn-save:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(253, 160, 133, 0.6); }
        
        .btn-back { 
            flex: 1; background: rgba(255,255,255,0.5); color: #005c7a; 
            text-decoration: none; padding: 12px; border-radius: 12px; display: block; 
            text-align: center; font-weight: 700; transition: 0.3s;
        }
        .btn-back:hover { background: rgba(255,255,255,0.8); transform: translateY(-2px);}
        
        .error { background: rgba(255, 71, 87, 0.2); color: #e84118; padding: 12px; border-radius: 12px; font-weight: 600; margin-bottom: 20px; font-size: 0.9em; border: 1px solid rgba(255, 71, 87, 0.3);}
    </style>
</head>
<body>

<div class="bg-shape1"></div>
<div class="bg-shape2"></div>

<div class="glass-card">
    <h2>✏️ <?php echo trans('edit_data'); ?></h2>
    
    <?php if ($error_msg != '') { echo "<div class='error'>$error_msg</div>"; } ?>

    <form method="POST" action="">
        <div class="form-grid">
            <div class="form-group">
                <label><?php echo trans('med_code'); ?></label>
                <input type="text" name="kode_obat" value="<?php echo htmlspecialchars($obat['kode_obat']); ?>" required>
            </div>
            <div class="form-group">
                <label><?php echo trans('med_name'); ?></label>
                <input type="text" name="nama_obat" value="<?php echo htmlspecialchars($obat['nama_obat']); ?>" required>
            </div>
            <div class="form-group full-width">
                <label><?php echo trans('category'); ?></label>
                <select name="kategori" required>
                    <option value="Tablet" <?php if($obat['kategori'] == 'Tablet') echo 'selected'; ?>>Tablet</option>
                    <option value="Kapsul" <?php if($obat['kategori'] == 'Kapsul') echo 'selected'; ?>>Kapsul</option>
                    <option value="Sirup" <?php if($obat['kategori'] == 'Sirup') echo 'selected'; ?>>Sirup</option>
                    <option value="Salep" <?php if($obat['kategori'] == 'Salep') echo 'selected'; ?>>Salep</option>
                    <option value="Suntik" <?php if($obat['kategori'] == 'Suntik') echo 'selected'; ?>>Suntik</option>
                </select>
            </div>
            <div class="form-group">
                <label><?php echo trans('price'); ?></label>
                <input type="number" step="0.01" name="harga" value="<?php echo htmlspecialchars($obat['harga']); ?>" required>
            </div>
            <div class="form-group">
                <label><?php echo trans('stock'); ?></label>
                <input type="number" name="stok" value="<?php echo htmlspecialchars($obat['stok']); ?>" required>
            </div>
            <div class="form-group full-width">
                <label><?php echo trans('exp_date'); ?></label>
                <input type="date" name="tanggal_kedaluwarsa" value="<?php echo htmlspecialchars($obat['tanggal_kedaluwarsa']); ?>" required>
            </div>
        </div>
        
        <div class="action-buttons">
            <a href="index.php" class="btn-back"><?php echo trans('back'); ?></a>
            <button type="submit" class="btn-save"><?php echo trans('save'); ?></button>
        </div>
    </form>
</div>

</body>
</html>