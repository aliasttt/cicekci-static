<?php
require_once 'config.php';

$success = false;
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate input
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');
    
    if (empty($name)) $errors[] = 'Ad Soyad gereklidir';
    if (empty($email)) $errors[] = 'E-posta gereklidir';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Geçerli bir e-posta adresi giriniz';
    if (empty($subject)) $errors[] = 'Konu gereklidir';
    if (empty($message)) $errors[] = 'Mesaj gereklidir';
    
    // If no errors, save message
    if (empty($errors)) {
        try {
            $stmt = $db->prepare("
                INSERT INTO mesajlar (ad_soyad, email, konu, mesaj, created_at)
                VALUES (?, ?, ?, ?, NOW())
            ");
            $stmt->execute([$name, $email, $subject, $message]);
            $success = true;
        } catch(PDOException $e) {
            $errors[] = 'Mesaj gönderilirken bir hata oluştu.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>İletişim - Çiçekçi</title>
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

        .contact-header {
            background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('assets/images/contact-bg.jpg');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 100px 0;
            text-align: center;
            margin-bottom: 50px;
        }

        .contact-info {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            padding: 30px;
            margin-bottom: 30px;
            text-align: center;
            transition: all 0.3s ease;
        }

        .contact-info:hover {
            transform: translateY(-10px);
        }

        .contact-info i {
            font-size: 2.5rem;
            color: var(--primary-color);
            margin-bottom: 20px;
        }

        .contact-form {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            padding: 30px;
            margin-bottom: 50px;
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

        .map-container {
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            margin-bottom: 50px;
        }

        .map-container iframe {
            width: 100%;
            height: 400px;
            border: none;
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
                        <a class="nav-link active" href="contact.php">İletişim</a>
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

    <!-- Contact Header -->
    <div class="contact-header">
        <div class="container">
            <h1 class="display-4 mb-4" data-aos="fade-up">İletişim</h1>
            <p class="lead" data-aos="fade-up" data-aos-delay="200">
                Sorularınız için bize ulaşın
            </p>
        </div>
    </div>

    <!-- Contact Info -->
    <div class="container">
        <div class="row">
            <div class="col-md-4" data-aos="fade-up">
                <div class="contact-info">
                    <i class="fas fa-map-marker-alt"></i>
                    <h4>Adres</h4>
                    <p>İstanbul, Türkiye</p>
                </div>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                <div class="contact-info">
                    <i class="fas fa-phone"></i>
                    <h4>Telefon</h4>
                    <p>+90 123 456 7890</p>
                </div>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="400">
                <div class="contact-info">
                    <i class="fas fa-envelope"></i>
                    <h4>E-posta</h4>
                    <p>info@cicekci.com</p>
                </div>
            </div>
        </div>

        <!-- Contact Form -->
        <div class="row">
            <div class="col-md-6" data-aos="fade-up">
                <div class="contact-form">
                    <h3 class="mb-4">Bize Mesaj Gönderin</h3>

                    <?php if ($success): ?>
                    <div class="alert alert-success">
                        Mesajınız başarıyla gönderildi. En kısa sürede size dönüş yapacağız.
                    </div>
                    <?php endif; ?>

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
                            <input type="text" class="form-control" name="subject" placeholder="Konu" required>
                        </div>
                        <div class="mb-3">
                            <textarea class="form-control" name="message" rows="5" placeholder="Mesajınız" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane"></i> Gönder
                        </button>
                    </form>
                </div>
            </div>
            <div class="col-md-6" data-aos="fade-up" data-aos-delay="200">
                <div class="map-container">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d385398.5897809314!2d28.731994399999998!3d41.0049823!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x14caa7040068086b%3A0xe1ccfe98bc01b0d0!2zxLBzdGFuYnVs!5e0!3m2!1str!2str!4v1647880000000!5m2!1str!2str" allowfullscreen="" loading="lazy"></iframe>
                </div>
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
    </script>
</body>
</html> 