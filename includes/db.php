<?php
$dsn  = "mysql:host=localhost;dbname=qlks_hotel;charset=utf8mb4";
$user = "root";
$pass = "";                    
try {
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    die("Kết nối thất bại: " . $e->getMessage());
}
?>