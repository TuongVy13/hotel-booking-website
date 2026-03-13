<?php
session_start();
require_once '../includes/db.php';

// if (empty($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
//     header('Location: ../login.php');
//     exit;
// }

$user_id = $_SESSION['user_id'];
$success = $error = '';


$stmt = $pdo->prepare("SELECT ho_ten, email, sdt, dia_chi FROM khachhang WHERE id_kh = ?");
$stmt->execute([$user_id]);
$kh = $stmt->fetch(PDO::FETCH_ASSOC);


if (isset($_POST['capnhat'])) {
    $ho_ten  = trim($_POST['ho_ten'] ?? '');
    $sdt     = trim($_POST['sdt'] ?? '');
    $dia_chi = trim($_POST['dia_chi'] ?? '');

    if (empty($ho_ten)) {
        $error = "Vui lòng nhập họ tên.";
    } elseif ($sdt !== '' && !preg_match('/^0[3|5|7|8|9][0-9]{8}$/', $sdt)) {
        $error = "Số điện thoại không hợp lệ.";
    } else {
        $stmt = $pdo->prepare("UPDATE khachhang SET ho_ten = ?, sdt = ?, dia_chi = ? WHERE id_kh = ?");
        if ($stmt->execute([$ho_ten, $sdt ?: null, $dia_chi ?: null, $user_id])) {
            $success = "Cập nhật thông tin thành công!";
            $_SESSION['ho_ten'] = $ho_ten;
            $kh['ho_ten'] = $ho_ten;
            $kh['sdt'] = $sdt;
            $kh['dia_chi'] = $dia_chi;
        } else {
            $error = "Có lỗi xảy ra. Vui lòng thử lại.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Thông tin cá nhân - Sunrise Retreat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-xl-7">

                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white text-center py-4">
                        <h4 class="mb-0 fw-semibold">
                            <i class="fas fa-user-edit me-2"></i>
                            Thông tin cá nhân
                        </h4>
                    </div>

                    <div class="card-body p-4 p-lg-5">

                        
                        <?php if ($success): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle me-2"></i><?= htmlspecialchars($success) ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                       
                        <?php if ($error): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-triangle me-2"></i><?= htmlspecialchars($error) ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <!-- Form cập nhật -->
                        <form method="post" class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label fw-medium">Họ và tên <span class="text-danger">*</span></label>
                                <input type="text" name="ho_ten" class="form-control form-control-lg" 
                                       value="<?= htmlspecialchars($kh['ho_ten']) ?>" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-medium">Email</label>
                                <input type="email" class="form-control form-control-lg bg-light" 
                                       value="<?= htmlspecialchars($kh['email']) ?>" disabled>
                                <div class="form-text">Email không thể thay đổi</div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-medium">Số điện thoại</label>
                                <input type="text" name="sdt" class="form-control form-control-lg" 
                                       value="<?= htmlspecialchars($kh['sdt'] ?? '') ?>"
                                       placeholder="Ví dụ: 0909111222">
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-medium">Địa chỉ</label>
                                <textarea name="dia_chi" rows="3" class="form-control" 
                                          placeholder="Nhập địa chỉ nhận hóa đơn (tùy chọn)"><?= htmlspecialchars($kh['dia_chi'] ?? '') ?></textarea>
                            </div>

                            <div class="col-12 text-end">
                                <a href="../index.php" class="btn btn-outline-secondary btn-lg me-3">
                                    <i class="fas fa-arrow-left"></i> Quay lại
                                </a>
                                <button type="submit" name="capnhat" class="btn btn-primary btn-lg px-5">
                                    <i class="fas fa-save"></i> Lưu thay đổi
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

               
                <div class="text-center mt-4">
                    <a href="my_bookings.php" class="btn btn-link text-decoration-none">
                        <i class="fas fa-calendar-check me-2"></i>
                        Xem đơn đặt phòng của tôi
                    </a>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>