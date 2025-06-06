<?php
// Get statistics
$stats = array();

// Total users
$sql = "SELECT COUNT(*) as total FROM kullanicilar";
$result = mysqli_query($conn, $sql);
$stats['users'] = mysqli_fetch_assoc($result)['total'];

// Total products
$sql = "SELECT COUNT(*) as total FROM urunler";
$result = mysqli_query($conn, $sql);
$stats['products'] = mysqli_fetch_assoc($result)['total'];

// Total messages
$sql = "SELECT COUNT(*) as total FROM iletisim";
$result = mysqli_query($conn, $sql);
$stats['messages'] = mysqli_fetch_assoc($result)['total'];

// Total references
$sql = "SELECT COUNT(*) as total FROM referanslar";
$result = mysqli_query($conn, $sql);
$stats['references'] = mysqli_fetch_assoc($result)['total'];

// Recent messages
$sql = "SELECT * FROM iletisim ORDER BY tarih DESC LIMIT 5";
$recent_messages = mysqli_query($conn, $sql);

// Recent products
$sql = "SELECT * FROM urunler ORDER BY eklenme_tarihi DESC LIMIT 5";
$recent_products = mysqli_query($conn, $sql);
?>

<div class="container-fluid">
    <h1 class="h3 mb-4">Dashboard</h1>
    
    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card dashboard-card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Kullanıcılar</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $stats['users']; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-people fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card dashboard-card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Ürünler</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $stats['products']; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-box fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card dashboard-card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Mesajlar</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $stats['messages']; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-envelope fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card dashboard-card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Referanslar</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $stats['references']; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-star fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Messages -->
    <div class="row">
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Son Mesajlar</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>İsim</th>
                                    <th>Email</th>
                                    <th>Tarih</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($message = mysqli_fetch_assoc($recent_messages)): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($message['isim']); ?></td>
                                    <td><?php echo htmlspecialchars($message['email']); ?></td>
                                    <td><?php echo date('d.m.Y H:i', strtotime($message['tarih'])); ?></td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Products -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Son Eklenen Ürünler</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Ürün Adı</th>
                                    <th>Fiyat</th>
                                    <th>Tarih</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($product = mysqli_fetch_assoc($recent_products)): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($product['baslik']); ?></td>
                                    <td><?php echo number_format($product['fiyat'], 2); ?> TL</td>
                                    <td><?php echo date('d.m.Y', strtotime($product['eklenme_tarihi'])); ?></td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 