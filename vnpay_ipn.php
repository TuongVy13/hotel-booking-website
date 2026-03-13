<?php
require_once './includes/db.php';
require_once './vnpay/vnpay_config.php';

global $vnp_HashSecret;

$inputData = $_GET;
$vnp_SecureHash = $inputData['vnp_SecureHash'] ?? '';
unset($inputData['vnp_SecureHash']);

ksort($inputData);
$hashData = http_build_query($inputData);
$secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);

if ($secureHash === $vnp_SecureHash && $inputData['vnp_ResponseCode'] == '00') {
    $ma_DP = $inputData['vnp_TxnRef'];
    $pdo->prepare("UPDATE datphong SET trang_thai = 'da_thanh_toan' WHERE ma_DP = ?")
        ->execute([$ma_DP]);
    
    // Insert vào bảng thanhtoan nếu cần
    echo "OK"; // Phải trả về OK để VNPAY biết đã nhận
} else {
    echo "FAIL";
}
?>