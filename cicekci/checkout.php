<?php
require_once 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get user details
try {
    $stmt = $db->prepare("SELECT * FROM kullanicilar WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch();
} catch(PDOException $e) {
    $user = null;
}

// Process order
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ad = $_POST['ad'] ?? '';
    $soyad = $_POST['soyad'] ?? '';
    $email = $_POST['email'] ?? '';
    $telefon = $_POST['telefon'] ?? '';
    $adres = $_POST['adres'] ?? '';
    $sehir = $_POST['sehir'] ?? '';
    $posta_kodu = $_POST['posta_kodu'] ?? '';
    $odeme_tipi = $_POST['odeme_tipi'] ?? '';

    // Validate form data
    $errors = [];
    if (empty($ad)) $errors[] = 'Ad alanı zorunludur.';
    if (empty($soyad)) $errors[] = 'Soyad alanı zorunludur.';
    if (empty($email)) $errors[] = 'E-posta alanı zorunludur.';
    if (empty($telefon)) $errors[] = 'Telefon alanı zorunludur.';
    if (empty($adres)) $errors[] = 'Adres alanı zorunludur.';
    if (empty($sehir)) $errors[] = 'Şehir alanı zorunludur.';
    if (empty($posta_kodu)) $errors[] = 'Posta kodu alanı zorunludur.';
    if (empty($odeme_tipi)) $errors[] = 'Ödeme tipi seçilmelidir.';

    if (empty($errors)) {
        try {
            // Start transaction
            $db->beginTransaction();

            // Insert order
            $stmt = $db->prepare("INSERT INTO siparisler (kullanici_id, ad, soyad, email, telefon, adres, sehir, posta_kodu, odeme_tipi, toplam_tutar) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $_SESSION['user_id'],
                $ad,
                $soyad,
                $email,
                $telefon,
                $adres,
                $sehir,
                $posta_kodu,
                $odeme_tipi,
                $_POST['toplam_tutar']
            ]);

            $siparis_id = $db->lastInsertId();

            // Insert order items
            $stmt = $db->prepare("INSERT INTO siparis_urunleri (siparis_id, urun_id, miktar, fiyat) VALUES (?, ?, ?, ?)");
            
            $cart = json_decode($_POST['cart_data'], true);
            foreach ($cart as $item) {
                $stmt->execute([
                    $siparis_id,
                    $item['id'],
                    $item['quantity'],
                    $item['price']
                ]);
            }

            // Commit transaction
            $db->commit();

            // Clear cart
            echo "<script>localStorage.removeItem('cart');</script>";

            // Redirect to success page
            header('Location: order-success.php?id=' . $siparis_id);
            exit;

        } catch(PDOException $e) {
            // Rollback transaction on error
            $db->rollBack();
            $errors[] = 'Sipariş oluşturulurken bir hata oluştu.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ödeme - Çiçekçi</title>
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

        .checkout-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            padding: 30px;
            margin: 50px 0;
        }

        .form-control {
            border: 2px solid #eee;
            border-radius: 10px;
            padding: 12px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(255,105,180,0.25);
        }

        .form-label {
            color: #666;
            font-weight: 500;
        }

        .order-summary {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 20px;
        }

        .order-item {
            display: flex;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }

        .order-item img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
            margin-right: 15px;
        }

        .order-total {
            font-size: 1.5rem;
            color: var(--secondary-color);
            font-weight: bold;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 2px solid #eee;
        }

        .payment-method {
            border: 2px solid #eee;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .payment-method:hover {
            border-color: var(--primary-color);
        }

        .payment-method.selected {
            border-color: var(--primary-color);
            background: rgba(255,105,180,0.1);
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

    <!-- Checkout Container -->
    <div class="container">
        <div class="checkout-container" data-aos="fade-up">
            <h2 class="text-center mb-4">Ödeme</h2>

            <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach ($errors as $error): ?>
                    <li><?php echo $error; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>

            <form method="POST" id="checkout-form">
                <div class="row">
                    <div class="col-md-8">
                        <div class="card mb-4">
                            <div class="card-body">
                                <h4 class="card-title mb-4">Teslimat Bilgileri</h4>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Ad</label>
                                        <input type="text" class="form-control" name="ad" value="<?php echo $user['ad'] ?? ''; ?>" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Soyad</label>
                                        <input type="text" class="form-control" name="soyad" value="<?php echo $user['soyad'] ?? ''; ?>" required>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">E-posta</label>
                                        <input type="email" class="form-control" name="email" value="<?php echo $user['email'] ?? ''; ?>" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Telefon</label>
                                        <input type="tel" class="form-control" name="telefon" value="<?php echo $user['telefon'] ?? ''; ?>" required>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Adres</label>
                                    <textarea class="form-control" name="adres" rows="3" required><?php echo $user['adres'] ?? ''; ?></textarea>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Şehir</label>
                                        <input type="text" class="form-control" name="sehir" value="<?php echo $user['sehir'] ?? ''; ?>" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Posta Kodu</label>
                                        <input type="text" class="form-control" name="posta_kodu" value="<?php echo $user['posta_kodu'] ?? ''; ?>" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title mb-4">Ödeme Yöntemi</h4>
                                
                                <div class="payment-method" onclick="selectPayment('kredi')">
                                    <input type="radio" name="odeme_tipi" value="kredi" id="kredi" required>
                                    <label for="kredi" class="ms-2">
                                        <i class="fas fa-credit-card"></i> Kredi Kartı
                                    </label>
                                </div>

                                <div class="payment-method" onclick="selectPayment('havale')">
                                    <input type="radio" name="odeme_tipi" value="havale" id="havale" required>
                                    <label for="havale" class="ms-2">
                                        <i class="fas fa-university"></i> Havale/EFT
                                    </label>
                                </div>

                                <div class="payment-method" onclick="selectPayment('kapida')">
                                    <input type="radio" name="odeme_tipi" value="kapida" id="kapida" required>
                                    <label for="kapida" class="ms-2">
                                        <i class="fas fa-money-bill-wave"></i> Kapıda Ödeme
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="order-summary">
                            <h4 class="mb-4">Sipariş Özeti</h4>
                            <div id="order-items">
                                <!-- Order items will be loaded here via JavaScript -->
                            </div>
                            <div class="order-total text-end">
                                Toplam: <span id="total-amount">0.00</span> TL
                            </div>
                            <input type="hidden" name="toplam_tutar" id="toplam_tutar">
                            <input type="hidden" name="cart_data" id="cart_data">
                            <button type="submit" class="btn btn-primary w-100 mt-4">
                                <i class="fas fa-lock"></i> Siparişi Tamamla
                            </button>
                        </div>
                    </div>
                </div>
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

        // Load order items
        function loadOrderItems() {
            const cart = JSON.parse(localStorage.getItem('cart')) || [];
            const orderItems = document.getElementById('order-items');
            let total = 0;

            orderItems.innerHTML = '';
            cart.forEach(item => {
                const itemTotal = item.price * item.quantity;
                total += itemTotal;

                orderItems.innerHTML += `
                    <div class="order-item">
                        <img src="assets/images/${item.image || 'placeholder.jpg'}" alt="${item.title}">
                        <div class="flex-grow-1">
                            <h6 class="mb-0">${item.title}</h6>
                            <small class="text-muted">${item.quantity} x ${item.price.toFixed(2)} TL</small>
                        </div>
                        <div class="ms-3">
                            ${itemTotal.toFixed(2)} TL
                        </div>
                    </div>
                `;
            });

            document.getElementById('total-amount').textContent = total.toFixed(2);
            document.getElementById('toplam_tutar').value = total.toFixed(2);
            document.getElementById('cart_data').value = JSON.stringify(cart);
        }

        // Select payment method
        function selectPayment(method) {
            document.querySelectorAll('.payment-method').forEach(el => {
                el.classList.remove('selected');
            });
            document.getElementById(method).checked = true;
            document.getElementById(method).parentElement.classList.add('selected');
        }

        // Load order items on page load
        loadOrderItems();
    </script>
</body>
</html> 