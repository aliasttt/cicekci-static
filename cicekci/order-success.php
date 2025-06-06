<?php
require_once 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get order ID from URL
$order_id = $_GET['id'] ?? 0;

// Get order details
try {
    $stmt = $db->prepare("
        SELECT s.*, GROUP_CONCAT(u.baslik) as urunler, GROUP_CONCAT(su.miktar) as miktarlar, GROUP_CONCAT(su.fiyat) as fiyatlar
        FROM siparisler s
        LEFT JOIN siparis_urunleri su ON s.id = su.siparis_id
        LEFT JOIN urunler u ON su.urun_id = u.id
        WHERE s.id = ? AND s.kullanici_id = ?
        GROUP BY s.id
    ");
    $stmt->execute([$order_id, $_SESSION['user_id']]);
    $order = $stmt->fetch();
} catch(PDOException $e) {
    $order = null;
}

// If order not found, redirect to home page
if (!$order) {
    header('Location: index.php');
    exit;
}

// Parse order items
$urunler = explode(',', $order['urunler']);
$miktarlar = explode(',', $order['miktarlar']);
$fiyatlar = explode(',', $order['fiyatlar']);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sipariş Başarılı - Çiçekçi</title>
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

        .success-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            padding: 30px;
            margin: 50px 0;
            text-align: center;
        }

        .success-icon {
            font-size: 5rem;
            color: #28a745;
            margin-bottom: 20px;
        }

        .order-details {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 20px;
            margin-top: 30px;
            text-align: left;
        }

        .order-item {
            display: flex;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }

        .order-item:last-child {
            border-bottom: none;
        }

        .order-total {
            font-size: 1.5rem;
            color: var(--secondary-color);
            font-weight: bold;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 2px solid #eee;
        }

        .btn-primary {
            background: var(--primary-color);
            border: none;
            padding: 15px 30px;
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

    <!-- Success Container -->
    <div class="container">
        <div class="success-container" data-aos="fade-up">
            <i class="fas fa-check-circle success-icon"></i>
            <h2 class="mb-4">Siparişiniz Başarıyla Alındı!</h2>
            <p class="lead mb-4">Siparişiniz için teşekkür ederiz. Sipariş detaylarınız aşağıda yer almaktadır.</p>
            
            <div class="order-details">
                <h4 class="mb-4">Sipariş Detayları</h4>
                <p><strong>Sipariş No:</strong> #<?php echo str_pad($order['id'], 8, '0', STR_PAD_LEFT); ?></p>
                <p><strong>Tarih:</strong> <?php echo date('d.m.Y H:i', strtotime($order['created_at'])); ?></p>
                <p><strong>Ödeme Yöntemi:</strong> <?php echo ucfirst($order['odeme_tipi']); ?></p>
                
                <h5 class="mt-4 mb-3">Ürünler</h5>
                <?php for ($i = 0; $i < count($urunler); $i++): ?>
                <div class="order-item">
                    <div class="flex-grow-1">
                        <h6 class="mb-0"><?php echo htmlspecialchars($urunler[$i]); ?></h6>
                        <small class="text-muted"><?php echo $miktarlar[$i]; ?> x <?php echo number_format($fiyatlar[$i], 2); ?> TL</small>
                    </div>
                    <div class="ms-3">
                        <?php echo number_format($miktarlar[$i] * $fiyatlar[$i], 2); ?> TL
                    </div>
                </div>
                <?php endfor; ?>
                
                <div class="order-total text-end">
                    Toplam: <?php echo number_format($order['toplam_tutar'], 2); ?> TL
                </div>
            </div>

            <div class="mt-4">
                <a href="products.php" class="btn btn-primary">
                    <i class="fas fa-shopping-cart"></i> Alışverişe Devam Et
                </a>
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