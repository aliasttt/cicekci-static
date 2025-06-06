<?php
require_once 'baglan.php';
?>

<div class="container mt-4">
    <h2 class="mb-4">Referanslarımız</h2>
    
    <div class="row">
        <?php
        $sql = "SELECT * FROM referanslar ORDER BY eklenme_tarihi DESC";
        $result = mysqli_query($conn, $sql);
        
        if (mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) {
                ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <img src="assets/img/<?php echo htmlspecialchars($row['resim']); ?>" 
                                 class="ref-img mb-3" 
                                 alt="<?php echo htmlspecialchars($row['baslik']); ?>">
                            <h5 class="card-title"><?php echo htmlspecialchars($row['baslik']); ?></h5>
                            <p class="card-text">
                                <?php echo nl2br(htmlspecialchars($row['aciklama'])); ?>
                            </p>
                        </div>
                    </div>
                </div>
                <?php
            }
        } else {
            echo '<div class="col-12"><p class="text-center">Henüz referans eklenmemiş.</p></div>';
        }
        ?>
    </div>
</div> 