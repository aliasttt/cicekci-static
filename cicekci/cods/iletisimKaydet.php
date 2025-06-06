<?php
require_once 'baglan.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $isim = mysqli_real_escape_string($conn, $_POST['isim']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $telefon = mysqli_real_escape_string($conn, $_POST['telefon']);
    $mesaj = mysqli_real_escape_string($conn, $_POST['mesaj']);
    
    // Check required fields
    if (empty($isim) || empty($email) || empty($mesaj)) {
        echo "<script>
            alert('Lütfen gerekli alanları doldurun!');
            window.location.href = 'index.php?link=contact';
        </script>";
        exit;
    }
    
    // Insert into database
    $sql = "INSERT INTO iletisim (isim, email, telefon, mesaj) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ssss", $isim, $email, $telefon, $mesaj);
    
    if (mysqli_stmt_execute($stmt)) {
        echo "<script>
            alert('Mesajınız başarıyla gönderildi!');
            window.location.href = 'index.php?link=contact';
        </script>";
    } else {
        echo "<script>
            alert('Bir hata oluştu. Lütfen tekrar deneyin!');
            window.location.href = 'index.php?link=contact';
        </script>";
    }
    
    mysqli_stmt_close($stmt);
} else {
    header("Location: index.php?link=contact");
    exit;
}
?> 