<?php
// Handle product actions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add':
                $baslik = mysqli_real_escape_string($conn, $_POST['baslik']);
                $aciklama = mysqli_real_escape_string($conn, $_POST['aciklama']);
                $fiyat = (float)$_POST['fiyat'];
                
                // Handle image upload
                $resim = '';
                if (isset($_FILES['resim']) && $_FILES['resim']['error'] == 0) {
                    $target_dir = "../assets/img/products/";
                    $file_extension = strtolower(pathinfo($_FILES["resim"]["name"], PATHINFO_EXTENSION));
                    $new_filename = uniqid() . '.' . $file_extension;
                    $target_file = $target_dir . $new_filename;
                    
                    if (move_uploaded_file($_FILES["resim"]["tmp_name"], $target_file)) {
                        $resim = $new_filename;
                    }
                }
                
                $sql = "INSERT INTO urunler (baslik, aciklama, fiyat, resim) VALUES (?, ?, ?, ?)";
                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, "ssds", $baslik, $aciklama, $fiyat, $resim);
                
                if (mysqli_stmt_execute($stmt)) {
                    $success = "Ürün başarıyla eklendi.";
                } else {
                    $error = "Ürün eklenirken bir hata oluştu.";
                }
                mysqli_stmt_close($stmt);
                break;
                
            case 'edit':
                $id = (int)$_POST['id'];
                $baslik = mysqli_real_escape_string($conn, $_POST['baslik']);
                $aciklama = mysqli_real_escape_string($conn, $_POST['aciklama']);
                $fiyat = (float)$_POST['fiyat'];
                
                // Handle image upload
                $resim = $_POST['current_image'];
                if (isset($_FILES['resim']) && $_FILES['resim']['error'] == 0) {
                    $target_dir = "../assets/img/products/";
                    $file_extension = strtolower(pathinfo($_FILES["resim"]["name"], PATHINFO_EXTENSION));
                    $new_filename = uniqid() . '.' . $file_extension;
                    $target_file = $target_dir . $new_filename;
                    
                    if (move_uploaded_file($_FILES["resim"]["tmp_name"], $target_file)) {
                        // Delete old image
                        if (!empty($resim) && file_exists($target_dir . $resim)) {
                            unlink($target_dir . $resim);
                        }
                        $resim = $new_filename;
                    }
                }
                
                $sql = "UPDATE urunler SET baslik = ?, aciklama = ?, fiyat = ?, resim = ? WHERE id = ?";
                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, "ssdsi", $baslik, $aciklama, $fiyat, $resim, $id);
                
                if (mysqli_stmt_execute($stmt)) {
                    $success = "Ürün başarıyla güncellendi.";
                } else {
                    $error = "Ürün güncellenirken bir hata oluştu.";
                }
                mysqli_stmt_close($stmt);
                break;
                
            case 'delete':
                $id = (int)$_POST['id'];
                
                // Get image filename
                $sql = "SELECT resim FROM urunler WHERE id = ?";
                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, "i", $id);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                $row = mysqli_fetch_assoc($result);
                
                // Delete image file
                if (!empty($row['resim'])) {
                    $target_dir = "../assets/img/products/";
                    if (file_exists($target_dir . $row['resim'])) {
                        unlink($target_dir . $row['resim']);
                    }
                }
                
                // Delete from database
                $sql = "DELETE FROM urunler WHERE id = ?";
                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, "i", $id);
                
                if (mysqli_stmt_execute($stmt)) {
                    $success = "Ürün başarıyla silindi.";
                } else {
                    $error = "Ürün silinirken bir hata oluştu.";
                }
                mysqli_stmt_close($stmt);
                break;
        }
    }
}

// Get all products
$sql = "SELECT * FROM urunler ORDER BY id DESC";
$products = mysqli_query($conn, $sql);
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Ürün Yönetimi</h1>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProductModal">
            <i class="bi bi-plus"></i> Yeni Ürün
        </button>
    </div>
    
    <?php if (isset($success)): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>
    
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered sortable">
                    <thead>
                        <tr>
                            <th data-sort>ID</th>
                            <th>Resim</th>
                            <th data-sort>Başlık</th>
                            <th data-sort>Fiyat</th>
                            <th>İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($product = mysqli_fetch_assoc($products)): ?>
                        <tr>
                            <td><?php echo $product['id']; ?></td>
                            <td>
                                <?php if (!empty($product['resim'])): ?>
                                    <img src="../assets/img/products/<?php echo $product['resim']; ?>" alt="<?php echo htmlspecialchars($product['baslik']); ?>" class="img-preview">
                                <?php else: ?>
                                    <span class="text-muted">Resim yok</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($product['baslik']); ?></td>
                            <td><?php echo number_format($product['fiyat'], 2); ?> TL</td>
                            <td>
                                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editProductModal<?php echo $product['id']; ?>">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteProductModal<?php echo $product['id']; ?>">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                        
                        <!-- Edit Product Modal -->
                        <div class="modal fade" id="editProductModal<?php echo $product['id']; ?>" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Ürün Düzenle</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form method="POST" action="" enctype="multipart/form-data">
                                        <div class="modal-body">
                                            <input type="hidden" name="action" value="edit">
                                            <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
                                            <input type="hidden" name="current_image" value="<?php echo $product['resim']; ?>">
                                            
                                            <div class="mb-3">
                                                <label class="form-label">Başlık</label>
                                                <input type="text" class="form-control" name="baslik" value="<?php echo htmlspecialchars($product['baslik']); ?>" required>
                                            </div>
                                            
                                            <div class="mb-3">
                                                <label class="form-label">Açıklama</label>
                                                <textarea class="form-control" name="aciklama" rows="3" required><?php echo htmlspecialchars($product['aciklama']); ?></textarea>
                                            </div>
                                            
                                            <div class="mb-3">
                                                <label class="form-label">Fiyat</label>
                                                <input type="number" class="form-control" name="fiyat" step="0.01" value="<?php echo $product['fiyat']; ?>" required>
                                            </div>
                                            
                                            <div class="mb-3">
                                                <label class="form-label">Resim</label>
                                                <?php if (!empty($product['resim'])): ?>
                                                    <div class="mb-2">
                                                        <img src="../assets/img/products/<?php echo $product['resim']; ?>" alt="Current image" class="img-preview">
                                                    </div>
                                                <?php endif; ?>
                                                <input type="file" class="form-control" name="resim" accept="image/*">
                                                <small class="text-muted">Yeni resim yüklemezseniz mevcut resim korunacaktır.</small>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                                            <button type="submit" class="btn btn-primary">Kaydet</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Delete Product Modal -->
                        <div class="modal fade" id="deleteProductModal<?php echo $product['id']; ?>" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Ürün Sil</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Bu ürünü silmek istediğinizden emin misiniz?</p>
                                    </div>
                                    <div class="modal-footer">
                                        <form method="POST" action="">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                                            <button type="submit" class="btn btn-danger">Sil</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Product Modal -->
<div class="modal fade" id="addProductModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Yeni Ürün Ekle</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" name="action" value="add">
                    
                    <div class="mb-3">
                        <label class="form-label">Başlık</label>
                        <input type="text" class="form-control" name="baslik" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Açıklama</label>
                        <textarea class="form-control" name="aciklama" rows="3" required></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Fiyat</label>
                        <input type="number" class="form-control" name="fiyat" step="0.01" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Resim</label>
                        <input type="file" class="form-control" name="resim" accept="image/*" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                    <button type="submit" class="btn btn-primary">Ekle</button>
                </div>
            </form>
        </div>
    </div>
</div> 