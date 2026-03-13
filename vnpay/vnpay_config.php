<?php
// Cấu hình VNPAY Sandbox - thay bằng thông tin bạn nhận được từ email
$vnp_TmnCode = "UX0NI364"; // Ví dụ: 2QXUI4B4
$vnp_HashSecret = "LURUIHIN07LHX4H9LPNIFWVQZVQ54YDZ"; // Ví dụ: RAOEXROVJNKQJGBLWHJERYBOHIXYUWPF
$vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
$vnp_Returnurl = "http://localhost/DOANWEB/vnpay_return.php"; // URL trang trả về kết quả (sẽ tạo)
$vnp_Ipnurl = "http://localhost/DOANWEB/vnpay_ipn.php"; // URL nhận thông báo từ VNPAY (quan trọng nhất)
?>