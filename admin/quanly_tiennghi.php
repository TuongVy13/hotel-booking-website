<?php

session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Xử lý thêm tiện nghi
if (isset($_POST['them_tiennghi'])) {
    $tentn = trim($_POST['tentn']);
    if (!empty($tentn)) {
        try {
            $sql = "INSERT INTO tiennghi (tentn) VALUES (:tentn)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':tentn' => $tentn]);
            $_SESSION['success'] = "Thêm tiện nghi '$tentn' thành công!";
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) { // Duplicate entry
                $_SESSION['error'] = "Tiện nghi '$tentn' đã tồn tại!";
            } else {
                $_SESSION['error'] = "Lỗi: " . $e->getMessage();
            }
        }
    } else {
        $_SESSION['error'] = "Vui lòng nhập tên tiện nghi!";
    }
    header('Location: quanly_tiennghi.php');
    exit;
}

// Xử lý sửa tiện nghi
if (isset($_POST['sua_tiennghi'])) {
    $matn = $_POST['matn'];
    $tentn_moi = trim($_POST['tentn_moi']);
    if (!empty($tentn_moi)) {
        try {
            $sql = "UPDATE tiennghi SET tentn = :tentn WHERE matn = :matn";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':tentn' => $tentn_moi, ':matn' => $matn]);
            $_SESSION['success'] = "Cập nhật tiện nghi thành công!";
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $_SESSION['error'] = "Tên tiện nghi mới đã tồn tại!";
            } else {
                $_SESSION['error'] = "Lỗi: " . $e->getMessage();
            }
        }
    } else {
        $_SESSION['error'] = "Tên tiện nghi không được để trống!";
    }
    header('Location: quanly_tiennghi.php');
    exit;
}

// Xử lý xóa tiện nghi (chỉ xóa nếu chưa được gán cho phòng nào)
if (isset($_GET['xoa'])) {
    $matn = $_GET['xoa'];
    try {
        // Kiểm tra xem tiện nghi có đang được gán không
        $stmt_check = $pdo->prepare("SELECT COUNT(*) FROM phong_tiennghi WHERE matn = :matn");
        $stmt_check->execute([':matn' => $matn]);
        if ($stmt_check->fetchColumn() > 0) {
            $_SESSION['error'] = "Không thể xóa tiện nghi này vì đang được gán cho một số phòng!";
        } else {
            $stmt = $pdo->prepare("DELETE FROM tiennghi WHERE matn = :matn");
            $stmt->execute([':matn' => $matn]);
            $_SESSION['success'] = "Xóa tiện nghi thành công!";
        }
    } catch (Exception $e) {
        $_SESSION['error'] = "Lỗi khi xóa: " . $e->getMessage();
    }
    header('Location: quanly_tiennghi.php');
    exit;
}

// Lấy danh sách tiện nghi
$stmt = $pdo->query("SELECT matn, tentn FROM tiennghi ORDER BY tentn");
$tiennghi_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Quản Lý Tiện Nghi - Admin</title>
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
  </style>
</head>
<body>

  <!-- Sidebar giống dashboard -->
  <div class="sidebar text-white">
    <div class="text-center mb-4">
      <h4 class="fw-bold">QLKS HOTEL</h4>
      <small>Admin Panel</small>
    </div>
    <nav class="nav flex-column px-3">
      <a class="nav-link" href="dashboard.php"><i class='bx bx-home'></i> Dashboard</a>
      <a class="nav-link" href="quanly_phong.php"><i class='bx bx-bed'></i> Quản lý phòng</a>
      <a class="nav-link" href="quanly_datphong.php"><i class='bx bx-calendar-check'></i> Quản lý đặt phòng</a>
      <a class="nav-link active" href="quanly_tiennghi.php"><i class='bx bx-wifi'></i> Tiện nghi</a>
      <a class="nav-link" href="danhgia.php"><i class='bx bx-star'></i> Đánh giá</a>
      <hr class="bg-light opacity-25">
      <a class="nav-link" href="../logout.php"><i class='bx bx-log-out'></i> Đăng xuất</a>
    </nav>
  </div>

  <div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2 class="fw-bold text-dark">Quản Lý Tiện Nghi</h2>
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

    <div class="row">
      <!-- Form thêm tiện nghi -->
      <div class="col-lg-4">
        <div class="card mb-4">
          <div class="card-body">
            <h5 class="card-title"><i class='bx bx-plus-circle'></i> Thêm tiện nghi mới</h5>
            <form method="post">
              <div class="mb-3">
                <label class="form-label">Tên tiện nghi</label>
                <input type="text" name="tentn" class="form-control" placeholder="Ví dụ: Hồ bơi, Gym, Spa..." required>
              </div>
              <button type="submit" name="them_tiennghi" class="btn btn-primary">
                <i class='bx bx-check'></i> Thêm ngay
              </button>
            </form>
          </div>
        </div>
      </div>

      <!-- Danh sách tiện nghi -->
      <div class="col-lg-8">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title mb-4"><i class='bx bx-wifi'></i> Danh sách tiện nghi (<?= count($tiennghi_list) ?>)</h5>
            <div class="table-responsive">
              <table class="table table-hover align-middle">
                <thead class="table-light">
                  <tr>
                    <th>Mã TN</th>
                    <th>Tên tiện nghi</th>
                    <th width="200">Hành động</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if (empty($tiennghi_list)): ?>
                  <tr>
                    <td colspan="3" class="text-center text-muted py-4">Chưa có tiện nghi nào.</td>
                  </tr>
                  <?php else: ?>
                    <?php foreach ($tiennghi_list as $tn): ?>
                    <tr>
                      <td><strong>#<?= $tn['matn'] ?></strong></td>
                      <td><?= htmlspecialchars($tn['tentn']) ?></td>
                      <td>
                        <!-- Nút Sửa -->
                        <button class="btn btn-sm btn-warning me-2" data-bs-toggle="modal" data-bs-target="#suaModal_<?= $tn['matn'] ?>">
                          <i class='bx bx-edit'></i> Sửa
                        </button>
                        <!-- Nút Xóa -->
                        <a href="quanly_tiennghi.php?xoa=<?= $tn['matn'] ?>" 
                           class="btn btn-sm btn-danger"
                           onclick="return confirm('Xóa tiện nghi &quot;<?= htmlspecialchars($tn['tentn']) ?>&quot;? Hành động này không thể hoàn tác.');">
                          <i class='bx bx-trash'></i> Xóa
                        </a>
                      </td>
                    </tr>

                    <!-- Modal Sửa -->
                    <div class="modal fade" id="suaModal_<?= $tn['matn'] ?>">
                      <div class="modal-dialog">
                        <div class="modal-content">
                          <div class="modal-header bg-warning text-dark">
                            <h5 class="modal-title">Sửa tiện nghi</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                          </div>
                          <form method="post">
                            <div class="modal-body">
                              <input type="hidden" name="matn" value="<?= $tn['matn'] ?>">
                              <div class="mb-3">
                                <label class="form-label">Tên tiện nghi mới</label>
                                <input type="text" name="tentn_moi" class="form-control" 
                                       value="<?= htmlspecialchars($tn['tentn']) ?>" required>
                              </div>
                            </div>
                            <div class="modal-footer">
                              <button type="submit" name="sua_tiennghi" class="btn btn-warning">
                                <i class='bx bx-save'></i> Lưu thay đổi
                              </button>
                              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
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
    </div>
  </div>

  
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>