<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");  
    exit();
}


$admin_name = $_SESSION['admin_name'] ?? 'Quản Trị Viên';
// 1. Tổng doanh thu (đã thanh toán thành công)
$stmt = $pdo->query("SELECT COALESCE(SUM(sotien), 0) AS tong FROM thanhtoan WHERE trang_thai = 'Thành công'");
$tong_doanh_thu = $stmt->fetchColumn();

// 2. Đặt phòng chờ xác nhận
$cho_xac_nhan = $pdo->query("SELECT COUNT(*) FROM datphong WHERE trang_thai = 'cho_xac_nhan'")->fetchColumn();

// 3. Phòng trống / tổng phòng
$phong_trong = $pdo->query("SELECT COUNT(*) FROM phong WHERE trang_thai = 'Trống'")->fetchColumn();
$tong_phong  = $pdo->query("SELECT COUNT(*) FROM phong")->fetchColumn();


$danhgia_moi = $pdo->query("SELECT COUNT(*) FROM danhgia WHERE ngay_danhgia >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)")->fetchColumn();


// 7. 5 đặt phòng gần đây
$stmt = $pdo->query("
    SELECT dp.ma_DP, kh.ho_ten, dp.ngay_dat, dp.tong_tien, dp.trang_thai
    FROM datphong dp
    JOIN khachhang kh ON dp.id_kh = kh.id_kh
    ORDER BY dp.ngay_dat DESC LIMIT 5
");
$recent_bookings = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset=UTF-8>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin Dashboard - QLKS Hotel</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
  <link href="https://cdn.jsdelivr.net/npm/apexcharts@3/dist/apexcharts.css" rel="stylesheet">
  <style>
    body { background: #f8f9fa; font-family: 'Nunito', sans-serif; }
    .sidebar { position: fixed; top: 0; left: 0; height: 100vh; width: 260px; background: #2c3e50; padding-top: 20px; z-index: 1000; }
    .sidebar .nav-link { color: #bdc3c7; padding: 14px 25px; border-radius: 0; transition: all 0.3s; }
    .sidebar .nav-link:hover, .sidebar .nav-link.active { background: #34495e; color: #fff; }
    .sidebar .nav-link i { width: 30px; font-size: 1.3rem; }
    .main-content { margin-left: 260px; padding: 25px; }
    .stat-card { border-radius: 15px; color: white; padding: 25px; box-shadow: 0 10px 20px rgba(0,0,0,0.1); }
    .card { border-radius: 15px; overflow: hidden; }
  </style>
</head>
<body>

 
  <div class="sidebar text-white">
    <div class="text-center mb-4">
      <h4 class="fw-bold">QLKS HOTEL</h4>
      <small>Admin Panel</small>
    </div>
    <nav class="nav flex-column px-3">
      <a class="nav-link active" href="dashboard.php"><i class='bx bx-home'></i> Dashboard</a>
      <a class="nav-link" href="quanly_phong.php"><i class='bx bx-bed'></i> Quản lý phòng</a>
      <a class="nav-link" href="quanly_datphong.php"><i class='bx bx-calendar-check'></i> Quản lý đặt phòng</a>
      <a class="nav-link" href="quanly_tiennghi.php"><i class='bx bx-wifi'></i> Tiện nghi</a>
      <a class="nav-link" href="danhgia.php"><i class='bx bx-star'></i> Đánh giá</a>
      <hr class="bg-light opacity-25">
      <a class="nav-link" href="../logout.php"><i class='bx bx-log-out'></i> Đăng xuất</a>
    </nav>
  </div>

 
  <div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2 class="fw-bold text-dark">Dashboard Quản Trị</h2>
    </div>


    <div class="row g-4 mb-5">
      <div class="col-md-3">
        <div class="stat-card" style="background: linear-gradient(135deg, #667eea, #764ba2);">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <h5 class="mb-1">Tổng doanh thu</h5>
              <h3 class="mb-0"><?= number_format($tong_doanh_thu) ?> ₫</h3>
            </div>
            <i class='bx bx-money fs-1'></i>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="stat-card" style="background: linear-gradient(135deg, #f093fb, #f5576c);">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <h5 class="mb-1">Chờ xác nhận</h5>
              <h3 class="mb-0"><?= $cho_xac_nhan ?></h3>
            </div>
            <i class='bx bx-time fs-1'></i>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="stat-card" style="background: linear-gradient(135deg, #4facfe, #00f2fe);">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <h5 class="mb-1">Phòng trống</h5>
              <h3 class="mb-0"><?= $phong_trong ?> / <?= $tong_phong ?></h3>
            </div>
            <i class='bx bx-door-open fs-1'></i>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="stat-card" style="background: linear-gradient(135deg, #43e97b, #38f9d7);">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <h5 class="mb-1">Đánh giá mới</h5>
              <h3 class="mb-0"><?= $danhgia_moi ?></h3>
              <small>7 ngày qua</small>
            </div>
            <i class='bx bx-star fs-1'></i>
          </div>
        </div>
      </div>
    </div>

    <div class="main container">

  
      <div class="col-lg-20">
        <div class="card shadow">
          <div class="card-body">
            <h5 class="card-title">Đặt phòng gần đây</h5>
            <div class="table-responsive">
              <table class="table table-hover">
                <thead>
                  <tr>
                    <th>Mã</th>
                    <th>Khách</th>
                    <th>Tổng tiền</th>
                    <th>Trạng thái</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($recent_bookings as $b): ?>
                  <tr>
                    <td><strong><?= $b['ma_DP'] ?></strong></td>
                    <td><?= htmlspecialchars($b['ho_ten']) ?></td>
                    <td><?= number_format($b['tong_tien']) ?>₫</td>
                    <td>
                      <span class="badge bg-<?= $b['trang_thai']=='da_thanh_toan'?'success':($b['trang_thai']=='cho_xac_nhan'?'warning':'secondary') ?>">
                        <?= str_replace('_', ' ', $b['trang_thai']) ?>
                      </span>
                    </td>
                  </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
 
</body>
</html>