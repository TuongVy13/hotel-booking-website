<?php

    session_start(); 
    require_once 'db.php';
    require_once(__DIR__ . "/../init_cart.php");

  
    if (isset($_SESSION['user_id'])) {
    $isLoggedIn = true;
    $customerName = $_SESSION['ho_ten'] ?? 'Khách hàng';
    } else {
        $isLoggedIn = false;
    }
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Khách sạn Sunrise Retreat</title>
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
   <style>
    body { font-family: "Montserrat", sans-serif; background:#f5f7fb; padding-bottom:40px; }
    .h-font { font-family: "Merienda", cursive; }
  </style>
</head>

<body>
<nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container-fluid">
    <a class="navbar-brand me-5 fs-3 h-font" href="#">Sunrise Retreat</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav"> <span class="navbar-toggler-icon"></span> </button>
    <div class="collapse navbar-collapse" id="nav">
      <ul class="navbar-nav me-auto">
        <li class="nav-item"><a class="nav-link active" href="../index.php">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Rooms</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Facilities</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Contact</a></li>
        <li class="nav-item"><a class="nav-link" href="../LabTH.php">Lab thực hành</a></li>
      </ul>
      <div class="d-flex align-items-center gap-3">
        <?php if ($isLoggedIn): ?>
          <div class="dropdown">
            <a class="btn btn-secondary dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              Xin chao , <?= htmlspecialchars(explode(' ', $customerName)[0]) ?>!
            </a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="../user/profile.php">Thông tin cá nhân</a></li>
              <li><a class="dropdown-item" href="../lichsu_datphong.php">Đơn đặt phòng của tôi</a></li>
              <li><a class="dropdown-item" href="../logout.php">Đăng xuất</a></li>
            </ul>
          </div>
        <?php else: ?>
          <a href="../login.php" ><button class="btn btn-outline-primary"  name="login">Đăng nhập</button></a>
          <a href="../register.php" ><button class="btn btn-primary" name="register">Đăng ký</button></a>
        <?php endif; ?>
        <?php if (!empty($_SESSION['cart'])): ?>
        <a href="../booking.php" class="position-relative  position-relative">
            <i class="fas fa-shopping-cart"></i> Giỏ hàng
            <span class="badge bg-danger position-absolute top-0 start-100 translate-middle">
                <?= count($_SESSION['cart'] ?? []) ?>
            </span>
         </a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</nav>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js" integrity="sha384-G/EV+4j2dNv+tEPo3++6LCgdCROaejBqfUeNjuKAiuXbjrxilcCdDz6ZAVfHWe1Y" crossorigin="anonymous"></script>
</body>