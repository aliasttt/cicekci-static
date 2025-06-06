<?php
require_once 'config.php';

// Get product ID from URL
$product_id = $_GET['id'] ?? 0;

// Get product details
try {
    $stmt = $db->prepare("SELECT * FROM urunler WHERE id = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch();
} catch(PDOException $e) {
    $product = null;
}

// If product not found, redirect to products page
if (!$product) {
    header('Location: products.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['baslik']); ?> - Çiçekçi</title>
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

        .product-detail {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            overflow: hidden;
            margin: 50px 0;
        }

        .product-image {
            height: 400px;
            object-fit: cover;
            width: 100%;
        }

        .product-info {
            padding: 30px;
        }

        .product-title {
            font-size: 2rem;
            color: var(--primary-color);
            margin-bottom: 20px;
        }

        .product-price {
            font-size: 1.5rem;
            color: var(--secondary-color);
            font-weight: bold;
            margin-bottom: 20px;
        }

        .product-description {
            color: #666;
            line-height: 1.6;
            margin-bottom: 30px;
        }

        .quantity-control {
            display: flex;
            align-items: center;
            margin-bottom: 30px;
        }

        .quantity-btn {
            width: 40px;
            height: 40px;
            border: none;
            background: var(--primary-color);
            color: white;
            font-size: 1.2rem;
            border-radius: 50%;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .quantity-btn:hover {
            background: var(--secondary-color);
            transform: scale(1.1);
        }

        .quantity-input {
            width: 60px;
            height: 40px;
            text-align: center;
            margin: 0 15px;
            border: 2px solid var(--primary-color);
            border-radius: 20px;
            font-size: 1.2rem;
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

        .cart-icon {
            position: fixed;
            top: 20px;
            right: 20px;
            background: var(--primary-color);
            color: white;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
            transition: all 0.3s ease;
            z-index: 1000;
        }

        .cart-icon:hover {
            transform: scale(1.1);
            background: var(--secondary-color);
        }

        .cart-count {
            position: absolute;
            top: -5px;
            right: -5px;
            background: var(--secondary-color);
            color: white;
            width: 25px;
            height: 25px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
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
                        <a class="nav-link active" href="products.php">Ürünler</a>
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

    <!-- Cart Icon -->
    <a href="cart.php" class="cart-icon">
        <i class="fas fa-shopping-cart"></i>
        <span class="cart-count">0</span>
    </a>

    <!-- Product Detail -->
    <div class="container">
        <div class="product-detail" data-aos="fade-up">
            <div class="row">
                <div class="col-md-6">
                    <div class="product-image-container">
                        <?php
                        $imageUrl = $product['resim'];
                        if (!empty($imageUrl)) {
                            if (strpos($imageUrl, 'http://') === 0 || strpos($imageUrl, 'https://') === 0) {
                                $src = $imageUrl;
                            } else {
                                $src = 'cicekci-static/' . $imageUrl;
                            }
                            echo '<img src="' . htmlspecialchars($src) . '" alt="' . htmlspecialchars($product['baslik']) . '" class="img-fluid product-image" style="max-width: 100%; height: auto; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">';
                        } else {
                            echo '<img src="cicekci-static/no-image.png" alt="No Image" class="img-fluid product-image" style="max-width: 100%; height: auto; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">';
                        }
                        ?>
                    </div>
                </div>
                <div class="col-md-6 product-info">
                    <h1 class="product-title"><?php echo htmlspecialchars($product['baslik']); ?></h1>
                    <p class="product-price"><?php echo number_format($product['fiyat'], 2); ?> TL</p>
                    <p class="product-description"><?php echo htmlspecialchars($product['aciklama']); ?></p>
                    
                    <div class="quantity-control">
                        <button class="quantity-btn" onclick="decreaseQuantity()">-</button>
                        <input type="number" class="quantity-input" id="quantity" value="1" min="1" readonly>
                        <button class="quantity-btn" onclick="increaseQuantity()">+</button>
                    </div>
                    
                    <button class="btn btn-primary w-100 add-to-cart" 
                            data-id="<?php echo $product['id']; ?>"
                            data-price="<?php echo $product['fiyat']; ?>">
                        <i class="fas fa-cart-plus"></i> Sepete Ekle
                    </button>
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

        // Quantity Control
        function increaseQuantity() {
            const input = document.getElementById('quantity');
            input.value = parseInt(input.value) + 1;
        }

        function decreaseQuantity() {
            const input = document.getElementById('quantity');
            if (parseInt(input.value) > 1) {
                input.value = parseInt(input.value) - 1;
            }
        }

        // Add to Cart
        document.querySelector('.add-to-cart').addEventListener('click', function() {
            const productId = this.dataset.id;
            const price = this.dataset.price;
            const quantity = parseInt(document.getElementById('quantity').value);
            
            // Get current cart from localStorage
            let cart = JSON.parse(localStorage.getItem('cart')) || [];
            
            // Check if product already in cart
            const existingItem = cart.find(item => item.id === productId);
            
            if (existingItem) {
                existingItem.quantity += quantity;
            } else {
                cart.push({
                    id: productId,
                    quantity: quantity,
                    price: parseFloat(price)
                });
            }
            
            // Save cart back to localStorage
            localStorage.setItem('cart', JSON.stringify(cart));
            
            // Update cart count
            updateCartCount();
            
            // Show success message
            alert('Ürün sepete eklendi!');
        });

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