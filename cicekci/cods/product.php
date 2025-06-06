<?php
include 'baglan.php';

// Get all products
$sql = "SELECT * FROM urunler ORDER BY id DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ürünlerimiz - Çiçekçi</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <!-- Products Header -->
    <section class="products-header">
        <div class="container">
            <h1>Ürünlerimiz</h1>
            <p>En taze ve güzel çiçeklerimizi keşfedin</p>
        </div>
    </section>

    <!-- Products Section -->
    <section class="products">
        <div class="container">
            <!-- Categories Filter -->
            <div class="categories-filter">
                <button class="filter-btn active" data-category="all">Tümü</button>
                <button class="filter-btn" data-category="buket">Buketler</button>
                <button class="filter-btn" data-category="aranjman">Aranjmanlar</button>
                <button class="filter-btn" data-category="orkide">Orkideler</button>
            </div>

            <!-- Products Grid -->
            <div class="products-grid">
        <?php
        if (mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) {
                ?>
                        <div class="product-card" data-category="<?php echo htmlspecialchars($row['kategori']); ?>">
                            <div class="product-image">
                                <img src="../assets/images/<?php echo htmlspecialchars($row['resim']); ?>" 
                             alt="<?php echo htmlspecialchars($row['adi']); ?>">
                                <div class="product-overlay">
                                    <a href="#" class="btn btn-primary">Detaylar</a>
                                </div>
                            </div>
                            <div class="product-content">
                                <h3><?php echo htmlspecialchars($row['adi']); ?></h3>
                                <p><?php echo htmlspecialchars($row['aciklama']); ?></p>
                                <div class="product-price">
                                    <span class="price"><?php echo number_format($row['fiyat'], 2, ',', '.'); ?> ₺</span>
                                    <button class="btn btn-primary add-to-cart">
                                        <i class="fas fa-shopping-cart"></i> Sepete Ekle
                                    </button>
                        </div>
                    </div>
                </div>
                <?php
            }
        } else {
                    echo '<div class="no-products">Henüz ürün eklenmemiş.</div>';
        }
        ?>
    </div>
</div> 
    </section>

    <!-- Special Offer Banner -->
    <section class="special-offer-banner">
        <div class="container">
            <div class="offer-content">
                <h2>500 TL ve Üzeri Alışverişlerde</h2>
                <p>%10 İndirim Fırsatı!</p>
                <span class="coupon-code">Kupon Kodu: FLOWER10</span>
            </div>
        </div>
    </section>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Category Filter
        $('.filter-btn').click(function() {
            $('.filter-btn').removeClass('active');
            $(this).addClass('active');
            
            const category = $(this).data('category');
            
            if (category === 'all') {
                $('.product-card').show();
            } else {
                $('.product-card').hide();
                $('.product-card[data-category="' + category + '"]').show();
            }
        });

        // Add to Cart Animation
        $('.add-to-cart').click(function() {
            $(this).addClass('added');
            setTimeout(() => {
                $(this).removeClass('added');
            }, 1000);
        });
    </script>
</body>
</html> 