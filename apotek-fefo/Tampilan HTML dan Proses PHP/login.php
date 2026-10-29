<?php
// login.php
require_once 'bahasa.php';
require_once 'koneksi.php';

if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$error_msg = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = htmlspecialchars(trim($_POST['username']));
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $error_msg = trans('err_empty');
    } else {
        try {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
            $stmt->execute([':username' => $username]);
            
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['nama_lengkap'] = $user['nama_lengkap'];
                
                header("Location: index.php");
                exit;
            } else {
                $error_msg = trans('err_auth');
            }
        } catch (PDOException $e) {
            $error_msg = "Terjadi kesalahan sistem.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="<?php echo $active_lang; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo trans('app_title'); ?> - <?php echo trans('login'); ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }
        body { 
            font-family: 'Quicksand', sans-serif; 
            background: linear-gradient(135deg, #00C9FF 0%, #92FE9D 100%);
            display: flex; justify-content: center; align-items: center; 
            height: 100vh; margin: 0; position: relative; overflow: hidden;
        }
        .bg-shape1 { position: absolute; top: -10%; left: -5%; width: 400px; height: 400px; background: rgba(255,255,255,0.3); border-radius: 50%; filter: blur(40px); z-index: -1; }
        .bg-shape2 { position: absolute; bottom: -15%; right: -5%; width: 500px; height: 500px; background: rgba(0, 201, 255, 0.4); border-radius: 50%; filter: blur(60px); z-index: -1; }

        .glass-wrapper {
            background: rgba(255, 255, 255, 0.4);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.6);
            border-radius: 24px;
            padding: 40px;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.1);
            width: 100%; max-width: 380px;
            text-align: center;
        }
        .glass-wrapper h2 { color: #005c7a; margin-bottom: 5px; font-weight: 700; }
        .glass-wrapper p.subtitle { color: #005c7a; font-weight: 600; margin-bottom: 25px; opacity: 0.8; }
        
        .form-group { margin-bottom: 20px; text-align: left; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: 700; color: #005c7a;}
        .form-group input { 
            width: 100%; padding: 12px; 
            background: rgba(255, 255, 255, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.8); 
            border-radius: 12px; transition: 0.3s; 
            font-family: 'Quicksand', sans-serif; font-weight: 600; color: #005c7a;
        }
        .form-group input:focus { outline: none; background: rgba(255, 255, 255, 0.9); box-shadow: 0 0 10px rgba(0, 201, 255, 0.3); }
        
        .btn-login { 
            width: 100%; padding: 12px; 
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); 
            color: white; border: none; border-radius: 12px; cursor: pointer; 
            font-weight: 700; font-family: 'Quicksand', sans-serif; transition: 0.3s; font-size: 1em;
            box-shadow: 0 4px 15px rgba(56, 239, 125, 0.4);
        }
        .btn-login:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(56, 239, 125, 0.6); }
        
        .error { background: rgba(255, 71, 87, 0.2); color: #e84118; padding: 10px; border-radius: 12px; font-size: 0.85em; font-weight: 600; margin-bottom: 20px; border: 1px solid rgba(255, 71, 87, 0.3);}
        
        .lang-switch { margin-bottom: 25px; font-size: 0.85em; background: rgba(255,255,255,0.5); padding: 8px; border-radius: 20px; display: inline-block;}
        .lang-switch a { text-decoration: none; color: #005c7a; padding: 5px 15px; border-radius: 15px; transition: 0.3s; font-weight: 700;}
        .lang-switch a:hover { background: rgba(255,255,255,0.8); }
        .icon-logo { font-size: 50px; margin-bottom: 10px; text-shadow: 2px 2px 5px rgba(0,0,0,0.1);}
    </style>
</head>
<body>

<div class="bg-shape1"></div>
<div class="bg-shape2"></div>

<div class="glass-wrapper">
    <div class="lang-switch">
        <a href="?lang=id" style="<?php echo $active_lang == 'id' ? 'background:white;box-shadow:0 2px 4px rgba(0,0,0,0.1);' : ''; ?>"><?php echo trans('lang_id'); ?></a>
        <a href="?lang=en" style="<?php echo $active_lang == 'en' ? 'background:white;box-shadow:0 2px 4px rgba(0,0,0,0.1);' : ''; ?>"><?php echo trans('lang_en'); ?></a>
    </div>

    <div class="icon-logo">💊</div>
    <h2><?php echo trans('app_title'); ?></h2>
    <p class="subtitle"><?php echo trans('login'); ?> untuk melanjutkan</p>
    
    <?php if ($error_msg != '') { echo "<div class='error'>$error_msg</div>"; } ?>

    <form method="POST" action="">
        <div class="form-group">
            <label><?php echo trans('username'); ?></label>
            <input type="text" name="username" required>
        </div>
        <div class="form-group">
            <label><?php echo trans('password'); ?></label>
            <input type="password" name="password" required>
        </div>
        <button type="submit" class="btn-login"><?php echo trans('btn_login'); ?></button>
    </form>
</div>

</body>
</html>