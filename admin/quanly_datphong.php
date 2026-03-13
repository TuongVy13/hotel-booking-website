<?php
// File: admin/quanly_datphong.php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Xử lý duyệt đặt phòng
if (isset($_POST['duyet_datphong'])) {
    $ma_DP = $_POST['ma_DP'];
    try {
        $pdo->beginTransaction();
        
        // Cập nhật trạng thái đặt phòng
        $sql = "UPDATE datphong SET trang_thai = 'da_xac_nhan', id_admin = :id_admin 
                WHERE ma_DP = :ma_DP AND trang_thai = 'cho_xac_nhan'";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':ma_DP' => $ma_DP,
            ':id_admin' => $_SESSION['admin_id'] ?? 1
        ]);

        if ($stmt->rowCount() > 0) {
            // Cập nhật trạng thái các phòng liên quan thành 'Đã đặt'
            $sql_phong = "UPDATE phong p 
                          JOIN chitietdatphong ct ON p.id_phong = ct.id_phong 
                          SET p.trang_thai = 'Đã đặt' 
                          WHERE ct.id_datphong = :ma_DP";
            $stmt_phong = $pdo->prepare($sql_phong);
            $stmt_phong->execute([':ma_DP' => $ma_DP]);

            $pdo->commit();
            $_SESSION['success'] = "Đặt phòng $ma_DP đã được xác nhận thành công!";
        } else {
            $pdo->rollBack();
            $_SESSION['error'] = "Không thể xác nhận (có thể đã được xử lý trước đó).";
        }
    } catch (Exception $e) {
        $pdo->rollBack();
        $_SESSION['error'] = "Lỗi: " . $e->getMessage();
    }
    header('Location: quanly_datphong.php');
    exit;
}


if (isset($_POST['huy_datphong'])) {
    $ma_DP = $_POST['ma_DP'];
    $ly_do_huy = trim($_POST['ly_do_huy']);

    if (empty($ly_do_huy)) {
        $_SESSION['error'] = "Vui lòng nhập lý do hủy!";
    } else {
        try {
            $pdo->beginTransaction();

            $sql = "UPDATE datphong SET trang_thai = 'da_huy', ly_do_huy = :ly_do_huy, id_admin = :id_admin 
                    WHERE ma_DP = :ma_DP AND trang_thai IN ('cho_xac_nhan', 'da_xac_nhan')";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':ma_DP' => $ma_DP,
                ':ly_do_huy' => $ly_do_huy,
                ':id_admin' => $_SESSION['admin_id'] ?? 1
            ]);

            if ($stmt->rowCount() > 0) {
              
                $sql_phong = "UPDATE phong p 
                              JOIN chitietdatphong ct ON p.id_phong = ct.id_phong 
                              SET p.trang_thai = 'Trống' 
                              WHERE ct.id_datphong = :ma_DP AND p.trang_thai = 'Đã đặt'";
                $stmt_phong = $pdo->prepare($sql_phong);
                $stmt_phong->execute([':ma_DP' => $ma_DP]);

                $pdo->commit();
                $_SESSION['success'] = "Đặt phòng $ma_DP đã được hủy thành công!";
            } else {
                $pdo->rollBack();
                $_SESSION['error'] = "Không thể hủy (có thể đã được xử lý trước đó).";
            }
        } catch (Exception $e) {
            $pdo->rollBack();
            $_SESSION['error'] = "Lỗi: " . $e->getMessage();
        }
    }
    header('Location: quanly_datphong.php');
    exit;
}

// Lấy danh sách đặt phòng với thông tin chi tiết
$sql = "
    SELECT 
        dp.ma_DP, 
        dp.ngay_dat, 
        dp.tong_tien, 
        dp.trang_thai, 
        dp.ly_do_huy,
        kh.ho_ten AS ten_khach,
        kh.email,
        kh.sdt,
        COUNT(ct.id_chitiet) AS so_phong_dat
    FROM datphong dp
    JOIN khachhang kh ON dp.id_kh = kh.id_kh
    LEFT JOIN chitietdatphong ct ON ct.id_datphong = dp.ma_DP
    GROUP BY dp.ma_DP
    ORDER BY dp.ngay_dat DESC
";

