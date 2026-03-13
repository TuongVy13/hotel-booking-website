<?php
session_start();
require_once './includes/db.php';   

$error = '';


if (isset($_POST['login'])) {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = "Vui lòng nhập đầy đủ email và mật khẩu!";
    } 
    //filter_var: loc va kiem tra tinh hop le
    //filter_VALIDATE_EMAIL: kiem tra dinh dang email
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Email không hợp lệ!";
    }
    else {
            
        $stmt = $pdo->prepare("SELECT id_admin AS id, ho_ten, mat_khau, 'admin' AS role FROM admin WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            $stmt = $pdo->prepare("SELECT id_kh AS id, ho_ten, mat_khau, 'user' AS role FROM khachhang WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
        }

        if ($user && password_verify($password, $user['mat_khau'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['ho_ten']   = $user['ho_ten'];
            $_SESSION['role']     = $user['role'];  

            if ($user['role'] === 'admin') {
                header("Location: admin/dashboard.php");
            } else {
                header("Location: index.php");
            }
            exit();
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
    <title>Login</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <div class="container">
        <div class="form-box " id="login-form">
            <form  method="post">
                <h2>Login</h2>
                <?= showError($error); ?>
                <input type="email" name="email" placeholder="Email" required >
                <input type="password" name="password" placeholder="Password" required >
                <button type="submit" name="login">Login</button>
                <p>Don't have account?<a href="register.php">Register</a></p>
                <p>AD: admin@hotel.com MK: password</p>
            </form>
        </div>
    </div>

    
</body>
</html>