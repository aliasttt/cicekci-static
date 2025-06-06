<?php
require_once 'baglan.php';
?>

<div class="container mt-4">
    <h2 class="mb-4">Hakkımızda</h2>
    
    <?php
    $sql = "SELECT * FROM site_icerik WHERE sayfa_kod = 'aboutus' ORDER BY eklenme_tarihi DESC";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {
            ?>
            <div class="card mb-4">
                <div class="card-body">
                    <?php if (!empty($row['icerik_title'])) { ?>
                        <h3 class="card-title"><?php echo htmlspecialchars($row['icerik_title']); ?></h3>
                    <?php } ?>
                    <div class="card-text">
                        <?php echo $row['icerik_txt']; ?>
                    </div>
                </div>
            </div>
            <?php
        }
    } else {
        ?>
        <div class="card">
            <div class="card-body">
                <h3>Bizim Hikayemiz</h3>
                <p>
                    1950 yılında İstanbul'da küçük bir çiçekçi dükkanı olarak başladık. 
                    Yıllar içinde büyüdük ve geliştik, ancak kalite ve müşteri memnuniyeti 
                    anlayışımız hiç değişmedi.
                </p>
                <h3>Misyonumuz</h3>
                <p>
                    En taze çiçekleri, en uygun fiyatlarla müşterilerimize sunmak ve 
                    her özel anı daha da özel kılmak için çalışıyoruz.
                </p>
                <h3>Vizyonumuz</h3>
                <p>
                    Türkiye'nin en güvenilir ve tercih edilen çiçekçi markası olmak 
                    ve müşterilerimize en iyi hizmeti sunmak için çalışmaya devam ediyoruz.
                </p>
            </div>
        </div>
        <?php
    }
    ?>
</div> 