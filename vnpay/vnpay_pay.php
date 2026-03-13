<?php
require_once 'vnpay_config.php';

function createVNPayUrl($orderId, $amount, $orderInfo) {
    global $vnp_Url, $vnp_TmnCode, $vnp_HashSecret, $vnp_Returnurl;

    date_default_timezone_set('Asia/Ho_Chi_Minh');

     $vnp_TxnRef = $orderId . '_' . time();
    $vnp_Amount = $amount * 100; // VNPAY tính theo đồng (nhân 100)
    $vnp_Locale = 'vn';
    $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];
    $vnp_OrderInfo = $orderInfo;
    $vnp_OrderType = 'billpayment';

    $inputData = array(
        "vnp_Version" => "2.1.0",
        "vnp_TmnCode" => $vnp_TmnCode,
        "vnp_Amount" => $vnp_Amount,
        "vnp_Command" => "pay",
        "vnp_CreateDate" => date('YmdHis'),
        "vnp_CurrCode" => "VND",
        "vnp_IpAddr" => $vnp_IpAddr,
        "vnp_Locale" => $vnp_Locale,
        "vnp_OrderInfo" => $vnp_OrderInfo,
        "vnp_OrderType" => $vnp_OrderType,
        "vnp_ReturnUrl" => $vnp_Returnurl,
        "vnp_TxnRef" => $vnp_TxnRef,
    );

    ksort($inputData);
    $hashdata = http_build_query($inputData);
    $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
    
    $vnp_Url .= '?' . $hashdata . '&vnp_SecureHash=' . $vnpSecureHash;
    return $vnp_Url;
}
?>