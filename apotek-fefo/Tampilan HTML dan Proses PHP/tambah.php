<?php
// tambah.php
require_once 'bahasa.php';
require_once 'koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$error_msg = '';

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
            $stmt = $pdo->prepare("INSERT INTO obat (kode_obat, nama_obat, kategori, harga, stok, tanggal_kedaluwarsa) 
                                   VALUES (:kode, :nama, :kategori, :harga, :stok, :tgl)");
            $stmt->execute([
                ':kode' => $kode_obat,
                ':nama' => $nama_obat,
                ':kategori' => $kategori,
                ':harga' => $harga,
                ':stok' => $stok,
                ':tgl' => $tanggal_kedaluwarsa
            ]);
            header("Location: index.php");
            exit;
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $error_msg = "Kode Obat sudah terdaftar!";
            } else {
                $error_msg = "Gagal menyimpan data: " . $e->getMessage();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="<?php echo $active_lang; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo trans('add_data'); ?></title>
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
        .form-group input:focus, .form-group select:focus { outline: none; background: rgba(255, 255, 255, 0.9); box-shadow: 0 0 10px rgba(0, 201, 255, 0.3); }
        
        .action-buttons { display: flex; gap: 15px; margin-top: 30px; }
        .btn-save { 
            flex: 1; background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); 
            color: white; border: none; padding: 12px; border-radius: 12px; cursor: pointer; 
            font-weight: 700; font-family: 'Quicksand', sans-serif; transition: 0.3s; font-size: 1em;
            box-shadow: 0 4px 15px rgba(56, 239, 125, 0.4);
        }
        .btn-save:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(56, 239, 125, 0.6); }
        
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
    <h2>✨ <?php echo trans('add_data'); ?></h2>
    
    <?php if ($error_msg != '') { echo "<div class='error'>$error_msg</div>"; } ?>

    <form method="POST" action="">
        <div class="form-grid">
            <div class="form-group">
                <label><?php echo trans('med_code'); ?></label>
                <input type="text" name="kode_obat" required>
            </div>
            <div class="form-group">
                <label><?php echo trans('med_name'); ?></label>
                <input type="text" name="nama_obat" required>
            </div>
            <div class="form-group full-width">
                <label><?php echo trans('category'); ?></label>
                <select name="kategori" required>
                    <option value="" disabled selected>-- Pilih Kategori --</option>
                    <option value="Tablet">Tablet</option>
                    <option value="Kapsul">Kapsul</option>
                    <option value="Sirup">Sirup</option>
                    <option value="Salep">Salep</option>
                    <option value="Suntik">Suntik</option>
                </select>
            </div>
            <div class="form-group">
                <label><?php echo trans('price'); ?></label>
                <input type="number" step="0.01" name="harga" required>
            </div>
            <div class="form-group">
                <label><?php echo trans('stock'); ?></label>
                <input type="number" name="stok" required>
            </div>
            <div class="form-group full-width">
                <label><?php echo trans('exp_date'); ?></label>
                <input type="date" name="tanggal_kedaluwarsa" required>
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