<?php
require_once 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get user orders
try {
    $stmt = $db->prepare("
        SELECT o.*, GROUP_CONCAT(CONCAT(p.baslik, ' (', oi.miktar, ')') SEPARATOR ', ') as items
        FROM siparisler o
        LEFT JOIN siparis_urunler oi ON o.id = oi.siparis_id
        LEFT JOIN urunler p ON oi.urun_id = p.id
        WHERE o.kullanici_id = ?
        GROUP BY o.id
        ORDER BY o.created_at DESC
    ");
    $stmt->execute([$_SESSION['user_id']]);
    $orders = $stmt->fetchAll();
} catch(PDOException $e) {
    $orders = [];
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Siparişlerim - Çiçekçi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #ff69b4;
            --secondary-color: #ff1493;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #fff5f8 0%, #fff 100%);
        }

        .navbar {
            background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
        }

        .order-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }

        .order-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }

        .order-header {
            background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 15px;
            border-radius: 15px 15px 0 0;
        }

        .order-body {
            padding: 20px;
        }

        .status-badge {
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.9rem;
        }

        .status-pending {
            background: #ffc107;
            color: #000;
        }

        .status-completed {
            background: #28a745;
            color: white;
        }

        .status-cancelled {
            background: #dc3545;
            color: white;
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
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user-circle"></i> <?php echo htmlspecialchars($_SESSION['user_name']); ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="profile.php"><i class="fas fa-user"></i> Profilim</a></li>
                                <li><a class="dropdown-item" href="orders.php"><i class="fas fa-shopping-bag"></i> Siparişlerim</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt"></i> Çıkış</a></li>
                            </ul>
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

    <!-- Orders Section -->
    <section class="py-5">
        <div class="container">
            <h2 class="text-center mb-5">Siparişlerim</h2>
            
            <?php if (empty($orders)): ?>
            <div class="text-center">
                <i class="fas fa-shopping-bag fa-3x mb-3 text-muted"></i>
                <h4>Henüz siparişiniz bulunmuyor</h4>
                <p class="text-muted">Ürünlerimizi inceleyip sipariş verebilirsiniz.</p>
                <a href="products.php" class="btn btn-primary">Ürünleri İncele</a>
            </div>
            <?php else: ?>
            <div class="row">
                <?php foreach ($orders as $order): ?>
                <div class="col-md-6 mb-4">
                    <div class="order-card">
                        <div class="order-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">Sipariş #<?php echo $order['id']; ?></h5>
                                <span class="status-badge status-<?php echo strtolower($order['durum']); ?>">
                                    <?php echo $order['durum']; ?>
                                </span>
                            </div>
                        </div>
                        <div class="order-body">
                            <p><strong>Tarih:</strong> <?php echo date('d.m.Y H:i', strtotime($order['created_at'])); ?></p>
                            <p><strong>Toplam:</strong> <?php echo number_format($order['toplam_tutar'], 2); ?> TL</p>
                            <p><strong>Ürünler:</strong> <?php echo htmlspecialchars($order['items']); ?></p>
                            <p><strong>Adres:</strong> <?php echo htmlspecialchars($order['adres']); ?></p>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 