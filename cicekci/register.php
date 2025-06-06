<?php
require_once 'config.php';

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate input
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    
    if (empty($name)) $errors[] = 'Ad Soyad gereklidir';
    if (empty($email)) $errors[] = 'E-posta gereklidir';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Geçerli bir e-posta adresi giriniz';
    if (empty($password)) $errors[] = 'Şifre gereklidir';
    if (strlen($password) < 6) $errors[] = 'Şifre en az 6 karakter olmalıdır';
    
    // Check if email already exists
    if (empty($errors)) {
        try {
            $stmt = $db->prepare("SELECT COUNT(*) FROM kullanicilar WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetchColumn() > 0) {
                $errors[] = 'Bu e-posta adresi zaten kayıtlı';
            }
        } catch(PDOException $e) {
            $errors[] = 'Veritabanı hatası: ' . $e->getMessage();
        }
    }
    
    // If no errors, create user
    if (empty($errors)) {
        try {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $db->prepare("INSERT INTO kullanicilar (ad_soyad, email, sifre, created_at) VALUES (?, ?, ?, NOW())");
            $result = $stmt->execute([$name, $email, $hashed_password]);
            
            if ($result) {
                $_SESSION['user_id'] = $db->lastInsertId();
                $_SESSION['user_name'] = $name;
                header('Location: index.php');
                exit;
            } else {
                $errors[] = 'Kullanıcı kaydedilirken bir hata oluştu';
            }
        } catch(PDOException $e) {
            $errors[] = 'Veritabanı hatası: ' . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kayıt Ol - Çiçekçi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">
    <style>
        :root {
            --primary-color: #ff69b4;
            --secondary-color: #ff1493;
            --accent-color: #ffb6c1;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #fff5f8 0%, #fff 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .navbar {
            background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
            box-shadow: 0 2px 15px rgba(0,0,0,0.1);
        }

        .navbar-brand, .nav-link {
            color: white !important;
            font-weight: 500;
        }

        .register-container {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 50px 0;
        }

        .register-form {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            padding: 40px;
            width: 100%;
            max-width: 500px;
        }

        .register-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .register-header i {
            font-size: 3rem;
            color: var(--primary-color);
            margin-bottom: 20px;
        }

        .form-control {
            border-radius: 10px;
            padding: 12px;
            border: 1px solid #eee;
            margin-bottom: 20px;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(255,105,180,0.25);
        }

        .btn-primary {
            background: var(--primary-color);
            border: none;
            padding: 12px 30px;
            border-radius: 25px;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            width: 100%;
        }

        .btn-primary:hover {
            background: var(--secondary-color);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255,105,180,0.4);
        }

        .login-link {
            text-align: center;
            margin-top: 20px;
        }

        .login-link a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
        }

        .login-link a:hover {
            color: var(--secondary-color);
        }

        .footer {
            background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 50px 0;
            margin-top: auto;
        }

        .password-strength {
            height: 5px;
            margin-top: 5px;
            border-radius: 3px;
            transition: all 0.3s ease;
        }

        .strength-weak {
            background-color: #dc3545;
            width: 25%;
        }

        .strength-medium {
            background-color: #ffc107;
            width: 50%;
        }

        .strength-strong {
            background-color: #28a745;
            width: 100%;
        }

        .strength-text {
            font-size: 0.8rem;
            margin-top: 5px;
        }

        .strength-text.weak {
            color: #dc3545;
        }

        .strength-text.medium {
            color: #ffc107;
        }

        .strength-text.strong {
            color: #28a745;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
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
                            <a class="nav-link" href="logout.php">Çıkış</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="login.php">Giriş</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="register.php">Kayıt Ol</a>
                        </li>
                    <?php endif; ?>
                    <li class="nav-item">
                        <a class="nav-link position-relative" href="cart.php">
                            <i class="fas fa-shopping-cart"></i>
                            <span class="cart-count">0</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Register Container -->
    <div class="register-container">
        <div class="register-form" data-aos="fade-up">
            <div class="register-header">
                <i class="fas fa-user-plus"></i>
                <h2>Kayıt Ol</h2>
            </div>

            <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach ($errors as $error): ?>
                    <li><?php echo $error; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="mb-3">
                    <input type="text" class="form-control" name="name" placeholder="Ad Soyad" required>
                </div>
                <div class="mb-3">
                    <input type="email" class="form-control" name="email" placeholder="E-posta" required>
                </div>
                <div class="mb-3">
                    <input type="password" class="form-control" name="password" id="password" placeholder="Şifre" required>
                    <div class="password-strength"></div>
                    <div class="strength-text"></div>
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-user-plus"></i> Kayıt Ol
                </button>
            </form>

            <div class="login-link">
                Zaten hesabınız var mı? <a href="login.php">Giriş Yap</a>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5>İletişim</h5>
                    <p>
                        <i class="fas fa-phone"></i> +90 123 456 7890<br>
                        <i class="fas fa-envelope"></i> info@cicekci.com<br>
                        <i class="fas fa-map-marker-alt"></i> İstanbul, Türkiye
                    </p>
                </div>
                <div class="col-md-4 mb-4">
                    <h5>Hızlı Linkler</h5>
                    <ul class="list-unstyled">
                        <li><a href="index.php" class="text-white">Ana Sayfa</a></li>
                        <li><a href="products.php" class="text-white">Ürünler</a></li>
                        <li><a href="about.php" class="text-white">Hakkımızda</a></li>
                        <li><a href="contact.php" class="text-white">İletişim</a></li>
                    </ul>
                </div>
                <div class="col-md-4 mb-4">
                    <h5>Sosyal Medya</h5>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script>
        AOS.init({
            duration: 1000,
            once: true
        });

        // Password strength validation
        const passwordInput = document.getElementById('password');
        const strengthBar = document.querySelector('.password-strength');
        const strengthText = document.querySelector('.strength-text');

        passwordInput.addEventListener('input', function() {
            const password = this.value;
            let strength = 0;
            let feedback = '';

            // Length check
            if (password.length >= 8) strength += 1;
            
            // Contains number
            if (/\d/.test(password)) strength += 1;
            
            // Contains lowercase
            if (/[a-z]/.test(password)) strength += 1;
            
            // Contains uppercase
            if (/[A-Z]/.test(password)) strength += 1;
            
            // Contains special character
            if (/[^A-Za-z0-9]/.test(password)) strength += 1;

            // Update strength bar and text
            strengthBar.className = 'password-strength';
            strengthText.className = 'strength-text';

            if (strength <= 2) {
                strengthBar.classList.add('strength-weak');
                strengthText.classList.add('weak');
                feedback = 'Zayıf şifre';
            } else if (strength <= 4) {
                strengthBar.classList.add('strength-medium');
                strengthText.classList.add('medium');
                feedback = 'Orta güçlükte şifre';
            } else {
                strengthBar.classList.add('strength-strong');
                strengthText.classList.add('strong');
                feedback = 'Güçlü şifre';
            }

            strengthText.textContent = feedback;
        });

        // Form validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const password = passwordInput.value;
            const strength = strengthBar.className;
            
            if (strength.includes('weak')) {
                e.preventDefault();
                alert('Lütfen daha güçlü bir şifre seçin!');
            }
        });
    </script>
</body>
</html> 