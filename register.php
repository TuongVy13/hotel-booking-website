<?php
session_start();
require_once './includes/db.php';   // File PDO siêu ngắn của bạn

$error = '';


if (isset($_POST['register'])) {

    $name     = trim($_POST['name'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($name) || empty($email) || empty($password)) {
        $error = "Vui lòng điền đầy đủ thông tin!";
    } 
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Email không hợp lệ!";
    }
    elseif (strlen($password) < 6) {
        $error = "Mật khẩu phải từ 6 ký tự trở lên!";
    }
    else {
    
        $stmt = $pdo->prepare("SELECT email FROM admin WHERE email = ? UNION SELECT email FROM khachhang WHERE email = ? LIMIT 1");
        $stmt->execute([$email, $email]);

        if ($stmt->fetch()) {
            $error = "Email này đã được đăng ký!";
        } 
        else {

            $hash = password_hash($password, PASSWORD_BCRYPT);

                $stmt = $pdo->prepare("INSERT INTO khachhang (ho_ten, email, mat_khau) VALUES (?, ?, ?)");
            if ($stmt->execute([$name, $email, $hash])) {
                $success = "Đăng ký thành công! Đang chuyển về trang đăng nhập...";
                header("Refresh: 2; url=login.php");
                exit();
            } else {
                $error = "Lỗi CSDL: " . implode(" | ", $stmt->errorInfo());
            }
        }
    }
}

function showError($msg) {
    return $msg ? '<div class="error-msg">' . htmlspecialchars($msg) . '</div>' : '';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <div class="container">
        <div class="form-box">
        <form action="" method="POST">
            <h2>Register</h2>
            <?= showError($error); ?>
            <input type="text" name="name" placeholder="Họ và tên"  required>
            <input type="email" name="email" placeholder="Email"  required>
            <input type="password" name="password" placeholder="Mật khẩu" required>
            <button type="submit" name="register">Đăng ký</button>
            <p>Đã có tài khoản? <a href="login.php">Đăng nhập</a></p>
        </form>
    </div>
        
    </div>
</body>
</html>