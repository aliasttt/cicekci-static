<?php
require_once 'config.php';

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($email) || empty($password)) {
        $error = 'Lütfen email ve şifrenizi girin.';
    } else {
        try {
            // Try users table first
            $stmt = $db->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
            $stmt->execute([$email]);
            $user = $stmt->fetch();

            // If not found in users table, try kullanicilar table
            if (!$user) {
                $stmt = $db->prepare("SELECT * FROM kullanicilar WHERE email = ? LIMIT 1");
                $stmt->execute([$email]);
                $user = $stmt->fetch();
                
                if ($user) {
                    // Convert kullanicilar table structure to match users table
                    $user['username'] = $user['email'];
                    $user['full_name'] = $user['ad_soyad'];
                    $user['password'] = $user['sifre']; // Convert sifre to password
                }
            }

            if ($user && isset($user['password']) && password_verify($password, $user['password'])) {
                // Set session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'] ?? $user['email'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['full_name'] = $user['full_name'] ?? $user['ad_soyad'];
                $_SESSION['yetki'] = $user['yetki'] ?? 'user';
                
                // Debug information
                error_log("Login successful for email: " . $email);
                error_log("Session data: " . print_r($_SESSION, true));
                
                header("Location: index.php");
                exit();
            } else {
                error_log("Login failed for email: " . $email);
                $error = 'Email veya şifre hatalı.';
            }
        } catch(PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            $error = 'Bir hata oluştu. Lütfen tekrar deneyin.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giriş Yap - Çiçekçi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #fff5f8 0%, #fff 100%);
            min-height: 100vh;
        }
        .navbar { 
            background: linear-gradient(to right, #ff69b4, #ff1493);
            box-shadow: 0 2px 15px rgba(0,0,0,0.1);
        }
        .navbar-brand, .nav-link { 
            color: white !important; 
            font-weight: 500;
        }
        .login-form { 
            max-width: 400px; 
            margin: 50px auto;
            background: white;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .btn-primary {
            background: linear-gradient(to right, #ff69b4, #ff1493);
            border: none;
            padding: 12px 30px;
            border-radius: 25px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255,105,180,0.4);
        }
        .form-control {
            border-radius: 10px;
            padding: 12px;
            border: 2px solid #eee;
        }
        .form-control:focus {
            border-color: #ff69b4;
            box-shadow: 0 0 0 0.2rem rgba(255,105,180,0.25);
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark mb-4">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-flower"></i> Çiçekçi
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Ana Sayfa</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="products.php">Ürünler</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="about.php">Hakkımızda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="contact.php">İletişim</a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="profile.php">Profilim</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">Çıkış</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link active" href="login.php">Giriş</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="register.php">Kayıt Ol</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Login Form -->
    <div class="container">
        <div class="login-form">
            <h2 class="text-center mb-4">Giriş Yap</h2>
            
            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" autocomplete="email" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Şifre</label>
                    <input type="password" class="form-control" id="password" name="password" autocomplete="current-password" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Giriş Yap</button>
            </form>
            
            <div class="text-center mt-3">
                <p>Hesabınız yok mu? <a href="register.php">Kayıt Ol</a></p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 