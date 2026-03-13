<?php
// session_start();
// require_once './includes/db.php';

// if (!isset($_SESSION['user_id'])) {
//     header('Location: login.php');
//     exit;
// }
// $vnp_TxnRef = $_GET['vnp_TxnRef'] ?? '';
// $vnp_ResponseCode = $_GET['vnp_ResponseCode'] ?? '';

// $parts = explode('_', $vnp_TxnRef);
// $order_code = $parts[0]; 

// if ($vnp_ResponseCode == '00') {
//   $sql = "UPDATE datphong 
//             SET trang_thai = 'da_thanh_toan'
//             WHERE ma_DP = ? ";
    
//     $stmt = $pdo->prepare($sql);
   
//     if ($stmt->execute([$order_code])) {
//         if ($stmt->rowCount() > 0) {
//             $_SESSION['thongbao_thanhcong'] = "Thanh toán thành công cho đơn hàng $order_code.";
//         } else {
//             $_SESSION['thongbao'] = "Thanh toán VNPay thành công nhưng không tìm thấy đơn hàng $order_code trong hệ thống.";
//         }
//     } else {
//         $_SESSION['thongbao'] = "Lỗi cập nhật trạng thái thanh toán. Vui lòng liên hệ hỗ trợ.";
//     }
    
//     header('Location: dat_phong_thanh_cong.php');
//     exit;
// } else {
//     $_SESSION['thongbao_thatbai'] = "Thanh toán thất bại cho đơn hàng $order_code.";
// }


session_start();
require_once './includes/db.php';

// ====================== DEBUG CHI TIẾT ======================
file_put_contents('debug_vnpay.txt', 
    "=== " . date('Y-m-d H:i:s') . " ===\n" .
    "GET params: " . print_r($_GET, true) . "\n" .
    "SESSION user_id: " . ($_SESSION['user_id'] ?? 'không có') . "\n",
    FILE_APPEND
);
// ==========================================================

$vnp_TxnRef = $_GET['vnp_TxnRef'] ?? '';
$vnp_ResponseCode = $_GET['vnp_ResponseCode'] ?? '';

file_put_contents('debug_vnpay.txt', 
    "vnp_TxnRef: $vnp_TxnRef\n" .
    "vnp_ResponseCode: $vnp_ResponseCode\n",
    FILE_APPEND
);

if ($vnp_ResponseCode !== '00') {
    $_SESSION['thongbao'] = "Thanh toán thất bại (mã: $vnp_ResponseCode)";
    header('Location: lichsu_datphong.php');
    exit;
}

// Tách mã đặt phòng
$parts = explode('_', $vnp_TxnRef);
$order_code = $parts[0] ?? '';

file_put_contents('debug_vnpay.txt', 
    "Sau khi explode: " . print_r($parts, true) . "\n" .
    "order_code lấy được: '$order_code'\n",
    FILE_APPEND
);

// Kiểm tra xem trong CSDL có đơn này không, trạng thái hiện tại là gì
$sql_check = "SELECT ma_DP, trang_thai FROM datphong WHERE ma_DP = ?";
$stmt_check = $pdo->prepare($sql_check);
$stmt_check->execute([$order_code]);
$row = $stmt_check->fetch(PDO::FETCH_ASSOC);

file_put_contents('debug_vnpay.txt', 
    "Kết quả tìm đơn trong CSDL: " . print_r($row, true) . "\n",
    FILE_APPEND
);

// Thực hiện UPDATE
$sql = "UPDATE datphong SET trang_thai = 'da_thanh_toan' WHERE ma_DP = ?";
$stmt = $pdo->prepare($sql);
$execute_ok = $stmt->execute([$order_code]);

file_put_contents('debug_vnpay.txt', 
    "Execute UPDATE: " . ($execute_ok ? 'THÀNH CÔNG' : 'THẤT BẠI') . "\n" .
    "Số dòng bị ảnh hưởng (rowCount): " . $stmt->rowCount() . "\n\n",
    FILE_APPEND
);

// Set thông báo theo kết quả thực tế
if ($stmt->rowCount() > 0) {
    $_SESSION['thongbao'] = "Thanh toán thành công! Đơn $order_code đã được cập nhật trạng thái ĐÃ THANH TOÁN.";
} else {
    $_SESSION['thongbao'] = "Cảnh báo nghiêm trọng: VNPay báo thành công nhưng KHÔNG cập nhật được trạng thái đơn $order_code!";
}

header('Location: dat_phong_thanh_cong.php');
exit;

?>