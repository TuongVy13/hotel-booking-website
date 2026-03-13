<?php 
require_once 'includes/header.php';

if (!isset($_SESSION['user_id'])) {
    $_SESSION['thongbao'] = "Vui lòng đăng nhập để xem giỏ hàng!";
    header('Location: login.php');
    exit;
}

$customerName = $_SESSION['ho_ten'] ?? 'Khách hàng';
$tongPhong = 0;
$tongTien  = 0;

foreach ($_SESSION['cart'] as $item) {
    $tongPhong += $item['so_phong'];
    $tongTien  += $item['tong_tien'];
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Giỏ hàng đặt phòng - Khách sạn ABC</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        .cart-item { transition: all 0.3s; }
        .cart-item:hover { box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
    </style>
</head>
<body class="bg-light">

<div class="container py-5">
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-shopping-cart"></i> 
                        Giỏ hàng của bạn • <?= $tongPhong ?> phòng
                    </h4>
                </div>
                <div class="card-body p-0">
                    <?php foreach ($_SESSION['cart'] as $key => $item): ?>
                    <div class="cart-item border-bottom p-4">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h5 class="mb-1 fw-bold text-primary"><?= htmlspecialchars($item['ten_loai']) ?></h5>
                                <p class="mb-2 text-muted">
                                    <i class="far fa-calendar-alt"></i> 
                                    <?= date('d/m/Y', strtotime($item['ngay_nhan'])) ?> → 
                                    <?= date('d/m/Y', strtotime($item['ngay_tra'])) ?> 
                                    <span class="badge bg-info ms-2"><?= $item['so_dem'] ?> đêm</span>
                                </p>
                                <p class="mb-0">
                                    <strong><?= $item['so_phong'] ?> phòng</strong> × 
                                    <?= number_format($item['gia_dem']) ?>₫/đêm
                                </p>
                            </div>
                            <div class="col-md-4 text-end">
                                <h4 class="text-danger fw-bold mb-3">
                                    <?= number_format($item['tong_tien']) ?>₫
                                </h4>
                                <a href="xoa_cart.php?key=<?= urlencode($key) ?>" 
                                   class="btn btn-sm btn-outline-danger"
                                   onclick="return confirm('Xóa loại phòng này khỏi giỏ hàng?')">
                                    <i class="fas fa-trash"></i> Xóa
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="mt-4">
                <a href="index.php" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i> Tiếp tục chọn phòng
                </a>
            </div>
        </div>

       
        <div class="col-lg-4">
            <div class="card shadow-sm sticky-top" style="top: 20px;">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Tóm tắt đơn hàng</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Tổng số phòng:</span>
                        <strong><?= $tongPhong ?> phòng</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span>Tổng số đêm:</span>
                        <strong><?= $_SESSION['cart'][array_key_first($_SESSION['cart'])]['so_dem'] ?? 0 ?> đêm</strong>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between fs-4 fw-bold text-danger">
                        <span>TỔNG TIỀN</span>
                        <span><?= number_format($tongTien) ?>₫</span>
                    </div>
                </div>
                <div class="card-footer bg-white">
                    <button type="button" class="btn btn-success btn-lg w-100 fw-bold" data-bs-toggle="modal" data-bs-target="#confirmModal">
                        <i class="fas fa-credit-card"></i> TIẾN HÀNH ĐẶT PHÒNG
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="confirmModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title">Xác nhận đặt phòng & Thanh toán</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="text-center py-3">
          <i class="fas fa-check-circle text-success fa-3x mb-3"></i>
          <h4>Bạn muốn đặt <?= $tongPhong ?> phòng?</h4>
          <p class="fs-5 text-danger fw-bold">Tổng tiền: <?= number_format($tongTien) ?>₫</p>
        </div>

        <hr>

        <h6 class="mb-3">Chọn phương thức thanh toán</h6>
        <form method="post" action="dat_phong.php" id="paymentForm">
          <div class="row g-3">
            <div class="col-md-4">
              <div class="form-check">
                <input class="form-check-input" type="radio" name="phuong_thuc" id="tienmat" value="tien_mat" checked>
                <label class="form-check-label border p-3 rounded text-center w-100" for="tienmat">
                  <i class="fas fa-money-bill-wave fa-2x text-success mb-2"></i><br>
                  <strong>Tiền mặt</strong><br>
                  <small>Thanh toán tại quầy</small>
                </label>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-check">
                <input class="form-check-input" type="radio" name="phuong_thuc" id="chuyenkhoan" value="chuyen_khoan">
                <label class="form-check-label border p-3 rounded text-center w-100" for="chuyenkhoan">
                  <i class="fas fa-university fa-2x text-primary mb-2"></i><br>
                  <strong>Chuyển khoản</strong><br>
                  <small>Ngân hàng</small>
                </label>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-check">
                <input class="form-check-input" type="radio" name="phuong_thuc" id="vnpay" value="vnpay">
                <label class="form-check-label border p-3 rounded text-center w-100" for="vnpay">
                  <i class="fas fa-credit-card fa-2x text-danger mb-2"></i><br>
                  <strong>VNPay</strong><br>
                  <small>Thanh toán online</small>
                </label>
              </div>
            </div>
          </div>

          <!-- Thông tin chuyển khoản (hiển thị khi chọn chuyển khoản) -->
          <div id="thongtin_chuyenkhoan" class="mt-4 p-3 bg-light rounded d-none">
            <h6>Thông tin chuyển khoản</h6>
            <p><strong>Ngân hàng:</strong> Vietcombank</p>
            <p><strong>Chủ tài khoản:</strong> KHÁCH SẠN ABC</p>
            <p><strong>Số tài khoản:</strong> 1234567890</p>
            <p><strong>Nội dung:</strong> <span class="text-primary fw-bold">THANH TOAN <?= sprintf("BOOK-%d-%04d", date('Y'), $so_thu_tu ?? 1) ?></span></p>
          </div>

          <!-- Giả lập VNPay -->
         <div id="vnpay_info" class="mt-4 p-4 border rounded bg-light text-center d-none">
            <i class="fas fa-credit-card fa-3x text-danger mb-3"></i>
            <h6 class="fw-bold text-danger">Thanh toán qua VNPAY</h6>
            <p>Bạn sẽ được chuyển đến cổng thanh toán an toàn của VNPAY.</p>
            <p class="fw-bold mb-1">Số tiền: <span class="text-danger"><?= number_format($tongTien) ?>₫</span></p>
            <small class="text-muted d-block mt-3">
                Sau khi thanh toán thành công, bạn sẽ tự động quay về trang xác nhận đặt phòng.
            </small>
        </div>
        
        </form>
      </div>
      <div class="modal-footer justify-content-center">
        <button type="submit" form="paymentForm" name="dat_phong" class="btn btn-success btn-lg px-5" id="btnDatPhong">
          <i class="fas fa-check"></i> HOÀN TẤT ĐẶT PHÒNG
        </button>
        <button type="button" class="btn btn-secondary btn-lg" data-bs-dismiss="modal">Hủy</button>
      </div>
    </div>
  </div>
</div>

<script>
document.querySelectorAll('input[name="phuong_thuc"]').forEach(radio => {
  radio.addEventListener('change', function() {
    // Ẩn tất cả
    document.getElementById('thongtin_chuyenkhoan').classList.add('d-none');
    document.getElementById('vnpay_info').classList.add('d-none');

    // Hiển thị phần phù hợp
    if (this.value === 'chuyen_khoan') {
      document.getElementById('thongtin_chuyenkhoan').classList.remove('d-none');
    } else if (this.value === 'vnpay') {
      document.getElementById('vnpay_info').classList.remove('d-none');
    }
  });
});

</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>