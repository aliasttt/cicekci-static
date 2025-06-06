<?php
require_once 'baglan.php';
?>

<div class="container mt-4">
    <h2 class="mb-4">İletişim</h2>
    
    <div class="row">
        <!-- Contact Form -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <form action="index.php?link=iletisimKaydet" method="POST">
                        <div class="mb-3">
                            <label for="isim" class="form-label">Adınız Soyadınız</label>
                            <input type="text" class="form-control" id="isim" name="isim" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">E-Posta Adresiniz</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="telefon" class="form-label">Telefon</label>
                            <input type="tel" class="form-control" id="telefon" name="telefon">
                        </div>
                        <div class="mb-3">
                            <label for="mesaj" class="form-label">Mesajınız</label>
                            <textarea class="form-control" id="mesaj" name="mesaj" rows="5" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Gönder</button>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Contact Info -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">İletişim Bilgileri</h5>
                    <div class="contact-info">
                        <p><i class="bi bi-geo-alt"></i> Adres: İstanbul, Türkiye</p>
                        <p><i class="bi bi-telephone"></i> Telefon: +90 555 123 4567</p>
                        <p><i class="bi bi-envelope"></i> E-posta: info@cicekci.com</p>
                    </div>
                    
                    <!-- Google Maps -->
                    <div class="mt-4">
                        <iframe 
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d385398.5897809314!2d28.731994399999998!3d41.0049823!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x14caa7040068086b%3A0xe1ccfe98bc01b0d0!2zxLBzdGFuYnVs!5e0!3m2!1str!2str!4v1647881234567!5m2!1str!2str" 
                            width="100%" 
                            height="300" 
                            style="border:0;" 
                            allowfullscreen="" 
                            loading="lazy">
                        </iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 