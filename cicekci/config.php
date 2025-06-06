<?php
// Database configuration
define('DB_HOST', '127.0.0.1');  // Using IP instead of localhost
define('DB_NAME', 'cicekci');
define('DB_USER', 'root');
define('DB_PASS', '');  // No password

// Create connection
try {
    $db = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USER, DB_PASS);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Set default timezone
date_default_timezone_set('Europe/Istanbul');

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);
?> 