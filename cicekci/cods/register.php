<?php
session_start();
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $full_name = $_POST['full_name'];
    
    $errors = [];
    
    // Validation
    if (strlen($username) < 3) {
        $errors[] = "Kullanıcı adı en az 3 karakter olmalıdır.";
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Geçerli bir email adresi giriniz.";
    }
    
    if (strlen($password) < 6) {
        $errors[] = "Şifre en az 6 karakter olmalıdır.";
    }
    
    if ($password !== $confirm_password) {
        $errors[] = "Şifreler eşleşmiyor.";
    }
    
    // Check if username or email already exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $errors[] = "Bu kullanıcı adı veya email zaten kullanılıyor.";
    }
    
    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        $stmt = $conn->prepare("INSERT INTO users (username, email, password, full_name) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $username, $email, $hashed_password, $full_name);
        
        if ($stmt->execute()) {
            $_SESSION['success'] = "Kayıt başarılı! Şimdi giriş yapabilirsiniz.";
            header("Location: index.php?link=login");
            exit();
        } else {
            $errors[] = "Bir hata oluştu. Lütfen tekrar deneyin.";
        }
    }
}
?>

<div class="container">
    <div class="auth-form">
        <h2 class="text-center mb-4">Kayıt Ol</h2>
        
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo $error; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="mb-3">
                <label for="username" class="form-label">Kullanıcı Adı</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            
            <div class="mb-3">
                <label for="full_name" class="form-label">Ad Soyad</label>
                <input type="text" class="form-control" id="full_name" name="full_name" required>
            </div>
            
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            
            <div class="mb-3">
                <label for="password" class="form-label">Şifre</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            
            <div class="mb-3">
                <label for="confirm_password" class="form-label">Şifre Tekrar</label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
            </div>
            
            <button type="submit" class="btn btn-primary w-100">Kayıt Ol</button>
        </form>
        
        <div class="text-center mt-3">
            <p>Zaten hesabınız var mı? <a href="index.php?link=login">Giriş Yap</a></p>
        </div>
    </div>
</div> 