<?php

require_once 'includes/header.php'; 
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">
            <div class="text-center py-5">
                <i class="fas fa-check-circle text-success" style="font-size: 100px;"></i>
                
                <h1 class="mt-4 text-success fw-bold">ĐẶT PHÒNG THÀNH CÔNG!</h1>
                
                <div class="mt-4 p-4 bg-light rounded shadow-sm">
                    <?php if (isset($_SESSION['thongbao_thanhcong'])): ?>
                        <p class="fs-5 lead">
                            <?= $_SESSION['thongbao_thanhcong'] ?>
                        </p>
                        <?php unset($_SESSION['thongbao_thanhcong']); // Xóa thông báo sau khi hiển thị ?>
                    <?php else: ?>
                        <p class="fs-5 lead">
                            Cảm ơn bạn đã đặt phòng tại khách sạn chúng tôi.<br>
                            Đơn đặt phòng đã được ghi nhận và đang chờ xác nhận.
                        </p>
                    <?php endif; ?>

                    <div class="mt-4 p-3 bg-white rounded border">
                        <p class="mb-2 text-muted">Bạn sẽ nhận được email xác nhận sớm nhất có thể.</p>
                        <p class="mb-0 text-muted">Nếu cần hỗ trợ, vui lòng liên hệ: <strong>1900 1234</strong></p>
                    </div>
                </div>

                <div class="mt-5">
                    <a href="lichsu_datphong.php" class="btn btn-primary btn-lg me-3 px-5">
                        <i class="fas fa-list-alt"></i> Xem lịch sử đặt phòng
                    </a>
                    <a href="index.php" class="btn btn-outline-secondary btn-lg px-5">
                        <i class="fas fa-home"></i> Về trang chủ
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
