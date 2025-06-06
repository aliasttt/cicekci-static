<?php
require_once 'config.php';

try {
    // Test database connection
    $stmt = $db->query("SELECT * FROM kullanicilar");
    $users = $stmt->fetchAll();
    
    echo "<pre>";
    print_r($users);
    echo "</pre>";
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?> 