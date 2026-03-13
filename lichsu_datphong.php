<?php

require_once 'includes/header.php';
require_once 'includes/db.php'; 


if (!isset($_SESSION['user_id'])) {
    $_SESSION['thongbao'] = "Vui lòng đăng nhập để xem lịch sử đặt phòng!";
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];


try {
    $sql = "SELECT dp.*, 
                   (SELECT COUNT(*) FROM chitietdatphong ct WHERE ct.id_datphong = dp.ma_DP) AS so_phong_dat
            FROM datphong dp 
            WHERE dp.id_kh = :id_kh 
            ORDER BY dp.ngay_dat DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id_kh' => $user_id]);
    $don_dat = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $don_dat = [];
    $error = "Lỗi tải dữ liệu: " . $e->getMessage();
}
?>

<div class="container py-5">
    <h2 class="mb-4 text-primary fw-bold">
        <i class="fas fa-history me-2"></i> Lịch sử đặt phòng của bạn
    </h2>

    <?php if (isset($_SESSION['thongbao'])): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <?= $_SESSION['thongbao'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['thongbao']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['thongbao_thanhcong'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?= $_SESSION['thongbao_thanhcong'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['thongbao_thanhcong']); ?>
    <?php endif; ?>

    <?php if (empty($don_dat)): ?>
        <div class="alert alert-info text-center py-5">
            <i class="fas fa-info-circle fa-3x mb-3 text-info"></i>
            <h4>Bạn chưa có đơn đặt phòng nào</h4>
            <p>Hãy quay lại trang chủ để tìm và đặt phòng ngay hôm nay!</p>
            <a href="index.php" class="btn btn-primary">Tìm phòng ngay</a>
        </div>
    <?php else: ?>
        <div class="row g-4">
            <?php foreach ($don_dat as $don): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card shadow-sm h-100 border-0">
                        <div class="card-header <?= $don['trang_thai'] == 'da_thanh_toan' || $don['trang_thai'] == 'hoan_thanh' ? 'bg-success' : 'bg-warning' ?> text-white">
                            <h5 class="mb-0">
                                Mã đơn: <strong><?= htmlspecialchars($don['ma_DP']) ?></strong>
                            </h5>
                        </div>
                        <div class="card-body">
                            <p class="mb-2">
                                <i class="far fa-calendar-alt text-muted"></i>
                                <strong>Ngày đặt:</strong> <?= date('d/m/Y H:i', strtotime($don['ngay_dat'])) ?>
                            </p>
                            <p class="mb-2">
                                <i class="fas fa-bed text-muted"></i>
                                <strong>Số phòng:</strong> <?= $don['so_phong_dat'] ?> phòng
                            </p>
                            <p class="mb-2">
                                <i class="fas fa-money-bill-wave text-muted"></i>
                                <strong>Tổng tiền:</strong> 
                                <span class="text-danger fw-bold"><?= number_format($don['tong_tien']) ?> ₫</span>
                            </p>
                            <p class="mb-3">
                                <i class="fas fa-info-circle text-muted"></i>
                                <strong>Trạng thái:</strong>
                                <span class="badge bg-<?= 
                                    $don['trang_thai'] == 'cho_xac_nhan' ? 'warning' :
                                    ($don['trang_thai'] == 'da_xac_nhan' ? 'info' :
                                    ($don['trang_thai'] == 'da_thanh_toan' ? 'success' :
                                    ($don['trang_thai'] == 'hoan_thanh' ? 'primary' : 'danger')))
                                ?> fs-6">
                                    <?= ucfirst(str_replace('_', ' ', $don['trang_thai'])) ?>
                                </span>
                            </p>
                            <?php if ($don['ly_do_huy']): ?>
                                <p class="text-danger small">
                                    <strong>Lý do hủy:</strong> <?= htmlspecialchars($don['ly_do_huy']) ?>
                                </p>
                            <?php endif; ?>
                        </div>
                   
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

