<?php
session_start();

if (!isset($_GET['key']) || trim($_GET['key']) === '') {
    $_SESSION['thongbao'] = "Không xác định được phòng cần xóa!";
    header('Location: booking.php');
    exit;
}

$key = $_GET['key'];


if (isset($_SESSION['cart'][$key])) {
    // Lấy tên phòng để thông báo chi tiết hơn (nếu có)
    $ten_phong = $_SESSION['cart'][$key]['ten_phong'] ?? 
                 $_SESSION['cart'][$key]['loai_phong'] ?? 
                 'phòng này';


    unset($_SESSION['cart'][$key]);

    $_SESSION['thongbao_thanhcong'] = "Đã xóa <strong>$ten_phong</strong> khỏi giỏ hàng thành công!";
} else {
    $_SESSION['thongbao'] = "Không tìm thấy loại phòng này trong giỏ hàng!";
}


header('Location: booking.php');
exit;
?>