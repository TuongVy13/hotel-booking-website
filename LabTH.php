<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lab Thực Hành - Trang chủ</title>

   
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        }
        .header-title {
            font-weight: 700;
            color: #2c3e50;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
            margin: 50px 0;
        }
        .card-lab {
            transition: all 0.3s ease;
            border: none;
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            height: 100%;
        }
        .card-lab:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }
        .card-lab .btn {
            font-size: 1.1rem;
            font-weight: 500;
            padding: 1rem 1.5rem;
            border-radius: 12px;
        }
        .week-number {
            font-size: 1.8rem;
            font-weight: bold;
            color: #5a67d8;
        }
        .icon-large {
            font-size: 2rem;
        }
    </style>
</head>
<body>

<div class="container py-5">
    <h1 class="header-title text-center mb-5">LAB THỰC HÀNH</h1>

    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 justify-content-center">

        <!-- Tuần 1 -->
        <div class="col">
            <a href="./LabTH/lab1.php" class="text-decoration-none">
                <div class="card card-lab bg-white h-100 text-dark">
                    <div class="card-body d-flex align-items-center gap-4">
                        <div class="icon-large text-primary">
                            <i class="bi bi-1-circle-fill"></i>
                        </div>
                        <div>
                            <h4 class="mb-0">Bài tập Tuần 1</h4>
                            <small class="text-muted">Nhập môn PHP</small>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Tuần 2 -->
        <div class="col">
            <a href="./LabTH/lab02/lab2.php" class="text-decoration-none">
                <div class="card card-lab bg-white h-100 text-dark">
                    <div class="card-body d-flex align-items-center gap-4">
                        <div class="icon-large text-success">
                            <i class="bi bi-2-circle-fill"></i>
                        </div>
                        <div>
                            <h4 class="mb-0">Bài tập Tuần 2</h4>
                            <small class="text-muted">Biến, hằng, kiểu dữ liệu</small>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Tuần 3 -->
        <div class="col">
            <a href="./LabTH/lab03/lab3.php" class="text-decoration-none">
                <div class="card card-lab bg-white h-100 text-dark">
                    <div class="card-body d-flex align-items-center gap-4">
                        <div class="icon-large text-warning">
                            <i class="bi bi-3-circle-fill"></i>
                        </div>
                        <div>
                            <h4 class="mb-0">Bài tập Tuần 3</h4>
                            <small class="text-muted">Câu lệnh điều kiện</small>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Tuần 4 -->
        <div class="col">
            <a href="./LabTH/lab04/lab4.php" class="text-decoration-none">
                <div class="card card-lab bg-white h-100 text-dark">
                    <div class="card-body d-flex align-items-center gap-4">
                        <div class="icon-large text-danger">
                            <i class="bi bi-4-circle-fill"></i>
                        </div>
                        <div>
                            <h4 class="mb-0">Bài tập Tuần 4</h4>
                            <small class="text-muted">Vòng lặp</small>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Tuần 5 -->
        <div class="col">
            <a href="./LabTH/lab05/lab05.php" class="text-decoration-none">
                <div class="card card-lab bg-white h-100 text-dark">
                    <div class="card-body d-flex align-items-center gap-4">
                        <div class="icon-large text-info">
                            <i class="bi bi-5-circle-fill"></i>
                        </div>
                        <div>
                            <h4 class="mb-0">Bài tập Tuần 5</h4>
                            <small class="text-muted">Hàm trong PHP</small>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Tuần 6 -->
        <div class="col">
            <a href="./LabTH/lab06/lab06.php" class="text-decoration-none">
                <div class="card card-lab bg-white h-100 text-dark">
                    <div class="card-body d-flex align-items-center gap-4">
                        <div class="icon-large text-primary">
                            <i class="bi bi-6-circle-fill"></i>
                        </div>
                        <div>
                            <h4 class="mb-0">Bài tập Tuần 6</h4>
                            <small class="text-muted">Mảng</small>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Tuần 7 -->
        <div class="col">
            <a href="./LabTH/lab7.php" class="text-decoration-none">
                <div class="card card-lab bg-white h-100 text-dark">
                    <div class="card-body d-flex align-items-center gap-4">
                        <div class="icon-large text-secondary">
                            <i class="bi bi-7-circle-fill"></i>
                        </div>
                        <div>
                            <h4 class="mb-0">Bài tập Tuần 7</h4>
                            <small class="text-muted">Xử lý Form</small>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Tuần 8 -->
        <div class="col">
            <a href="./LabTH/lab08/lab8.php" class="text-decoration-none">
                <div class="card card-lab bg-white h-100 text-dark">
                    <div class="card-body d-flex align-items-center gap-4">
                        <div class="icon-large text-dark">
                            <i class="bi bi-8-circle-fill"></i>
                        </div>
                        <div>
                            <h4 class="mb-0">Bài tập Tuần 8</h4>
                            <small class="text-muted">Session & Cookie</small>
                        </div>
                    </div>
                </div>
            </a>
        </div>

    </div>
</div>

<!-- Bootstrap 5 JS (tùy chọn, chỉ cần nếu dùng component JS) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>