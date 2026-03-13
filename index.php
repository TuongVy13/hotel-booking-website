<?php

if (isset($_SESSION['thongbao'])): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <i class="fas fa-check-circle"></i> <?= $_SESSION['thongbao'] ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php unset($_SESSION['thongbao']);  ?>
<?php endif; ?>


<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Carousel + Check & List Rooms</title>

 
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

  <style>
    body { font-family: "Montserrat", sans-serif; background:#f5f7fb; padding-bottom:40px; }
    .h-font { font-family: "Merienda", cursive; }

    .carousel-item img { height: 360px; object-fit: cover; }

    .check-container { max-width:1100px; margin:24px auto; background:#fff; padding:18px; border-radius:10px; box-shadow:0 6px 20px rgba(0,0,0,0.06); }

    .results { max-width:1100px; margin:18px auto 60px auto; }
    .room-card img { height:160px; object-fit:cover; border-radius:6px; }
    .badge-avail { font-weight:600; }

    .muted { color:#6c757d; }
  </style>
</head>
<body>


<?php include 'includes/header.php'; ?>


<div id="carouselExampleInterval" class="carousel slide" data-bs-ride="carousel">
  <div class="carousel-inner">
    <div class="carousel-item active" data-bs-interval="8000">
      <img src="../images/carousel/1.jpg" class="d-block w-100" alt="...">
    </div>
    <div class="carousel-item" data-bs-interval="6000">
      <img src="../images/carousel/2.jpg" class="d-block w-100" alt="...">
    </div>
    <div class="carousel-item">
      <img src="../images/carousel/3.jpg" class="d-block w-100" alt="...">
    </div>
  </div>

  <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleInterval" data-bs-slide="prev">
    <span class="carousel-control-prev-icon"></span>
  </button>

  <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleInterval" data-bs-slide="next">
    <span class="carousel-control-next-icon"></span>
  </button>
</div>

<div class="check-container">
  <h5 class="mb-3">Kiểm tra phòng trống</h5>
  <div class="row gy-2">
    <div class="col-md-3">
      <label class="form-label">Ngày đến</label>
      <input id="cin" type="date" class="form-control" required>
    </div>
    <div class="col-md-3">
      <label class="form-label">Ngày đi</label>
      <input id="cout" type="date" class="form-control" required>
    </div>
    <div class="col-md-2">
      <label class="form-label">Số phòng</label>
      <input id="rooms" type="number" min="1" value="1" class="form-control">
    </div>
    <div class="col-md-2">
      <label class="form-label">Loại phòng</label>
      <select id="rtype" class="form-select">
        <option value="any">Tất cả</option>
        <option value="Standard">Standard</option>
        <option value="Deluxe">Deluxe</option>
        <option value="Suite">Suite</option>
      </select>
    </div>
    <div class="col-md-2 d-flex align-items-end">
      <button id="checkBtn" class="btn btn-primary w-100">Kiểm tra</button>
    </div>
    <div class="col-12 mt-2">
      <div id="msg" class="text-danger fw-bold"></div>
    </div>
  </div>
</div>
<div id="results" class="container mt-4"></div>

<script>
const roomImages = {
  'Phòng Tiêu Chuẩn': '/images/rooms/standard.jpg',
  'Phòng Deluxe':     '/images/rooms/deluxe.jpg',
  'Phòng Suite':      '/images/rooms/suite.jpg'
};
// Xử lý khi nhấn nút "Kiểm tra"
document.getElementById('checkBtn').addEventListener('click', async function () {
  const cin   = document.getElementById('cin').value;
  const cout  = document.getElementById('cout').value;
  const rooms = document.getElementById('rooms').value || 1;
  const type  = document.getElementById('rtype').value;

  const msgDiv = document.getElementById('msg');
  const resultsDiv = document.getElementById('results');
  msgDiv.textContent = '';
  resultsDiv.innerHTML = '<div class="text-center"><div class="spinner-border"></div></div>';

  try {
    const res = await fetch(`/check-availability.php?cin=${cin}&cout=${cout}&rooms=${rooms}&type=${type}`);
    const data = await res.json();

    if (!data.success) {
      msgDiv.textContent = data.message;
      resultsDiv.innerHTML = '';
      return;
    }

    if (data.data.length === 0) {
      resultsDiv.innerHTML = '<div class="alert alert-warning">Không có phòng nào trống cho yêu cầu của bạn.</div>';
      return;
    }

    let html = `<h4 class="mb-4">Có ${data.data.length} loại phòng trống • ${data.nights} đêm</h4><div class="row g-4">`;

    data.data.forEach(room => {
      const total = room.gia_dem * data.nights * rooms;
      const canBook = room.so_luong_con >= rooms;

      // Tạo form ẩn để thêm vào giỏ hàng
      const formId = 'addcart_' + room.ten_loai.replace(/\s+/g, '_') + '_' + Date.now();

      html += `
        <div class="col-md-6 col-lg-4 mb-4">
          <div class="card h-100 shadow-sm border-0 position-relative">
            <img src="${roomImages[room.ten_loai] || '/images/rooms/default.jpg'}" 
                class="card-img-top" 
                style="height:200px; object-fit:cover;" 
                alt="${room.ten_loai}">
            
            ${!canBook ? '<div class="position-absolute top-0 start-0 w-100 h-100 bg-dark bg-opacity-50 d-flex align-items-center justify-content-center rounded-top"><span class="text-white fs-3 fw-bold">HẾT PHÒNG</span></div>' : ''}

            <div class="card-body d-flex flex-column ${!canBook ? 'opacity-75' : ''}">
              <h5 class="card-title">${room.ten_loai}</h5>
              <p class="text-muted small flex-grow-1">${room.mo_ta || 'Phòng đầy đủ tiện nghi, view đẹp'}</p>

              <div class="mt-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                  <strong class="fs-4 text-danger">${room.gia_dem.toLocaleString('vi-VN')} ₫<small class="text-muted fs-6">/đêm</small></strong>
                  <span class="badge bg-${canBook ? 'success' : 'danger'} fs-6">
                    ${room.so_luong_con} phòng trống
                  </span>
                </div>

                <div class="bg-light rounded p-3 mb-3 border">
                  <div class="d-flex justify-content-between small">
                    <span>${data.nights} đêm × ${rooms} phòng</span>
                    <strong class="text-primary">${total.toLocaleString('vi-VN')} ₫</strong>
                  </div>
                </div>
                ${canBook ? `
                  <form method="post" action="add-to-cart.php" id="${formId}">
                    <input type="hidden" name="id_phong" value="${room.id_phong}">
                    <input type="hidden" name="ten_loai" value="${room.ten_loai}">
                    <input type="hidden" name="gia_dem" value="${room.gia_dem}">
                    <input type="hidden" name="so_phong" value="${rooms}">
                    <input type="hidden" name="ngay_nhan" value="${cin}">
                    <input type="hidden" name="ngay_tra" value="${cout}">
                    <input type="hidden" name="so_dem" value="${data.nights}">
                    <input type="hidden" name="tong_tien" value="${total}">

                    <button type="submit" name="add_to_cart"
                            class="btn btn-lg btn-primary w-100 fw-bold shadow-sm d-flex align-items-center justify-content-center gap-2">
                      <i class="fas fa-cart-plus"></i>
                      Thêm vào giỏ hàng
                    </button>
                  </form>
                ` : `
                  <button class="btn btn-secondary w-100" disabled>
                    Hết phòng
                  </button>
                `}
              </div>
            </div>
          </div>
        </div>`;
    });
    html += '</div>';
    resultsDiv.innerHTML = html;

  } catch (err) {
    msgDiv.textContent = 'Lỗi kết nối. Vui lòng thử lại.';
    resultsDiv.innerHTML = '';
  }
});
</script>