<?php
require_once 'config.php';

// Get about content from database
try {
    $stmt = $db->prepare("SELECT * FROM site_icerik WHERE baslik = 'Hakkımızda'");
    $stmt->execute();
    $about = $stmt->fetch();
} catch(PDOException $e) {
    $about = null;
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hakkımızda - Çiçekçi</title>
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

        .about-header {
            background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('assets/images/about-bg.jpg');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 100px 0;
            text-align: center;
            margin-bottom: 50px;
        }

        .about-content {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            padding: 30px;
            margin-bottom: 50px;
        }

        .team-member {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            padding: 30px;
            text-align: center;
            margin-bottom: 30px;
            transition: all 0.3s ease;
        }

        .team-member:hover {
            transform: translateY(-10px);
        }

        .team-member img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            margin-bottom: 20px;
            object-fit: cover;
        }

        .team-member h4 {
            color: var(--primary-color);
            margin-bottom: 10px;
        }

        .team-member p {
            color: #666;
            margin-bottom: 15px;
        }

        .social-links a {
            color: var(--primary-color);
            margin: 0 10px;
            font-size: 1.2rem;
            transition: all 0.3s ease;
        }

        .social-links a:hover {
            color: var(--secondary-color);
            transform: translateY(-3px);
        }

        .stats-section {
            background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 50px 0;
            margin: 50px 0;
        }

        .stat-item {
            text-align: center;
            padding: 20px;
        }

        .stat-item i {
            font-size: 3rem;
            margin-bottom: 20px;
        }

        .stat-item h3 {
            font-size: 2.5rem;
            margin-bottom: 10px;
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
                        <a class="nav-link active" href="about.php">Hakkımızda</a>
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

    <!-- About Header -->
    <div class="about-header">
        <div class="container">
            <h1 class="display-4 mb-4" data-aos="fade-up">Hakkımızda</h1>
            <p class="lead" data-aos="fade-up" data-aos-delay="200">
                Kaliteli hizmet ve müşteri memnuniyeti odaklı çalışıyoruz
            </p>
        </div>
    </div>

    <!-- About Content -->
    <div class="container">
        <div class="about-content" data-aos="fade-up">
            <div class="row">
                <div class="col-md-6">
                    <img src="assets/images/about.jpg" alt="Hakkımızda" class="img-fluid rounded mb-4">
                </div>
                <div class="col-md-6">
                    <h2 class="mb-4">Biz Kimiz?</h2>
                    <p class="lead">
                        <?php echo $about ? htmlspecialchars($about['icerik']) : 'Çiçekçi olarak, müşterilerimize en kaliteli çiçek ve aranjmanları sunmayı hedefliyoruz.'; ?>
                    </p>
                    <p>
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
                    </p>
                </div>
            </div>
        </div>

        <!-- Stats Section -->
        <div class="stats-section" data-aos="fade-up">
            <div class="container">
                <div class="row">
                    <div class="col-md-3">
                        <div class="stat-item">
                            <i class="fas fa-users"></i>
                            <h3>1000+</h3>
                            <p>Mutlu Müşteri</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-item">
                            <i class="fas fa-flower"></i>
                            <h3>500+</h3>
                            <p>Ürün Çeşidi</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-item">
                            <i class="fas fa-truck"></i>
                            <h3>50+</h3>
                            <p>Günlük Teslimat</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-item">
                            <i class="fas fa-award"></i>
                            <h3>10+</h3>
                            <p>Yıllık Deneyim</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Team Section -->
        <h2 class="text-center mb-5" data-aos="fade-up">Ekibimiz</h2>
        <div class="row">
            <div class="col-md-4" data-aos="fade-up">
                <div class="team-member">
                    <img src="assets/images/team-1.jpg" alt="Takım Üyesi">
                    <h4>Ahmet Yılmaz</h4>
                    <p>Kurucu & CEO</p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                <div class="team-member">
                    <img src="assets/images/team-2.jpg" alt="Takım Üyesi">
                    <h4>Ayşe Demir</h4>
                    <p>Çiçek Tasarımcısı</p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="400">
                <div class="team-member">
                    <img src="assets/images/team-3.jpg" alt="Takım Üyesi">
                    <h4>Mehmet Kaya</h4>
                    <p>Müşteri İlişkileri</p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                    </div>
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