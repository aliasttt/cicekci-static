<?php
session_start();
require_once '../cods/baglan.php';

// Check if user is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

$link = isset($_GET['link']) ? $_GET['link'] : 'dashboard';
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yönetim Paneli - Çiçekçi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    <link href="css/admin.css" rel="stylesheet">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 d-md-block bg-dark sidebar collapse">
                <div class="position-sticky pt-3">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link <?php echo $link == 'dashboard' ? 'active' : ''; ?>" href="index.php">
                                <i class="bi bi-speedometer2"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo $link == 'users' ? 'active' : ''; ?>" href="index.php?link=users">
                                <i class="bi bi-people"></i> Kullanıcılar
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo $link == 'products' ? 'active' : ''; ?>" href="index.php?link=products">
                                <i class="bi bi-box"></i> Ürünler
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo $link == 'content' ? 'active' : ''; ?>" href="index.php?link=content">
                                <i class="bi bi-file-text"></i> İçerik Yönetimi
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo $link == 'references' ? 'active' : ''; ?>" href="index.php?link=references">
                                <i class="bi bi-star"></i> Referanslar
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo $link == 'messages' ? 'active' : ''; ?>" href="index.php?link=messages">
                                <i class="bi bi-envelope"></i> Mesajlar
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">
                                <i class="bi bi-box-arrow-right"></i> Çıkış
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <?php
                switch($link) {
                    case 'dashboard':
                        require_once 'pages/dashboard.php';
                        break;
                    case 'users':
                        require_once 'pages/users.php';
                        break;
                    case 'products':
                        require_once 'pages/products.php';
                        break;
                    case 'content':
                        require_once 'pages/content.php';
                        break;
                    case 'references':
                        require_once 'pages/references.php';
                        break;
                    case 'messages':
                        require_once 'pages/messages.php';
                        break;
                    default:
                        echo '<div class="alert alert-danger mt-3">Sayfa bulunamadı!</div>';
                        break;
                }
                ?>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/admin.js"></script>
</body>
</html> 