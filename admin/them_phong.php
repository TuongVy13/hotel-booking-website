<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$id_admin = $_SESSION['user_id'];
$ten     = trim($_POST['ten_phong'] ?? '');
$loai    = $_POST['loai_phong'] ?? '';
$gia     = $_POST['gia_mac_dinh'] ?? 0;
$mota    = $_POST['mo_ta'] ?? null;
$tiennghi = $_POST['tiennghi'] ?? [];


if (empty($ten) || empty($loai) || $gia <= 0) {
    $_SESSION['error'] = "Vui lòng điền đầy đủ thông tin bắt buộc.";
    header("Location: quanly_phong.php");
    exit();
}

try {
    $pdo->beginTransaction();

   
    $stmt = $pdo->prepare("
        INSERT INTO phong 
        (ten_phong, loai_phong, gia_mac_dinh, mo_ta, trang_thai, id_admin, ngay_tao) 
        VALUES (?, ?, ?, ?, 'Trống', ?, NOW())
    ");
    $stmt->execute([$ten, $loai, $gia, $mota, $id_admin]);

    $id_phong = $pdo->lastInsertId();

  
    $stmt= $pdo->prepare("INSERT INTO phong_tiennghi (maphong, matn) VALUES (?, ?)");
    foreach ($tiennghi as $matn) {
        $stmt->execute([$id_phong, $matn]);
    }
    
    $pdo->commit();

    $_SESSION['success'] = "Thêm phòng thành công!";

} catch (Exception $e) {
    $pdo->rollBack();
    $_SESSION['error'] = "Lỗi khi thêm phòng. Vui lòng thử lại.";
   
}

header("Location: quanly_phong.php");
exit();