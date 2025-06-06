<?php
require_once 'config.php';

// Get cart items from localStorage via JavaScript
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sepetim - Çiçekçi</title>
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

        .cart-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            padding: 30px;
            margin: 50px 0;
        }

        .cart-item {
            display: flex;
            align-items: center;
            padding: 20px;
            border-bottom: 1px solid #eee;
            transition: all 0.3s ease;
        }

        .cart-item:hover {
            background: #f8f9fa;
        }

        .cart-item img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 10px;
            margin-right: 20px;
        }

        .cart-item-info {
            flex: 1;
        }

        .cart-item-title {
            font-size: 1.2rem;
            color: var(--primary-color);
            margin-bottom: 5px;
        }

        .cart-item-price {
            color: var(--secondary-color);
            font-weight: bold;
        }

        .quantity-control {
            display: flex;
            align-items: center;
            margin: 0 20px;
        }

        .quantity-btn {
            width: 30px;
            height: 30px;
            border: none;
            background: var(--primary-color);
            color: white;
            font-size: 1rem;
            border-radius: 50%;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .quantity-btn:hover {
            background: var(--secondary-color);
            transform: scale(1.1);
        }

        .quantity-input {
            width: 50px;
            height: 30px;
            text-align: center;
            margin: 0 10px;
            border: 2px solid var(--primary-color);
            border-radius: 15px;
            font-size: 1rem;
        }

        .remove-item {
            color: #dc3545;
            cursor: pointer;
            font-size: 1.2rem;
            transition: all 0.3s ease;
        }

        .remove-item:hover {
            transform: scale(1.2);
        }

        .cart-summary {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 20px;
            margin-top: 30px;
        }

        .cart-total {
            font-size: 1.5rem;
            color: var(--secondary-color);
            font-weight: bold;
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

        .empty-cart {
            text-align: center;
            padding: 50px 0;
        }

        .empty-cart i {
            font-size: 5rem;
            color: var(--primary-color);
            margin-bottom: 20px;
        }

        .footer {
            background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 50px 0;
            margin-top: 50px;
        }

        .table {
            margin-bottom: 0;
        }
        
        .table th {
            background-color: var(--primary-color);
            color: white;
            font-weight: 500;
            text-align: center;
        }
        
        .table td {
            vertical-align: middle;
            text-align: center;
        }
        
        .cart-item-image {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
        }
        
        .quantity-control {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        
        .quantity-btn {
            width: 30px;
            height: 30px;
            border: none;
            background: var(--primary-color);
            color: white;
            font-size: 1rem;
            border-radius: 50%;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .quantity-btn:hover {
            background: var(--secondary-color);
            transform: scale(1.1);
        }
        
        .quantity-input {
            width: 50px;
            height: 30px;
            text-align: center;
            border: 2px solid var(--primary-color);
            border-radius: 15px;
            font-size: 1rem;
        }
        
        .remove-item {
            color: #dc3545;
            cursor: pointer;
            font-size: 1.2rem;
            transition: all 0.3s ease;
        }
        
        .remove-item:hover {
            transform: scale(1.2);
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

    <!-- Cart Container -->
    <div class="container">
        <div class="cart-container" data-aos="fade-up">
            <h2 class="text-center mb-4">Sepetim</h2>
            
            <div id="cart-items">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th style="width: 100px">Resim</th>
                            <th>Ürün Adı</th>
                            <th style="width: 150px">Adet</th>
                            <th style="width: 120px">Birim Fiyat</th>
                            <th style="width: 120px">Toplam</th>
                            <th style="width: 50px"></th>
                        </tr>
                    </thead>
                    <tbody id="cart-items-body">
                        <!-- Cart items will be loaded here via JavaScript -->
                    </tbody>
                </table>
            </div>

            <div id="empty-cart" class="empty-cart" style="display: none;">
                <i class="fas fa-shopping-cart"></i>
                <h3>Sepetiniz Boş</h3>
                <p>Sepetinizde henüz ürün bulunmuyor.</p>
                <a href="products.php" class="btn btn-primary">Alışverişe Başla</a>
            </div>

            <div id="cart-summary" class="cart-summary" style="display: none;">
                <div class="d-flex justify-content-between align-items-center">
                    <h4>Toplam Tutar:</h4>
                    <span id="cart-total" class="cart-total">0.00 TL</span>
                </div>
                <button class="btn btn-primary w-100 mt-3" onclick="checkout()">
                    <i class="fas fa-credit-card"></i> Ödemeye Geç
                </button>
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

        document.addEventListener('DOMContentLoaded', function() {
            loadCart();
        });

        function loadCart() {
            // Get cart items from localStorage
            const cartItems = JSON.parse(localStorage.getItem('cart')) || [];
            const cartItemsBody = document.getElementById('cart-items-body');
            const cartTotal = document.getElementById('cart-total');
            const emptyCart = document.getElementById('empty-cart');
            const cartSummary = document.getElementById('cart-summary');
            let total = 0;

            // Clear previous items
            cartItemsBody.innerHTML = '';

            if (cartItems.length === 0) {
                document.getElementById('cart-items').style.display = 'none';
                emptyCart.style.display = 'block';
                cartSummary.style.display = 'none';
                return;
            }

            document.getElementById('cart-items').style.display = 'block';
            emptyCart.style.display = 'none';
            cartSummary.style.display = 'block';

            // Display cart items
            cartItems.forEach((item, index) => {
                const price = parseFloat(item.price);
                if (!isNaN(price)) {
                    const quantity = parseInt(item.quantity) || 1;
                    const itemTotal = price * quantity;
                    total += itemTotal;
                    
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>
                            <img src="${item.image ? (item.image.startsWith('http') ? item.image : 'cicekci-static/' + item.image) : 'cicekci-static/no-image.png'}" 
                                 alt="${item.title || 'Ürün'}" 
                                 class="cart-item-image"
                                 onerror="this.onerror=null; this.src='cicekci-static/no-image.png';">
                        </td>
                        <td>${item.title || 'Ürün'}</td>
                        <td>
                            <div class="quantity-control">
                                <button class="quantity-btn" onclick="updateQuantity(${index}, -1)">-</button>
                                <input type="number" class="quantity-input" value="${quantity}" min="1" 
                                       onchange="updateQuantity(${index}, this.value - ${quantity})">
                                <button class="quantity-btn" onclick="updateQuantity(${index}, 1)">+</button>
                            </div>
                        </td>
                        <td>${price.toFixed(2)} TL</td>
                        <td>${itemTotal.toFixed(2)} TL</td>
                        <td>
                            <i class="fas fa-trash remove-item" onclick="removeItem(${index})"></i>
                        </td>
                    `;
                    cartItemsBody.appendChild(row);
                } else {
                    console.error('Invalid price for item:', item);
                }
            });

            // Update total
            cartTotal.textContent = total.toFixed(2) + ' TL';
            updateCartCount();
        }

        function updateQuantity(index, change) {
            const cartItems = JSON.parse(localStorage.getItem('cart')) || [];
            if (cartItems[index]) {
                const newQuantity = Math.max(1, parseInt(cartItems[index].quantity) + parseInt(change));
                cartItems[index].quantity = newQuantity;
                localStorage.setItem('cart', JSON.stringify(cartItems));
                loadCart(); // Reload cart instead of page refresh
            }
        }

        function removeItem(index) {
            const cartItems = JSON.parse(localStorage.getItem('cart')) || [];
            cartItems.splice(index, 1);
            localStorage.setItem('cart', JSON.stringify(cartItems));
            loadCart(); // Reload cart instead of page refresh
        }

        function updateCartCount() {
            const cart = JSON.parse(localStorage.getItem('cart')) || [];
            const count = cart.reduce((total, item) => total + (parseInt(item.quantity) || 0), 0);
            document.querySelector('.cart-count').textContent = count;
        }

        function checkout() {
            if (!<?php echo isset($_SESSION['user_id']) ? 'true' : 'false'; ?>) {
                alert('Lütfen önce giriş yapın!');
                window.location.href = 'login.php';
                return;
            }
            window.location.href = 'checkout.php';
        }
    </script>
</body>
</html> 