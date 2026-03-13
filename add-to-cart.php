<?php
session_start();
require_once 'includes/db.php';

// Bắt buộc đăng nhập trước khi thêm cart
if (!isset($_SESSION['user_id'])) {
    $_SESSION['thongbao'] = "Vui lòng đăng nhập để đặt phòng!";
    header('Location: login.php');
    exit;
}

if (!isset($_POST['add_to_cart'])) {
    header('Location: index.php');
    exit;
}


$id_phong   = (int)$_POST['id_phong'] ?? 0;
$ten_loai   = $_POST['ten_loai'] ?? '';
$gia_dem    = (float)$_POST['gia_dem'];
$so_phong   = (int)$_POST['so_phong'] ?? 1;
$ngay_nhan  = $_POST['ngay_nhan'];
$ngay_tra   = $_POST['ngay_tra'];
$so_dem     = (int)$_POST['so_dem'];
$tong_tien  = (float)$_POST['tong_tien'];

if ($id_phong <= 0 || empty($ten_loai) || $so_phong < 1) {
    $_SESSION['thongbao'] = "Dữ liệu phòng không hợp lệ!";
    header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? 'index.php'));
    exit;
}

$item = [
    'id_phong'   => $id_phong,
    'ten_loai'   => $ten_loai,
    'gia_dem'    => $gia_dem,
    'so_phong'   => $so_phong,
    'ngay_nhan'  => $ngay_nhan,
    'ngay_tra'   => $ngay_tra,
    'so_dem'     => $so_dem,
    'tong_tien'  => $tong_tien
];


$key = $id_phong . '|' . $ngay_nhan . '|' . $ngay_tra;

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if (isset($_SESSION['cart'][$key])) {
    $_SESSION['cart'][$key]['so_phong'] += $so_phong;
    $_SESSION['cart'][$key]['tong_tien'] += $tong_tien;
} else {
    $_SESSION['cart'][$key] = $item;
}

$_SESSION['thongbao_thanhcong'] = "Đã thêm $so_phong phòng $ten_loai vào đặt phòng!";
header('Location: booking.php'); 
exit;
?>