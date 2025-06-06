<?php
// Handle user actions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add':
                $username = mysqli_real_escape_string($conn, $_POST['username']);
                $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                $email = mysqli_real_escape_string($conn, $_POST['email']);
                $yetki = mysqli_real_escape_string($conn, $_POST['yetki']);
                
                $sql = "INSERT INTO kullanicilar (kullanici_adi, sifre, email, yetki) VALUES (?, ?, ?, ?)";
                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, "ssss", $username, $password, $email, $yetki);
                
                if (mysqli_stmt_execute($stmt)) {
                    $success = "Kullanıcı başarıyla eklendi.";
                } else {
                    $error = "Kullanıcı eklenirken bir hata oluştu.";
                }
                mysqli_stmt_close($stmt);
                break;
                
            case 'edit':
                $id = (int)$_POST['id'];
                $username = mysqli_real_escape_string($conn, $_POST['username']);
                $email = mysqli_real_escape_string($conn, $_POST['email']);
                $yetki = mysqli_real_escape_string($conn, $_POST['yetki']);
                
                $sql = "UPDATE kullanicilar SET kullanici_adi = ?, email = ?, yetki = ? WHERE id = ?";
                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, "sssi", $username, $email, $yetki, $id);
                
                if (mysqli_stmt_execute($stmt)) {
                    if (!empty($_POST['password'])) {
                        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                        $sql = "UPDATE kullanicilar SET sifre = ? WHERE id = ?";
                        $stmt = mysqli_prepare($conn, $sql);
                        mysqli_stmt_bind_param($stmt, "si", $password, $id);
                        mysqli_stmt_execute($stmt);
                    }
                    $success = "Kullanıcı başarıyla güncellendi.";
                } else {
                    $error = "Kullanıcı güncellenirken bir hata oluştu.";
                }
                mysqli_stmt_close($stmt);
                break;
                
            case 'delete':
                $id = (int)$_POST['id'];
                
                $sql = "DELETE FROM kullanicilar WHERE id = ?";
                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, "i", $id);
                
                if (mysqli_stmt_execute($stmt)) {
                    $success = "Kullanıcı başarıyla silindi.";
                } else {
                    $error = "Kullanıcı silinirken bir hata oluştu.";
                }
                mysqli_stmt_close($stmt);
                break;
        }
    }
}

// Get all users
$sql = "SELECT * FROM kullanicilar ORDER BY id DESC";
$users = mysqli_query($conn, $sql);
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Kullanıcı Yönetimi</h1>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
            <i class="bi bi-plus"></i> Yeni Kullanıcı
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
                            <th data-sort>Kullanıcı Adı</th>
                            <th data-sort>Email</th>
                            <th data-sort>Yetki</th>
                            <th>İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($user = mysqli_fetch_assoc($users)): ?>
                        <tr>
                            <td><?php echo $user['id']; ?></td>
                            <td><?php echo htmlspecialchars($user['kullanici_adi']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td><?php echo htmlspecialchars($user['yetki']); ?></td>
                            <td>
                                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editUserModal<?php echo $user['id']; ?>">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteUserModal<?php echo $user['id']; ?>">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                        
                        <!-- Edit User Modal -->
                        <div class="modal fade" id="editUserModal<?php echo $user['id']; ?>" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Kullanıcı Düzenle</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form method="POST" action="">
                                        <div class="modal-body">
                                            <input type="hidden" name="action" value="edit">
                                            <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
                                            
                                            <div class="mb-3">
                                                <label class="form-label">Kullanıcı Adı</label>
                                                <input type="text" class="form-control" name="username" value="<?php echo htmlspecialchars($user['kullanici_adi']); ?>" required>
                                            </div>
                                            
                                            <div class="mb-3">
                                                <label class="form-label">Email</label>
                                                <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                                            </div>
                                            
                                            <div class="mb-3">
                                                <label class="form-label">Yeni Şifre (Boş bırakılabilir)</label>
                                                <input type="password" class="form-control" name="password">
                                            </div>
                                            
                                            <div class="mb-3">
                                                <label class="form-label">Yetki</label>
                                                <select class="form-select" name="yetki" required>
                                                    <option value="admin" <?php echo $user['yetki'] == 'admin' ? 'selected' : ''; ?>>Admin</option>
                                                    <option value="user" <?php echo $user['yetki'] == 'user' ? 'selected' : ''; ?>>Kullanıcı</option>
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
                        
                        <!-- Delete User Modal -->
                        <div class="modal fade" id="deleteUserModal<?php echo $user['id']; ?>" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Kullanıcı Sil</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Bu kullanıcıyı silmek istediğinizden emin misiniz?</p>
                                    </div>
                                    <div class="modal-footer">
                                        <form method="POST" action="">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
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

<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Yeni Kullanıcı Ekle</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="">
                <div class="modal-body">
                    <input type="hidden" name="action" value="add">
                    
                    <div class="mb-3">
                        <label class="form-label">Kullanıcı Adı</label>
                        <input type="text" class="form-control" name="username" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Şifre</label>
                        <input type="password" class="form-control" name="password" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Yetki</label>
                        <select class="form-select" name="yetki" required>
                            <option value="admin">Admin</option>
                            <option value="user">Kullanıcı</option>
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