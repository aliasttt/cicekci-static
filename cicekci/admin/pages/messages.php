<?php
// Handle message actions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'delete':
                $id = (int)$_POST['id'];
                
                $sql = "DELETE FROM iletisim WHERE id = ?";
                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, "i", $id);
                
                if (mysqli_stmt_execute($stmt)) {
                    $success = "Mesaj başarıyla silindi.";
                } else {
                    $error = "Mesaj silinirken bir hata oluştu.";
                }
                mysqli_stmt_close($stmt);
                break;
        }
    }
}

// Get all messages
$sql = "SELECT * FROM iletisim ORDER BY id DESC";
$messages = mysqli_query($conn, $sql);
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Mesaj Yönetimi</h1>
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
                            <th data-sort>Ad Soyad</th>
                            <th data-sort>E-posta</th>
                            <th data-sort>Telefon</th>
                            <th data-sort>Tarih</th>
                            <th>İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($message = mysqli_fetch_assoc($messages)): ?>
                        <tr>
                            <td><?php echo $message['id']; ?></td>
                            <td><?php echo htmlspecialchars($message['ad_soyad']); ?></td>
                            <td><?php echo htmlspecialchars($message['email']); ?></td>
                            <td><?php echo htmlspecialchars($message['telefon']); ?></td>
                            <td><?php echo date('d.m.Y H:i', strtotime($message['tarih'])); ?></td>
                            <td>
                                <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#viewMessageModal<?php echo $message['id']; ?>">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteMessageModal<?php echo $message['id']; ?>">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                        
                        <!-- View Message Modal -->
                        <div class="modal fade" id="viewMessageModal<?php echo $message['id']; ?>" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Mesaj Detayı</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Ad Soyad</label>
                                            <p><?php echo htmlspecialchars($message['ad_soyad']); ?></p>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">E-posta</label>
                                            <p><?php echo htmlspecialchars($message['email']); ?></p>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Telefon</label>
                                            <p><?php echo htmlspecialchars($message['telefon']); ?></p>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Tarih</label>
                                            <p><?php echo date('d.m.Y H:i', strtotime($message['tarih'])); ?></p>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Mesaj</label>
                                            <p><?php echo nl2br(htmlspecialchars($message['mesaj'])); ?></p>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Delete Message Modal -->
                        <div class="modal fade" id="deleteMessageModal<?php echo $message['id']; ?>" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Mesaj Sil</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Bu mesajı silmek istediğinizden emin misiniz?</p>
                                    </div>
                                    <div class="modal-footer">
                                        <form method="POST" action="">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="id" value="<?php echo $message['id']; ?>">
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
 