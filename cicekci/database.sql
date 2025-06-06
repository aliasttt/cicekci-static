-- Create database
CREATE DATABASE IF NOT EXISTS cicekci CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE cicekci;

-- Drop existing tables if they exist
DROP TABLE IF EXISTS siparis_urunler;
DROP TABLE IF EXISTS siparisler;
DROP TABLE IF EXISTS urunler;
DROP TABLE IF EXISTS kullanicilar;

-- Create kullanicilar table
CREATE TABLE kullanicilar (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ad_soyad VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    sifre VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create urunler table
CREATE TABLE urunler (
    id INT AUTO_INCREMENT PRIMARY KEY,
    baslik VARCHAR(100) NOT NULL,
    aciklama TEXT,
    fiyat DECIMAL(10,2) NOT NULL,
    resim VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create siparisler table
CREATE TABLE siparisler (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kullanici_id INT NOT NULL,
    toplam_tutar DECIMAL(10,2) NOT NULL,
    durum ENUM('beklemede', 'tamamlandi', 'iptal') DEFAULT 'beklemede',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (kullanici_id) REFERENCES kullanicilar(id)
);

-- Create siparis_urunler table
CREATE TABLE siparis_urunler (
    id INT AUTO_INCREMENT PRIMARY KEY,
    siparis_id INT NOT NULL,
    urun_id INT NOT NULL,
    miktar INT NOT NULL,
    fiyat DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (siparis_id) REFERENCES siparisler(id),
    FOREIGN KEY (urun_id) REFERENCES urunler(id)
);

-- Create site content table
CREATE TABLE IF NOT EXISTS site_icerik (
    id INT AUTO_INCREMENT PRIMARY KEY,
    baslik VARCHAR(255) NOT NULL,
    icerik TEXT NOT NULL,
    sayfa_kod VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create references table
CREATE TABLE IF NOT EXISTS referanslar (
    id INT AUTO_INCREMENT PRIMARY KEY,
    baslik VARCHAR(255) NOT NULL,
    aciklama TEXT,
    resim VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create contact messages table
CREATE TABLE IF NOT EXISTS iletisim (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ad_soyad VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    telefon VARCHAR(20),
    mesaj TEXT NOT NULL,
    tarih TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert default admin user (password: admin123)
INSERT INTO kullanicilar (username, password, email, yetki) VALUES 
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@example.com', 'admin');

-- Insert sample content
INSERT INTO site_icerik (baslik, icerik, sayfa_kod) VALUES 
('Hakkımızda', 'Çiçekçi firmamız 20 yıldır hizmet vermektedir.', 'aboutus'),
('İletişim', 'Bize ulaşmak için aşağıdaki formu kullanabilirsiniz.', 'contact');

-- Insert sample products
INSERT INTO urunler (baslik, aciklama, fiyat, resim) VALUES
('Kırmızı Gül Buketi', '12 adet kırmızı gülden oluşan lüks buket', 299.99, 'https://images.pexels.com/photos/103573/pexels-photo-103573.jpeg'),
('Orkide', 'Beyaz orkide çiçeği', 199.99, 'https://images.pexels.com/photos/1904769/pexels-photo-1904769.jpeg'),
('Papatya Buketi', 'Renkli papatyalardan oluşan buket', 149.99, 'https://images.pexels.com/photos/736230/pexels-photo-736230.jpeg'),
('Lale Buketi', 'Renkli lalelerden oluşan buket', 249.99, 'https://images.pexels.com/photos/1458694/pexels-photo-1458694.jpeg'),
('Menekşe', 'Mor menekşe çiçeği', 89.99, 'https://images.pexels.com/photos/1904769/pexels-photo-1904769.jpeg'),
('Gül Buketi', 'Pembe ve beyaz güllerden oluşan buket', 279.99, 'https://images.pexels.com/photos/103573/pexels-photo-103573.jpeg'),
('Orkide Buketi', 'Renkli orkidelerden oluşan buket', 329.99, 'https://images.pexels.com/photos/1904769/pexels-photo-1904769.jpeg'),
('Papatya Buketi', 'Sarı papatyalardan oluşan buket', 179.99, 'https://images.pexels.com/photos/736230/pexels-photo-736230.jpeg'),
('Lale Buketi', 'Kırmızı lalelerden oluşan buket', 229.99, 'https://images.pexels.com/photos/1458694/pexels-photo-1458694.jpeg'),
('Menekşe Buketi', 'Mor menekşelerden oluşan buket', 159.99, 'https://images.pexels.com/photos/1904769/pexels-photo-1904769.jpeg');

-- Insert sample references
INSERT INTO referanslar (baslik, aciklama, resim) VALUES 
('Düğün Organizasyonu', 'Özel düğün organizasyonu', 'dugun.jpg'),
('Kurumsal Etkinlik', 'Şirket etkinliği organizasyonu', 'etkinlik.jpg');

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert test user (password: test123)
INSERT INTO users (username, email, password, full_name) VALUES 
('test', 'test@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Test User');

-- Insert admin user (password: admin123)
INSERT INTO users (username, email, password, full_name, yetki) VALUES 
('admin', 'admin@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Admin User', 'admin'); 