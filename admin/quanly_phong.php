<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");  
    exit();
}

// XỬ LÝ LƯU (thêm hoặc sửa)
if (isset($_POST['them_phong'])) {
    $stmt = $pdo->prepare("INSERT INTO phong (ten_phong, loai_phong, gia_mac_dinh, mo_ta, trang_thai, id_admin, ngay_tao) 
                           VALUES (?, ?, ?, ?, ?, ?, NOW())");
    $stmt->execute([
        $_POST['ten_phong'],
        $_POST['loai_phong'],
        $_POST['gia_mac_dinh'],
        nl2br($_POST['mo_ta']), // chuyển \n thành <br>
        $_POST['trang_thai'],
        $_SESSION['admin_id']
    ]);
    header('Location: index.php?msg=Thêm thành công');
}

if (isset($_POST['sua_phong'])) {
    $stmt = $pdo->prepare("UPDATE phong SET 
        ten_phong = ?, loai_phong = ?, gia_mac_dinh = ?, mo_ta = ?, trang_thai = ?
        WHERE id_phong = ?");
    $stmt->execute([
        $_POST['ten_phong'],
        $_POST['loai_phong'],
        $_POST['gia_mac_dinh'],
        nl2br($_POST['mo_ta']),
        $_POST['trang_thai'],
        $_POST['id_phong']
    ]);
    header('Location: index.php?msg=Cập nhật thành công');
}

// Xóa phòng (chỉ khi không có đặt phòng nào đang hoạt động)
if (isset($_GET['xoa'])) {
    $id = $_GET['xoa'];
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM chitietdatphong cd 
                           JOIN datphong dp ON cd.id_datphong = dp.ma_DP 
                           WHERE cd.id_phong = ? AND dp.trang_thai NOT IN ('da_huy', 'hoan_thanh')");
    $stmt->execute([$id]);
    if ($stmt->fetchColumn() > 0) {
        $message = "<div class='alert alert-danger'>Không thể xóa! Phòng đang có đặt phòng chưa hoàn tất.</div>";
    } else {
        $pdo->prepare("DELETE FROM phong WHERE id_phong = ?")->execute([$id]);
        $message = "<div class='alert alert-success'>Xóa phòng thành công!</div>";
    }
}

// Lấy danh sách phòng + tiện nghi
$stmt = $pdo->query("SELECT p.*, a.ho_ten AS nguoitao 
                     FROM phong p 
                     LEFT JOIN admin a ON p.id_admin = a.id_admin 
                     ORDER BY p.id_phong DESC");
$phongs = $stmt->fetchAll();

// Lấy danh sách tiện nghi để thêm/sửa
$tiennghis = $pdo->query("SELECT * FROM tiennghi ORDER BY tentn")->fetchAll();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Quản lý Phòng - QLKS Hotel</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
  <style>
    body { background: #f8f9fa; font-family: 'Nunito', sans-serif; }
    .sidebar { position: fixed; top: 0; left: 0; height: 100vh; width: 260px; background: #2c3e50; padding-top: 20px; z-index: 1000; }
    .sidebar .nav-link { color: #bdc3c7; padding: 14px 25px; transition: all 0.3s; }
    .sidebar .nav-link:hover, .sidebar .nav-link.active { background: #34495e; color: #fff; }
    .main-content { margin-left: 260px; padding: 30px; }
    .table th { background: #f8f9fa; }
    .badge-trong { background: #28a745; }
    .badge-dat { background: #ffc107; color: #212529; }
    .badge-baotri { background: #dc3545; }
  </style>
</head>
<body>


  <div class="sidebar text-white">
    <div class="text-center mb-4">
      <h4 class="fw-bold">QLKS HOTEL</h4>
      <small>Admin Panel</small>
    </div>
    <nav class="nav flex-column px-3">
      <a class="nav-link" href="dashboard.php">Dashboard</a>
      <a class="nav-link active" href="quanly_phong.php">Quản lý phòng</a>
      <a class="nav-link" href="quanly_datphong.php">Quản lý đặt phòng</a>
      <a class="nav-link" href="quanly_tiennghi.php">Tiện nghi</a>
      <a class="nav-link" href="danhgia.php">Đánh giá</a>
      <hr class="bg-light opacity-25">
      <a class="nav-link" href="../logout.php">Đăng xuất</a>
    </nav>
  </div>

  <!-- Main Content -->
  <div class="main-content">
    <h2 class="mb-4 fw-bold">Quản lý Phòng</h2>

    <!-- Nút thêm phòng -->
       
    <div class="mb-4">
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalPhong">Thêm phòng mới</button>
    </div>

  
    <div class="card shadow">
    <div class="card-body">
        <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
            <tr>
                <th>ID</th>
                <th>Tên phòng</th>
                <th>Loại</th>
                <th>Giá</th>
                <th>Trạng thái</th>
                <th>Tiện nghi</th>
                <th>Ngày tạo</th>
                <th>Hành động</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($phongs as $p): ?>
            <tr>
                <td><?= $p['id_phong'] ?></td>
                <td><?= htmlspecialchars($p['ten_phong']) ?></td>
                <td><span class="badge bg-info"><?= $p['loai_phong'] ?></span></td>
                <td><?= number_format($p['gia_mac_dinh']) ?> ₫</td>
                <td><span class="badge bg-success"><?= $p['trang_thai'] ?></span></td>
                <td>
                <?php
                $tn = $pdo->prepare("SELECT tentn FROM phong_tiennghi pt JOIN tiennghi t ON pt.matn=t.matn WHERE pt.maphong=?");
                $tn->execute([$p['id_phong']]);
                foreach ($tn->fetchAll() as $t) echo "<small class='badge bg-secondary me-1'>{$t['tentn']}</small>";
                ?>
                </td>
                <td><?= date('d/m/Y H:i', strtotime($p['ngay_tao'])) ?></td>
                <td>
                <button class="btn btn-sm btn-warning btn-edit" 
                        data-id="<?= $p['id_phong'] ?>"
                        data-ten="<?= htmlspecialchars($p['ten_phong']) ?>"
                        data-loai="<?= $p['loai_phong'] ?>"
                        data-gia="<?= $p['gia_mac_dinh'] ?>"
                        data-trangthai="<?= $p['trang_thai'] ?>"
                        data-mota="<?= htmlspecialchars($p['mo_ta'] ?? '') ?>">
                    Sửa
                </button>
                <a href="?xoa=<?= $p['id_phong'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Xóa thật nhé?')">Xóa</a>
                </td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        </div>
    </div>
    </div>


<div class="modal fade" id="modalPhong" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form method="POST" action="them_phong.php" class="modal-content"> 
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="bx bx-plus-circle"></i> Thêm phòng mới
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Tên phòng <span class="text-danger">*</span></label>
                        <input type="text" name="ten_phong" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Loại phòng <span class="text-danger">*</span></label>
                        <select name="loai_phong" class="form-select" required>
                            <option value="Standard">Standard</option>
                            <option value="Deluxe">Deluxe</option>
                            <option value="Suite">Suite</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Giá mặc định (VNĐ) <span class="text-danger">*</span></label>
                        <input type="number" name="gia_mac_dinh" class="form-control" min="0" step="1000" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Trạng thái</label>
                        <select name="trang_thai" class="form-select">
                            <option value="Trống" selected>Trống</option>
                            <option value="Đã đặt">Đã đặt</option>
                            <option value="Bảo trì">Bảo trì</option>
                        </select>
                    </div>
                    <div class="col-12 mt-4">
                        <label class="form-label fw-bold">Tiện nghi phòng</label>
                        <div class="row">
                            <?php
                          
                            $stmt = $pdo->query("SELECT matn, tentn FROM tiennghi ORDER BY tentn");
                            $tiennghis = $stmt->fetchAll(PDO::FETCH_ASSOC);

                            if (empty($tiennghis)) {
                                echo '<div class="col-12"><p class="text-muted fst-italic">Chưa có tiện nghi nào được thêm.</p></div>';
                            } else {
                                foreach ($tiennghis as $tn):
                            ?>
                                <div class="col-md-4 col-sm-6">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" 
                                               name="tiennghi[]" value="<?= $tn['matn'] ?>" 
                                               id="tn_<?= $tn['matn'] ?>">
                                        <label class="form-check-label" for="tn_<?= $tn['matn'] ?>">
                                            <?= htmlspecialchars($tn['tentn']) ?>
                                        </label>
                                    </div>
                                </div>
                            <?php
                                endforeach;
                            }
                            ?>
                        </div>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Mô tả</label>
                        <textarea name="mo_ta" class="form-control" rows="4" placeholder="Mô tả chi tiết về phòng..."></textarea>
                    </div>
                </div>
            </div>
           
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="submit" name="them_phong" class="btn btn-primary">
                    <i class="bx bx-check"></i> Thêm phòng
                </button>
            </div>
        </form>
    </div>
</div>
<div class="modal fade" id="modalSuaPhong" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form method="POST" action="sua_phong.php" class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title">
                    <i class="bi bi-pencil-square"></i> Sửa thông tin phòng
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="id_phong" id="edit_id_phong">

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Tên phòng <span class="text-danger">*</span></label>
                        <input type="text" name="ten_phong" id="edit_ten_phong" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Loại phòng <span class="text-danger">*</span></label>
                        <select name="loai_phong" id="edit_loai_phong" class="form-select" required>
                            <option value="Standard">Standard</option>
                            <option value="Deluxe">Deluxe</option>
                            <option value="Suite">Suite</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Giá mặc định (VNĐ) <span class="text-danger">*</span></label>
                        <input type="number" name="gia_mac_dinh" id="edit_gia_mac_dinh" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Trạng thái</label>
                        <select name="trang_thai" id="edit_trang_thai" class="form-select">
                            <option value="Trống">Trống</option>
                            <option value="Đã đặt">Đã đặt</option>
                            <option value="Bảo trì">Bảo trì</option>
                        </select>
                    </div>
                     <div class="col-12 mt-4">
                        <label class="form-label fw-bold">Tiện nghi phòng</label>
                        <div class="row">
                            <?php
                          
                            $stmt = $pdo->query("SELECT matn, tentn FROM tiennghi ORDER BY tentn");
                            $tiennghis = $stmt->fetchAll(PDO::FETCH_ASSOC);

                            if (empty($tiennghis)) {
                                echo '<div class="col-12"><p class="text-muted fst-italic">Chưa có tiện nghi nào được thêm.</p></div>';
                            } else {
                                foreach ($tiennghis as $tn):
                            ?>
                                <div class="col-md-4 col-sm-6">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" 
                                               name="tiennghi[]" value="<?= $tn['matn'] ?>" 
                                               id="tn_<?= $tn['matn'] ?>">
                                        <label class="form-check-label" for="tn_<?= $tn['matn'] ?>">
                                            <?= htmlspecialchars($tn['tentn']) ?>
                                        </label>
                                    </div>
                                </div>
                            <?php
                                endforeach;
                            }
                            ?>
                        </div>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Mô tả</label>
                        <textarea name="mo_ta" id="edit_mo_ta" class="form-control" rows="4"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="submit" name="sua_phong" class="btn btn-warning">
                    <i class="bi bi-save"></i> Cập nhật
                </button>
            </div>
        </form>
    </div>
</div>

<!-- JavaScript để đổ dữ liệu vào modal Sửa -->
<script>
document.querySelectorAll('.btn-edit').forEach(btn => {
    btn.addEventListener('click', function () {
        // Đổ dữ liệu từ data-* attributes
        document.getElementById('edit_id_phong').value = this.dataset.id;
        document.getElementById('edit_ten_phong').value = this.dataset.ten;
        document.getElementById('edit_loai_phong').value = this.dataset.loai;
        document.getElementById('edit_gia_mac_dinh').value = this.dataset.gia;
        document.getElementById('edit_trang_thai').value = this.dataset.trangthai;

        // Xử lý mô tả: nếu lưu dưới dạng <br> thì chuyển lại thành \n
        const mota = this.dataset.mota || '';
        document.getElementById('edit_mo_ta').value = mota.replace(/<br\s*\/?>/gi, "\n");

        // Mở modal
        new bootstrap.Modal(document.getElementById('modalSuaPhong')).show();
    });
});
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>