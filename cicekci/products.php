<?php
require_once 'config.php';

// Get products from database
try {
    $stmt = $db->query("SELECT * FROM urunler ORDER BY created_at DESC");
    $products = $stmt->fetchAll();
} catch(PDOException $e) {
    $products = [];
}

// Add sample products if none exist
if ($stmt->rowCount() === 0) {
    $sample_products = [
        [
            'baslik' => 'Kırmızı Gül Buketi',
            'aciklama' => '12 adet kırmızı gülden oluşan lüks buket',
            'fiyat' => 299.99,
            'resim' => 'https://images.pexels.com/photos/1458694/pexels-photo-1458694.jpeg'
        ],
        [
            'baslik' => 'Orkide',
            'aciklama' => 'Beyaz orkide çiçeği',
            'fiyat' => 199.99,
            'resim' => 'https://images.pexels.com/photos/1904769/pexels-photo-1904769.jpeg'
        ],
        [
            'baslik' => 'Papatya Buketi',
            'aciklama' => 'Renkli papatyalardan oluşan buket',
            'fiyat' => 149.99,
            'resim' => 'https://images.pexels.com/photos/1458694/pexels-photo-1458694.jpeg'
        ],
        [
            'baslik' => 'Lale Buketi',
            'aciklama' => 'Renkli lalelerden oluşan buket',
            'fiyat' => 249.99,
            'resim' => 'https://images.pexels.com/photos/1458694/pexels-photo-1458694.jpeg'
        ],
        [
            'baslik' => 'Menekşe',
            'aciklama' => 'Mor menekşe çiçeği',
            'fiyat' => 89.99,
            'resim' => 'https://images.pexels.com/photos/1904769/pexels-photo-1904769.jpeg'
        ]
    ];

    $stmt = $db->prepare("INSERT INTO urunler (baslik, aciklama, fiyat, resim) VALUES (?, ?, ?, ?)");
    foreach ($sample_products as $product) {
        $stmt->execute([$product['baslik'], $product['aciklama'], $product['fiyat'], $product['resim']]);
    }
    
    // Refresh products list
    $stmt = $db->query("SELECT * FROM urunler ORDER BY created_at DESC");
    $products = $stmt->fetchAll();
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ürünler - Çiçekçi</title>
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

        .product-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            transform-style: preserve-3d;
            perspective: 1000px;
            height: 100%;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .product-image {
            height: 200px;
            object-fit: cover;
            border-radius: 10px 10px 0 0;
        }

        .product-price {
            font-size: 1.25rem;
            color: var(--primary-color);
            font-weight: bold;
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

        .footer {
            background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 50px 0;
            margin-top: 50px;
        }

        .bg-pattern {
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ff69b4' fill-opacity='0.1'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }

        @media (max-width: 768px) {
            .product-card {
                margin-bottom: 20px;
            }
            .product-image {
                height: 180px;
            }
        }
    </style>
</head>
<body>
    <?php include 'cods/navbar.php'; ?>
    
    <!-- Products Section -->
    <section class="py-5 bg-pattern">
        <div class="container">
            <h2 class="text-center mb-5" data-aos="fade-up">Ürünlerimiz</h2>
            <div class="row">
                <?php foreach ($products as $product): ?>
                <div class="col-md-4 mb-4">
                    <div class="card product-card" data-aos="fade-up">
                        <?php if ($product['resim']): ?>
                        <img src="<?php echo htmlspecialchars($product['resim']); ?>" 
                             class="product-image" alt="<?php echo htmlspecialchars($product['baslik']); ?>">
                        <?php else: ?>
                        <img src="https://images.unsplash.com/photo-1566908829550-e6551b00979b?w=800&auto=format&fit=crop&q=60" 
                             class="product-image" alt="<?php echo htmlspecialchars($product['baslik']); ?>">
                        <?php endif; ?>
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($product['baslik']); ?></h5>
                            <p class="card-text"><?php echo htmlspecialchars($product['aciklama']); ?></p>
                            <p class="card-text"><strong><?php echo number_format($product['fiyat'], 2); ?> TL</strong></p>
                            <button class="btn btn-primary w-100 add-to-cart" 
                                    data-id="<?php echo $product['id']; ?>"
                                    data-title="<?php echo htmlspecialchars($product['baslik']); ?>"
                                    data-price="<?php echo $product['fiyat']; ?>"
                                    data-image="<?php echo htmlspecialchars($product['resim']); ?>">
                                <i class="fas fa-cart-plus"></i> Sepete Ekle
                            </button>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
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

        // Add to cart functionality
        document.querySelectorAll('.add-to-cart').forEach(button => {
            button.addEventListener('click', function() {
                const product = {
                    id: this.dataset.id,
                    title: this.dataset.title,
                    price: parseFloat(this.dataset.price),
                    image: this.dataset.image,
                    quantity: 1
                };
                
                // Get current cart from localStorage
                let cart = JSON.parse(localStorage.getItem('cart')) || [];
                
                // Check if product already in cart
                const existingItem = cart.find(item => item.id === product.id);
                
                if (existingItem) {
                    existingItem.quantity += 1;
                } else {
                    cart.push(product);
                }
                
                // Save cart back to localStorage
                localStorage.setItem('cart', JSON.stringify(cart));
                
                // Update cart count
                updateCartCount();
                
                // Show success message
                alert('Ürün sepete eklendi!');
            });
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