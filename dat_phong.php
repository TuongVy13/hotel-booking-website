<?php
session_start();
require_once 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    $_SESSION['thongbao'] = "Vui lòng đăng nhập để đặt phòng!";
    header('Location: login.php');
    exit;
}

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    $_SESSION['thongbao'] = "Không có phòng nào để đặt!";
    header('Location: index.php');
    exit;
}

if (!isset($_POST['dat_phong'])) {
    header('Location: booking.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$tong_tien = 0;

foreach ($_SESSION['cart'] as $item) {
    $tong_tien += $item['tong_tien'];
}

$phuong_thuc = $_POST['phuong_thuc'] ?? 'tien_mat';

try {
    $pdo->beginTransaction();
   
    $nam = date('Y');
    $stmt = $pdo->query("SELECT ma_DP FROM datphong WHERE ma_DP LIKE 'BOOK-$nam-%' ORDER BY ma_DP DESC LIMIT 1");
    $last = $stmt->fetchColumn();
    $so_thu_tu = $last ? ((int)substr($last, -4) + 1) : 1;
    $ma_dat_phong = sprintf("BOOK-%d-%04d", $nam, $so_thu_tu);

  
    $sql = "INSERT INTO datphong (ma_DP, id_kh, ngay_dat, tong_tien, trang_thai)
            VALUES (:ma_DP, :id_kh, NOW(), :tong_tien, 'cho_xac_nhan')";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':ma_DP' => $ma_dat_phong,
        ':id_kh' => $user_id,
        ':tong_tien' => $tong_tien
    ]);

   
   $sql_detail = "INSERT INTO chitietdatphong 
               (id_datphong, id_phong, ngay_nhan, ngay_tra, gia_phong)
               VALUES (:id_datphong, :id_phong, :ngay_nhan, :ngay_tra, :gia_phong)";

    $stmt_detail = $pdo->prepare($sql_detail);

    foreach ($_SESSION['cart'] as $item) {
        for ($i = 0; $i < $item['so_phong']; $i++) {
            $stmt_detail->execute([
                ':id_datphong' => $ma_dat_phong,
                ':id_phong'    => $item['id_phong'],
                ':ngay_nhan'   => $item['ngay_nhan'],
                ':ngay_tra'    => $item['ngay_tra'],
                ':gia_phong'   => $item['gia_dem']
            ]);
        }
    }
    // Cập nhật phương thức thanh toán
    $sql_update_pttt = "UPDATE datphong SET phuong_thuc_thanh_toan = :pttt WHERE ma_DP = :ma_DP";
    $stmt_pttt = $pdo->prepare($sql_update_pttt);
    $stmt_pttt->execute([':pttt' => $phuong_thuc, ':ma_DP' => $ma_dat_phong]);

    // Nếu chọn VNPay hoặc đã chọn thanh toán ngay → ghi nhận thanh toán thành công
    if ($phuong_thuc === 'vnpay') {
    
        $pdo->commit();
        require_once './vnpay/vnpay_pay.php';

        $orderInfo = "Thanh toan dat phong khach san - Ma don: $ma_dat_phong. Khach hang: " . $_SESSION['ho_ten'];
        $paymentUrl = createVNPayUrl($ma_dat_phong, $tong_tien, $orderInfo);

        echo '<!DOCTYPE html>
        <html>
        <head>
            <title>Đang chuyển đến VNPAY...</title>
            <meta http-equiv="refresh" content="0;url=' . $paymentUrl . '">
            <script>
                window.location.href = "' . $paymentUrl . '";
            </script>
        </head>
        <body>
            <p>Đang chuyển bạn đến cổng thanh toán VNPAY...</p>
        </body>
        </html>';
        exit;
    } else {
        // Các phương thức khác: chờ thanh toán tại quầy hoặc chuyển khoản
        $pdo->prepare("UPDATE datphong SET trang_thai = 'cho_xac_nhan' WHERE ma_DP = :ma_DP")
            ->execute([':ma_DP' => $ma_dat_phong]);
      
    }
          
    $pdo->commit();
    unset($_SESSION['cart']);

    $pttt_text = $phuong_thuc === 'tien_mat' ? 'Tiền mặt tại quầy' : 
                 ($phuong_thuc === 'chuyen_khoan' ? 'Chuyển khoản ngân hàng' : 'VNPay (Online)');
    
    $_SESSION['thongbao_thanhcong'] = "
        Đặt phòng thành công! Mã đặt phòng: <strong>$ma_dat_phong</strong><br>
        Phương thức thanh toán: <strong>$pttt_text</strong><br>
        " . ($phuong_thuc === 'vnpay' ? "Đã thanh toán thành công qua VNPay!" : "Vui lòng thanh toán khi nhận phòng hoặc theo hướng dẫn chuyển khoản.")
    ;

    
    header('Location: dat_phong_thanh_cong.php');
    exit;

} catch (Exception $e) {
  $pdo->rollBack();
    $_SESSION['thongbao'] = "Đặt phòng thất bại: " . $e->getMessage() . " (Line: " . $e->getLine() . ")";
    header('Location: booking.php');
    exit;
}
?>