<?php
function postIndex($index, $default = "")
{
    return isset($_POST[$index]) ? trim($_POST[$index]) : $default;
}

$username  = postIndex("username");
$password  = postIndex("password");
$email     = postIndex("email");
$phone     = postIndex("phone");
$date      = postIndex("date");
$sm        = postIndex("submit");

function getExt($filename)
{
    if (empty($filename)) return "";
    $parts = explode(".", $filename);
    if (count($parts) < 2) return "";
    return strtolower(end($parts));
}

function getPasswordRandom($n = 10)
{
    $chars = "abcdefghjkmnpqrstuvwxyzABCDEFGHJKMNPQRSTUVWXYZ23456789!@#$%";
    return substr(str_shuffle($chars), 0, $n);
}

function checkUserName($u)
{
    return preg_match('/^[a-zA-Z0-9._-]{6,20}$/', $u);
}

function checkPassword($p)
{
    if (strlen($p) < 8) return "Mật khẩu phải ít nhất 8 ký tự!<br>";
    if (!preg_match('/[A-Z]/', $p)) return "Mật khẩu phải có ít nhất 1 chữ hoa!<br>";
    if (!preg_match('/[0-9]/', $p)) return "Mật khẩu phải có ít nhất 1 số!<br>";
    if (!preg_match('/[^a-zA-Z0-9]/', $p)) return "Mật khẩu phải có ít nhất 1 ký tự đặc biệt!<br>";
    return true;
}

function checkEmail($e)
{
    return filter_var($e, FILTER_VALIDATE_EMAIL);
}

function checksdt($p)
{
    return preg_match('/^[0-9]{10,11}$/', $p);
}

function checkNgaySinh($d)
{
    return preg_match('/^(\d{2}[-\/]\d{2}[-\/]\d{4})$/', $d);
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Lab 4.2</title>
    <style>
        body {
            font-family: Arial;
            background: #f0f8ff;
        }

        fieldset {
            width: 60%;
            margin: 30px auto;
            padding: 20px;
            background: white;
            border: 2px solid #0066cc;
            border-radius: 10px;
        }

        legend {
            font-size: 1.6em;
            color: #0066cc;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            padding: 10px;
            vertical-align: top;
        }

        input[type=text],
        input[type=password],
        input[type=email],
        input[type=file] {
            width: 100%;
            padding: 10px;
            font-size: 1em;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        input[type=submit] {
            padding: 12px 40px;
            font-size: 1.2em;
            background: #0066cc;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .info {
            width: 60%;
            margin: 20px auto;
            padding: 20px;
            background: #e6f7ff;
            border: 2px solid #0066cc;
            border-radius: 8px;
            font-size: 1.1em;
        }

        .success {
            background: #d4edda;
            border-color: green;
            color: green;
        }

        .error {
            background: #f8d7da;
            border-color: #f5c6cb;
            color: #721c24;
        }
    </style>
</head>

<body>

    <fieldset>
        <legend>Form Đăng Ký</legend>
        <form action="" method="post" enctype="multipart/form-data">
            <table>
                <tr>
                    <td>Username:</td>
                    <td><input type="text" name="username" value="<?php echo htmlspecialchars($username); ?>" required /></td>
                </tr>
                <tr>
                    <td>Mật khẩu:</td>
                    <td>
                        <input type="password" name="password" id="password" value="<?php echo htmlspecialchars($password); ?>" required />
                        <label><input type="checkbox" onclick="togglePass()"> Hiện mật khẩu</label>
                    </td>
                </tr>
                <tr>
                    <td>Email:</td>
                    <td><input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required /></td>
                </tr>
                <tr>
                    <td>Số điện thoại:</td>
                    <td><input type="text" name="phone" value="<?php echo htmlspecialchars($phone); ?>" required /></td>
                </tr>
                <tr>
                    <td>Ngày sinh (dd/mm/yyyy):</td>
                    <td><input type="text" name="date" value="<?php echo htmlspecialchars($date); ?>" placeholder="01/01/2000" required /></td>
                </tr>
                <tr>
                    <td>Avatar (file):</td>
                    <td><input type="file" name="file" accept="image/*" /></td>
                </tr>
                <tr>
                    <td colspan="2" align="center"><input type="submit" name="submit" value="Đăng Ký" /></td>
                </tr>
            </table>
        </form>
    </fieldset>

    <script>
        function togglePass() {
            var x = document.getElementById("password");
            if (x.type === "password") {
                x.type = "text";
            } else {
                x.type = "password";
            }
        }
    </script>

    <?php
    if ($sm != "") {
        $error = "";

        if (!checkUserName($username))
            $error .= "Username chỉ được dùng a-z, A-Z, 0-9, ., _, - và dài 6-20 ký tự!<br>";

        $passCheck = checkPassword($password);
        if ($passCheck !== true)
            $error .= $passCheck;

        if (!checkEmail($email))
            $error .= "Email không đúng định dạng!<br>";

        if (!checksdt($phone))
            $error .= "Số điện thoại phải chứa 10-11 chữ số!<br>";

        if (!checkNgaySinh($date))
            $error .= "Ngày sinh phải định dạng dd/mm/yyyy hoặc dd-mm-yyyy!<br>";

        $ext = "";
        if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
            $ext = getExt($_FILES['file']['name']);
        }

        $randomPass = getPasswordRandom(12);

        echo '<div class="info ' . ($error == "" ? "success" : "error") . '">';
        if ($error != "") {
            echo "<h3>Lỗi:</h3>" . $error;
        } else {
            echo "<h3>Đăng ký thành công!</h3>";
            echo "Username: <strong>$username</strong><br>";
            echo "Email: <strong>$email</strong><br>";
            echo "Phần mở rộng file avatar: <strong>" . ($ext ?: "Không có file") . "</strong><br><br>";
            echo "<strong>Mật khẩu gợi ý mạnh (12 ký tự):</strong><br>";
            echo "<div style='font-size:1.4em; color:#d35400; background:#fff; padding:10px; border:1px solid #ccc; display:inline-block;'>$randomPass</div>";
        }
        echo '</div>';
    }
    ?>
</body>

</html>