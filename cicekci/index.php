<?php
require_once 'config.php';

// Check if user is logged in
$logged_in = false;
if (isset($_SESSION['user_id'])) {
    $logged_in = true;
}

// Get products from database
try {
    $stmt = $db->query("SELECT * FROM urunler ORDER BY created_at DESC LIMIT 6");
    $products = $stmt->fetchAll();
} catch(PDOException $e) {
    $products = [];
}

// Get site content
try {
    $stmt = $db->query("SELECT * FROM site_icerik WHERE baslik = 'Hakkımızda'");
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
    <title>Çiçekçi - Ana Sayfa</title>
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

        .hero-section {
            background: url('https://images.unsplash.com/photo-1490750967868-88aa4486c946?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80') center/cover;
            height: 80vh;
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, rgba(255,105,180,0.8), rgba(255,20,147,0.8));
        }

        .hero-content {
            position: relative;
            z-index: 1;
            color: white;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }

        .product-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            transform-style: preserve-3d;
            perspective: 1000px;
        }

        .product-card:hover {
            transform: translateY(-10px) rotateX(5deg);
            box-shadow: 0 15px 30px rgba(0,0,0,0.2);
        }

        .product-card img {
            height: 200px;
            object-fit: cover;
            transition: all 0.3s ease;
        }

        .product-card:hover img {
            transform: scale(1.1);
        }

        .about-section {
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            margin: 50px 0;
        }

        .footer {
            background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 50px 0;
            margin-top: 50px;
        }

        .social-links a {
            color: white;
            font-size: 24px;
            margin: 0 10px;
            transition: all 0.3s ease;
        }

        .social-links a:hover {
            transform: translateY(-5px);
            color: var(--accent-color);
        }

        .btn-primary {
            background: var(--primary-color);
            border: none;
            padding: 10px 25px;
            border-radius: 25px;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: var(--secondary-color);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255,105,180,0.4);
        }

        .floating {
            animation: floating 3s ease-in-out infinite;
        }

        @keyframes floating {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
            100% { transform: translateY(0px); }
        }

        .bg-pattern {
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ff69b4' fill-opacity='0.1'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }

        .cart-count {
            position: absolute;
            top: -8px;
            right: -8px;
            background: var(--secondary-color);
            color: white;
            font-size: 0.7rem;
            padding: 0.25rem 0.5rem;
            border-radius: 50%;
            min-width: 20px;
            text-align: center;
            z-index: 1000;
        }

        @media (max-width: 768px) {
            .navbar-nav {
                align-items: center;
            }
            .cart-count {
                top: -8px;
                right: -8px;
            }
        }
    </style>
</head>
<body>
    <?php include 'cods/navbar.php'; ?>
    
    <!-- Hero Section -->
    <section class="hero-section d-flex align-items-center">
        <div class="container">
            <div class="row">
                <div class="col-md-8 hero-content" data-aos="fade-right">
                    <h1 class="display-3 fw-bold mb-4">Sevgi ve Güzelliğin Adresi</h1>
                    <p class="lead mb-4">En güzel çiçekler, en özel anlar için...</p>
                    <a href="products.php" class="btn btn-light btn-lg">Ürünlerimizi Keşfedin</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Products Section -->
    <section class="py-5 bg-pattern">
        <div class="container">
            <h2 class="text-center mb-5" data-aos="fade-up">Öne Çıkan Ürünler</h2>
            <div class="row">
                <?php foreach ($products as $product): ?>
                <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="<?php echo $loop * 100; ?>">
                    <div class="product-card">
                        <?php if ($product['resim']): ?>
                        <img src="<?php echo htmlspecialchars($product['resim']); ?>" 
                             class="card-img-top" alt="<?php echo htmlspecialchars($product['baslik']); ?>">
                        <?php else: ?>
                        <img src="https://images.unsplash.com/photo-1566908829550-e6551b00979b?w=800&auto=format&fit=crop&q=60" 
                             class="card-img-top" alt="<?php echo htmlspecialchars($product['baslik']); ?>">
                        <?php endif; ?>
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($product['baslik']); ?></h5>
                            <p class="card-text"><?php echo htmlspecialchars($product['aciklama']); ?></p>
                            <p class="card-text"><strong>Fiyat: </strong><?php echo number_format($product['fiyat'], 2); ?> TL</p>
                            <a href="product.php?id=<?php echo $product['id']; ?>" class="btn btn-primary">Detaylar</a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <div class="text-center mt-4" data-aos="fade-up">
                <a href="products.php" class="btn btn-primary btn-lg">Tüm Ürünleri Gör</a>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="py-5">
        <div class="container">
            <div class="about-section" data-aos="fade-up">
                <div class="row align-items-center">
                    <div class="col-md-6" data-aos="fade-right">
                        <img src="https://images.unsplash.com/photo-1525310072745-f49212b5ac6d?ixlib=rb-1.2.1&auto=format&fit=crop&w=1000&q=80" 
                             class="img-fluid rounded" alt="Hakkımızda">
                    </div>
                    <div class="col-md-6" data-aos="fade-left">
                        <h2 class="mb-4">Hakkımızda</h2>
                        <p class="lead"><?php echo htmlspecialchars($about['icerik'] ?? 'Çiçekçi olarak 10 yıldır hizmetinizdeyiz.'); ?></p>
                        <a href="about.php" class="btn btn-primary mt-3">Daha Fazla Bilgi</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

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

        // Update cart count
        function updateCartCount() {
            const cart = JSON.parse(localStorage.getItem('cart')) || [];
            const count = cart.reduce((total, item) => total + item.quantity, 0);
            document.querySelector('.cart-count').textContent = count;
        }

        // Update cart count on page load
        updateCartCount();
    </script>
</body>
</html> 