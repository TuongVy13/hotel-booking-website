<?php
session_start();
require_once '../includes/db.php';
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}


$id = $_POST['id_phong'];
$ten = $_POST['ten_phong'];
$loai = $_POST['loai_phong'];
$gia = $_POST['gia_mac_dinh'];
$trangthai = $_POST['trang_thai'];
$mota = $_POST['mo_ta'] ?? null;
$tiennghi = $_POST['tiennghi'] ?? [];

$pdo->prepare("UPDATE phong SET ten_phong=?, loai_phong=?, gia_mac_dinh=?, trang_thai=?, mo_ta=? WHERE id_phong=?")
    ->execute([$ten, $loai, $gia, $trangthai, $mota, $id]);


$pdo->prepare("DELETE FROM phong_tiennghi WHERE maphong = ?")->execute([$id]);


foreach ($tiennghi as $matn) {
    $pdo->prepare("INSERT INTO phong_tiennghi (maphong, matn) VALUES (?, ?)")->execute([$id, $matn]);
}

header("Location: quanly_phong.php");
exit();