<?php
include 'baglan.php';
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Çiçekçi - En Güzel Çiçekler</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="hero-content text-center">
                <h1>Hayatınıza Renk Katın</h1>
                <p>En taze çiçekler, özel tasarımlar ve profesyonel hizmet</p>
                <div class="hero-buttons">
                    <a href="product.php" class="btn btn-primary">Ürünleri Keşfet</a>
                    <a href="contact.php" class="btn btn-outline">Bize Ulaşın</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Categories Section -->
    <section class="categories">
        <div class="container">
            <h2 class="section-title">Kategorilerimiz</h2>
            <div class="row">
                <div class="col-md-4">
                    <div class="category-card">
                        <img src="../assets/images/buket.jpg" alt="Buketler">
                        <div class="category-content">
                            <h3>Buketler</h3>
                            <p>Özel günleriniz için tasarlanmış, taze ve canlı çiçek buketleri</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="category-card">
                        <img src="../assets/images/aranjman.jpg" alt="Aranjmanlar">
                        <div class="category-content">
                            <h3>Aranjmanlar</h3>
                            <p>Profesyonel tasarımlar ve özel etkinlikler için aranjmanlar</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="category-card">
                        <img src="../assets/images/orchid.jpg" alt="Orkideler">
                        <div class="category-content">
                            <h3>Orkideler</h3>
                            <p>Uzun ömürlü ve zarif orkide çeşitleri</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Why Choose Us Section -->
    <section class="why-choose-us">
        <div class="container">
            <h2 class="section-title text-center">Neden Bizi Seçmelisiniz?</h2>
            <div class="row">
                <div class="col-md-3">
                    <div class="feature-card">
                        <i class="fas fa-truck feature-icon"></i>
                        <h3>Hızlı Teslimat</h3>
                        <p>İstanbul içi aynı gün teslimat imkanı</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="feature-card">
                        <i class="fas fa-flower feature-icon"></i>
                        <h3>Taze Çiçekler</h3>
                        <p>Her gün taze gelen çiçekler</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="feature-card">
                        <i class="fas fa-paint-brush feature-icon"></i>
                        <h3>Özel Tasarım</h3>
                        <p>İsteğinize göre özel tasarımlar</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="feature-card">
                        <i class="fas fa-headset feature-icon"></i>
                        <h3>7/24 Destek</h3>
                        <p>Kesintisiz müşteri hizmetleri</p>
                    </div>
                </div>
        </div>
    </div>
    </section>

    <!-- Special Offers Section -->
    <section class="special-offers">
        <div class="container">
            <h2 class="section-title">Özel Teklifler</h2>
    <div class="row">
                <div class="col-md-6">
                    <div class="offer-card">
                        <div class="offer-content">
                            <h3>Anneler Günü Özel</h3>
                            <p>Tüm anneler günü ürünlerinde %20 indirim</p>
                            <a href="product.php" class="btn btn-primary">Detaylar</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="offer-card">
                        <div class="offer-content">
                            <h3>Kurumsal İndirimler</h3>
                            <p>Kurumsal müşterilerimize özel fiyatlar</p>
                            <a href="contact.php" class="btn btn-primary">İletişime Geçin</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="testimonials">
        <div class="container">
            <h2 class="section-title text-center">Müşteri Yorumları</h2>
            <div class="row">
                <div class="col-md-4">
                    <div class="testimonial-card">
                        <img src="../assets/images/testimonial1.jpg" alt="Müşteri 1" class="testimonial-img">
                        <p>"Harika bir hizmet! Çiçekler çok taze ve güzel."</p>
                        <h4>Ayşe Y.</h4>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="testimonial-card">
                        <img src="../assets/images/testimonial2.jpg" alt="Müşteri 2" class="testimonial-img">
                        <p>"Özel tasarım isteğimi mükemmel şekilde karşıladılar."</p>
                        <h4>Mehmet K.</h4>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="testimonial-card">
                        <img src="../assets/images/testimonial3.jpg" alt="Müşteri 3" class="testimonial-img">
                        <p>"Hızlı teslimat ve profesyonel hizmet."</p>
                        <h4>Zeynep A.</h4>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Newsletter Section -->
    <section class="newsletter">
        <div class="container">
            <div class="newsletter-content text-center">
                <h2>Kampanyalardan Haberdar Olun</h2>
                <p>Yeni ürünler ve özel teklifler için bültenimize abone olun</p>
                <form class="newsletter-form">
                    <input type="email" placeholder="E-posta adresiniz" required>
                    <button type="submit" class="btn btn-primary">Abone Ol</button>
                </form>
    </div>
</div> 
    </section>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 