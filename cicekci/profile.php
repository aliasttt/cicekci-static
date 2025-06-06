<?php
require_once 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$success_message = '';
$error_message = '';

// Get user data
try {
    $stmt = $db->prepare("SELECT * FROM kullanicilar WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch();
} catch(PDOException $e) {
    $error_message = 'Veritabanı hatası: ' . $e->getMessage();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $current_password = trim($_POST['current_password'] ?? '');
    $new_password = trim($_POST['new_password'] ?? '');
    
    $errors = [];
    
    // Validate input
    if (empty($name)) $errors[] = 'Ad Soyad gereklidir';
    if (empty($email)) $errors[] = 'E-posta gereklidir';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Geçerli bir e-posta adresi giriniz';
    
    // Check if email is already taken by another user
    if (empty($errors)) {
        try {
            $stmt = $db->prepare("SELECT COUNT(*) FROM kullanicilar WHERE email = ? AND id != ?");
            $stmt->execute([$email, $_SESSION['user_id']]);
            if ($stmt->fetchColumn() > 0) {
                $errors[] = 'Bu e-posta adresi başka bir kullanıcı tarafından kullanılıyor';
            }
        } catch(PDOException $e) {
            $errors[] = 'Veritabanı hatası: ' . $e->getMessage();
        }
    }
    
    // If changing password, validate current password
    if (!empty($new_password)) {
        if (empty($current_password)) {
            $errors[] = 'Mevcut şifrenizi giriniz';
        } else {
            try {
                $stmt = $db->prepare("SELECT sifre FROM kullanicilar WHERE id = ?");
                $stmt->execute([$_SESSION['user_id']]);
                $stored_password = $stmt->fetchColumn();
                
                if (!password_verify($current_password, $stored_password)) {
                    $errors[] = 'Mevcut şifreniz yanlış';
                }
            } catch(PDOException $e) {
                $errors[] = 'Veritabanı hatası: ' . $e->getMessage();
            }
        }
    }
    
    // If no errors, update user data
    if (empty($errors)) {
        try {
            if (!empty($new_password)) {
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $stmt = $db->prepare("UPDATE kullanicilar SET ad_soyad = ?, email = ?, sifre = ? WHERE id = ?");
                $result = $stmt->execute([$name, $email, $hashed_password, $_SESSION['user_id']]);
            } else {
                $stmt = $db->prepare("UPDATE kullanicilar SET ad_soyad = ?, email = ? WHERE id = ?");
                $result = $stmt->execute([$name, $email, $_SESSION['user_id']]);
            }
            
            if ($result) {
                $_SESSION['user_name'] = $name;
                $success_message = 'Profiliniz başarıyla güncellendi';
                
                // Refresh user data
                $stmt = $db->prepare("SELECT * FROM kullanicilar WHERE id = ?");
                $stmt->execute([$_SESSION['user_id']]);
                $user = $stmt->fetch();
            } else {
                $error_message = 'Profil güncellenirken bir hata oluştu';
            }
        } catch(PDOException $e) {
            $error_message = 'Veritabanı hatası: ' . $e->getMessage();
        }
    } else {
        $error_message = implode('<br>', $errors);
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profilim - Çiçekçi</title>
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
        }

        .navbar {
            background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
            box-shadow: 0 2px 15px rgba(0,0,0,0.1);
        }

        .navbar-brand, .nav-link {
            color: white !important;
            font-weight: 500;
        }

        .profile-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            padding: 40px;
            margin: 50px 0;
        }

        .profile-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .profile-header i {
            font-size: 4rem;
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
        }

        .btn-primary:hover {
            background: var(--secondary-color);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255,105,180,0.4);
        }

        .footer {
            background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 50px 0;
            margin-top: 50px;
        }
    </style>
</head>
<body>
    <?php include 'cods/navbar.php'; ?>

    <!-- Profile Container -->
    <div class="container">
        <div class="profile-container" data-aos="fade-up">
            <div class="profile-header">
                <i class="fas fa-user-circle"></i>
                <h2>Profilim</h2>
            </div>

            <?php if ($success_message): ?>
            <div class="alert alert-success">
                <?php echo $success_message; ?>
            </div>
            <?php endif; ?>

            <?php if ($error_message): ?>
            <div class="alert alert-danger">
                <?php echo $error_message; ?>
            </div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="mb-3">
                    <label class="form-label">Ad Soyad</label>
                    <input type="text" class="form-control" name="name" value="<?php echo htmlspecialchars($user['ad_soyad'] ?? ''); ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">E-posta</label>
                    <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Mevcut Şifre</label>
                    <input type="password" class="form-control" name="current_password">
                    <small class="text-muted">Şifrenizi değiştirmek istemiyorsanız boş bırakın</small>
                </div>
                <div class="mb-3">
                    <label class="form-label">Yeni Şifre</label>
                    <input type="password" class="form-control" name="new_password">
                    <small class="text-muted">Şifrenizi değiştirmek istemiyorsanız boş bırakın</small>
                </div>
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-save"></i> Değişiklikleri Kaydet
                </button>
            </form>
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
    </script>
</body>
</html> 