<?php
session_start();
require_once '../cods/baglan.php';

// Check if already logged in
if (isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit;
}

// Handle login
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];
    
    $sql = "SELECT * FROM kullanicilar WHERE kullanici_adi = ? AND yetki = 'admin' LIMIT 1";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if ($row = mysqli_fetch_assoc($result)) {
        if (password_verify($password, $row['sifre'])) {
            $_SESSION['admin_id'] = $row['id'];
            $_SESSION['admin_username'] = $row['kullanici_adi'];
            header("Location: index.php");
            exit;
        }
    }
    
    $error = "Geçersiz kullanıcı adı veya şifre!";
    mysqli_stmt_close($stmt);
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giriş - Çiçekçi Yönetim Paneli</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/admin.css" rel="stylesheet">
</head>
<body class="text-center">
    <main class="form-signin">
        <form method="POST" action="">
            <h1 class="h3 mb-3 fw-normal">Yönetim Paneli</h1>
            
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="username" name="username" placeholder="Kullanıcı Adı" required>
                <label for="username">Kullanıcı Adı</label>
            </div>
            
            <div class="form-floating mb-3">
                <input type="password" class="form-control" id="password" name="password" placeholder="Şifre" required>
                <label for="password">Şifre</label>
            </div>
            
            <button class="w-100 btn btn-lg btn-primary" type="submit">Giriş Yap</button>
            
            <p class="mt-5 mb-3 text-muted">&copy; 2024 Çiçekçi</p>
        </form>
    </main>
</body>
</html> 