$stmt = $pdo->query($sql);
$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Quản Lý Đặt Phòng - Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
  <style>
    body { background: #f8f9fa; font-family: 'Nunito', sans-serif; }
    .sidebar { position: fixed; top: 0; left: 0; height: 100vh; width: 260px; background: #2c3e50; padding-top: 20px; z-index: 1000; }
    .sidebar .nav-link { color: #bdc3c7; padding: 14px 25px; border-radius: 0; transition: all 0.3s; }
    .sidebar .nav-link:hover, .sidebar .nav-link.active { background: #34495e; color: #fff; }
    .sidebar .nav-link i { width: 30px; font-size: 1.3rem; }
    .main-content { margin-left: 260px; padding: 25px; }
    .card { border-radius: 15px; overflow: hidden; box-shadow: 0 10px 20px rgba(0,0,0,0.1); }
    .badge-status { font-size: 0.9rem; padding: 0.5em 1em; }
  </style>
</head>
<body>


  <div class="sidebar text-white">
    <div class="text-center mb-4">
      <h4 class="fw-bold">QLKS HOTEL</h4>
      <small>Admin Panel</small>
    </div>
    <nav class="nav flex-column px-3">
      <a class="nav-link" href="dashboard.php"><i class='bx bx-home'></i> Dashboard</a>
      <a class="nav-link" href="quanly_phong.php"><i class='bx bx-bed'></i> Quản lý phòng</a>
      <a class="nav-link active" href="quanly_datphong.php"><i class='bx bx-calendar-check'></i> Quản lý đặt phòng</a>
      <a class="nav-link" href="quanly_tiennghi.php"><i class='bx bx-wifi'></i> Tiện nghi</a>
      <a class="nav-link" href="danhgia.php"><i class='bx bx-star'></i> Đánh giá</a>
      <hr class="bg-light opacity-25">
      <a class="nav-link" href="../logout.php"><i class='bx bx-log-out'></i> Đăng xuất</a>
    </nav>
  </div>

  <div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2 class="fw-bold text-dark">Quản Lý Đặt Phòng</h2>
    </div>

    <!-- Thông báo -->
    <?php if (isset($_SESSION['success'])): ?>
      <div class="alert alert-success alert-dismissible fade show">
        <?= $_SESSION['success'] ?> <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
      <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
      <div class="alert alert-danger alert-dismissible fade show">
        <?= $_SESSION['error'] ?> <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
      <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <!-- Bảng danh sách đặt phòng -->
    <div class="card shadow">
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-hover align-middle">
            <thead class="table-light">
              <tr>
                <th>Mã Đặt </th>
                <th>Khách Hàng</th>
                <th>Liên Hệ</th>
                <th>Ngày Đặt</th>
                <th>Số Phòng</th>
                <th>Tổng Tiền</th>
                <th>Trạng Thái</th>
                <th>Lý Do Hủy</th>
                <th width="150">Hành Động</th>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($bookings)): ?>
              <tr>
                <td colspan="9" class="text-center text-muted py-4">Chưa có đặt phòng nào.</td>
              </tr>
              <?php else: ?>
                <?php foreach ($bookings as $b): ?>
                <tr>
                  <td><strong><?= htmlspecialchars($b['ma_DP']) ?></strong></td>
                  <td><?= htmlspecialchars($b['ten_khach']) ?></td>
                  <td>
                    <?= htmlspecialchars($b['email']) ?><br>
                    <small class="text-muted"><?= htmlspecialchars($b['sdt'] ?? 'Chưa cung cấp') ?></small>
                  </td>
                  <td><?= date('d/m/Y H:i', strtotime($b['ngay_dat'])) ?></td>
                  <td><span class="badge bg-info"><?= $b['so_phong_dat'] ?></span></td>
                  <td><strong><?= number_format($b['tong_tien']) ?> ₫</strong></td>
                  <td>
                    <span class="badge badge-status bg-<?= 
                      $b['trang_thai'] == 'cho_xac_nhan' ? 'warning' :
                      ($b['trang_thai'] == 'da_xac_nhan' ? 'info' :
                      ($b['trang_thai'] == 'da_thanh_toan' ? 'success' :
                      ($b['trang_thai'] == 'da_huy' ? 'danger' : 'secondary')))
                    ?>">
                      <?= ucwords(str_replace('_', ' ', $b['trang_thai'])) ?>
                    </span>
                  </td>
                  <td class="text-wrap" style="max-width: 200px;">
                    <?= $b['ly_do_huy'] ? htmlspecialchars($b['ly_do_huy']) : '-' ?>
                  </td>
                  <td>
                    <?php if (in_array($b['trang_thai'], ['cho_xac_nhan', 'da_xac_nhan'])): ?>
                      <?php if ($b['trang_thai'] === 'cho_xac_nhan'): ?>
                        <form method="post" class="d-inline">
                          <input type="hidden" name="ma_DP" value="<?= $b['ma_DP'] ?>">
                          <button type="submit" name="duyet_datphong" class="btn btn-sm btn-success" 
                                  onclick="return confirm('Xác nhận đặt phòng <?= $b['ma_DP'] ?>?')">
                            <i class='bx bx-check'></i> Duyệt
                          </button>
                        </form>
                      <?php endif; ?>

                      <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#huyModal_<?= $b['ma_DP'] ?>">
                        <i class='bx bx-x'></i> Hủy
                      </button>
                    <?php else: ?>
                      <span class="text-muted small">Đã xử lý</span>
                    <?php endif; ?>
                  </td>
                </tr>

                
                <div class="modal fade" id="huyModal_<?= $b['ma_DP'] ?>" tabindex="-1">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title">Hủy đặt phòng <?= $b['ma_DP'] ?></h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                      </div>
                      <form method="post">
                        <div class="modal-body">
                          <input type="hidden" name="ma_DP" value="<?= $b['ma_DP'] ?>">
                          <p>Bạn có chắc chắn muốn <strong>hủy</strong> đặt phòng này?</p>
                          <div class="mb-3">
                            <label class="form-label fw-bold text-danger">Lý do hủy (bắt buộc)</label>
                            <textarea name="ly_do_huy" class="form-control" rows="4" required 
                                      placeholder="Ví dụ: Khách hủy, phòng không khả dụng..."></textarea>
                          </div>
                        </div>
                        <div class="modal-footer">
                          <button type="submit" name="huy_datphong" class="btn btn-danger">
                            <i class='bx bx-trash'></i> Xác nhận hủy
                          </button>
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
                <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>