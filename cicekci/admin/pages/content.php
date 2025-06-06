<?php
// Handle content actions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add':
                $baslik = mysqli_real_escape_string($conn, $_POST['baslik']);
                $icerik = mysqli_real_escape_string($conn, $_POST['icerik']);
                $sayfa_kod = mysqli_real_escape_string($conn, $_POST['sayfa_kod']);
                
                $sql = "INSERT INTO site_icerik (baslik, icerik, sayfa_kod) VALUES (?, ?, ?)";
                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, "sss", $baslik, $icerik, $sayfa_kod);
                
                if (mysqli_stmt_execute($stmt)) {
                    $success = "İçerik başarıyla eklendi.";
                } else {
                    $error = "İçerik eklenirken bir hata oluştu.";
                }
                mysqli_stmt_close($stmt);
                break;
                
            case 'edit':
                $id = (int)$_POST['id'];
                $baslik = mysqli_real_escape_string($conn, $_POST['baslik']);
                $icerik = mysqli_real_escape_string($conn, $_POST['icerik']);
                $sayfa_kod = mysqli_real_escape_string($conn, $_POST['sayfa_kod']);
                
                $sql = "UPDATE site_icerik SET baslik = ?, icerik = ?, sayfa_kod = ? WHERE id = ?";
                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, "sssi", $baslik, $icerik, $sayfa_kod, $id);
                
                if (mysqli_stmt_execute($stmt)) {
                    $success = "İçerik başarıyla güncellendi.";
                } else {
                    $error = "İçerik güncellenirken bir hata oluştu.";
                }
                mysqli_stmt_close($stmt);
                break;
                
            case 'delete':
                $id = (int)$_POST['id'];
                
                $sql = "DELETE FROM site_icerik WHERE id = ?";
                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, "i", $id);
                
                if (mysqli_stmt_execute($stmt)) {
                    $success = "İçerik başarıyla silindi.";
                } else {
                    $error = "İçerik silinirken bir hata oluştu.";
                }
                mysqli_stmt_close($stmt);
                break;
        }
    }
}

// Get all content
$sql = "SELECT * FROM site_icerik ORDER BY id DESC";
$contents = mysqli_query($conn, $sql);
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">İçerik Yönetimi</h1>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addContentModal">
            <i class="bi bi-plus"></i> Yeni İçerik
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
                            <th data-sort>Başlık</th>
                            <th data-sort>Sayfa</th>
                            <th>İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($content = mysqli_fetch_assoc($contents)): ?>
                        <tr>
                            <td><?php echo $content['id']; ?></td>
                            <td><?php echo htmlspecialchars($content['baslik']); ?></td>
                            <td><?php echo htmlspecialchars($content['sayfa_kod']); ?></td>
                            <td>
                                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editContentModal<?php echo $content['id']; ?>">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteContentModal<?php echo $content['id']; ?>">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                        
                        <!-- Edit Content Modal -->
                        <div class="modal fade" id="editContentModal<?php echo $content['id']; ?>" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">İçerik Düzenle</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form method="POST" action="">
                                        <div class="modal-body">
                                            <input type="hidden" name="action" value="edit">
                                            <input type="hidden" name="id" value="<?php echo $content['id']; ?>">
                                            
                                            <div class="mb-3">
                                                <label class="form-label">Başlık</label>
                                                <input type="text" class="form-control" name="baslik" value="<?php echo htmlspecialchars($content['baslik']); ?>" required>
                                            </div>
                                            
                                            <div class="mb-3">
                                                <label class="form-label">İçerik</label>
                                                <textarea class="form-control" name="icerik" rows="10" required><?php echo htmlspecialchars($content['icerik']); ?></textarea>
                                            </div>
                                            
                                            <div class="mb-3">
                                                <label class="form-label">Sayfa</label>
                                                <select class="form-select" name="sayfa_kod" required>
                                                    <option value="aboutus" <?php echo $content['sayfa_kod'] == 'aboutus' ? 'selected' : ''; ?>>Hakkımızda</option>
                                                    <option value="contact" <?php echo $content['sayfa_kod'] == 'contact' ? 'selected' : ''; ?>>İletişim</option>
                                                </select>
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
                        
                        <!-- Delete Content Modal -->
                        <div class="modal fade" id="deleteContentModal<?php echo $content['id']; ?>" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">İçerik Sil</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Bu içeriği silmek istediğinizden emin misiniz?</p>
                                    </div>
                                    <div class="modal-footer">
                                        <form method="POST" action="">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="id" value="<?php echo $content['id']; ?>">
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

<!-- Add Content Modal -->
<div class="modal fade" id="addContentModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Yeni İçerik Ekle</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="">
                <div class="modal-body">
                    <input type="hidden" name="action" value="add">
                    
                    <div class="mb-3">
                        <label class="form-label">Başlık</label>
                        <input type="text" class="form-control" name="baslik" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">İçerik</label>
                        <textarea class="form-control" name="icerik" rows="10" required></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Sayfa</label>
                        <select class="form-select" name="sayfa_kod" required>
                            <option value="aboutus">Hakkımızda</option>
                            <option value="contact">İletişim</option>
                        </select>
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