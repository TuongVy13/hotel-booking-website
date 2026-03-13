<?php
function postIndex($index, $value = "")
{
    return isset($_POST[$index]) ? trim($_POST[$index]) : $value;
}

$username   = postIndex("username");
$password1  = postIndex("password1");
$password2  = postIndex("password2");
$name       = postIndex("name");
$thong_tin  = postIndex("thong_tin");  
$sm         = postIndex("submit");

$err        = "";
$success    = false;
$clean_info = "";
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Lab 4.1</title>
<style>
    body {font-family: Arial, sans-serif; background: #f4f4f4;}
    fieldset {width: 60%; margin: 50px auto; padding: 20px; background: white; border: 2px solid #0066cc; border-radius: 10px;}
    legend {font-size: 1.6em; color: #0066cc; text-align: center;}
    table {width: 100%; border-collapse: collapse;}
    td {padding: 10px; vertical-align: top;}
    input[type=text], input[type=password], textarea {
        width: 100%; padding: 10px; font-size: 1em; border: 1px solid #ccc; border-radius: 5px; box-sizing: border-box;
    }
    textarea {height: 120px; resize: vertical;}
    input[type=submit] {
        padding: 12px 40px; font-size: 1.2em; background: #0066cc; color: white; border: none; border-radius: 5px; cursor: pointer;
    }
    .result {
        width: 60%; margin: 20px auto; padding: 20px; border-radius: 8px; font-size: 1.1em; line-height: 1.8;
    }
    .error {background: #ffe6e6; border: 2px solid red; color: red;}
    .success {background: #e6ffe6; border: 2px solid green; color: green;}
</style>
</head>

<body>
<fieldset>
    <legend>Form Đăng Ký</legend>
    <form action="" method="post">
        <table>
            <tr><td>Tên đăng nhập:</td><td><input type="text" name="username" value="<?php echo htmlspecialchars($username); ?>" /></td></tr>
            <tr><td>Mật khẩu:</td><td><input type="password" name="password1" /></td></tr>
            <tr><td>Nhập lại mật khẩu:</td><td><input type="password" name="password2" /></td></tr>
            <tr><td>Họ tên:</td><td><input type="text" name="name" value="<?php echo htmlspecialchars($name); ?>" /></td></tr>
            <tr>
                <td>Thông tin cá nhân:</td>
                <td><textarea name="thong_tin"><?php echo htmlspecialchars($thong_tin); ?></textarea></td>
            </tr>
            <tr><td colspan="2" align="center">
                <input type="submit" name="submit" value="Đăng Ký" />
            </td></tr>
        </table>
    </form>
</fieldset>

<?php
if ($sm != "") {
    if (strlen(trim($username)) < 6)
        $err .= "Tên đăng nhập phải có ít nhất 6 ký tự (không tính khoảng trắng đầu/cuối)!<br>";

    if ($password1 !== $password2)
        $err .= "Mật khẩu và nhập lại mật khẩu không khớp!<br>";

    if (strlen($password1) < 8)
        $err .= "Mật khẩu phải có ít nhất 8 ký tự!<br>";

    $name_trim = trim($name);
    $words = preg_split('/\s+/', $name_trim);
    $words = array_filter($words);
    if (count($words) < 2)
        $err .= "Họ tên phải có ít nhất 2 từ!<br>";
    if ($err == "") {
        $success = true;

        $safe = strip_tags($thong_tin);

        $escaped = addslashes($safe);

        $clean_info = stripslashes($escaped);
        $clean_info = htmlspecialchars($clean_info, ENT_QUOTES, 'UTF-8');
        $clean_info = nl2br($clean_info);
    }
    ?>

    <div class="result <?php echo $success ? 'success' : 'error'; ?>">
        <?php if (!$success): ?>
            <h3>Có lỗi xảy ra:</h3>
            <?php echo $err; ?>
        <?php else: ?>
            <h3>Đăng ký thành công!</h3>
            <strong>Username:</strong> <?php echo htmlspecialchars(trim($username)); ?><br><br>

            <strong>Mật khẩu mã hóa:</strong><br>
            - MD5: <?php echo md5($password1); ?><br>
            - SHA1: <?php echo sha1($password1); ?><br>
            - SHA1(MD5): <?php echo sha1(md5($password1)); ?><br>
            - SHA256: <?php echo hash('sha256', $password1); ?><br>
            - SHA3-256: <?php echo hash('sha3-256', $password1); ?><br><br>

            <strong>Họ tên:</strong> <?php echo ucwords(strtolower($name_trim)); ?><br><br>

            <strong>Thông tin cá nhân (đã xử lý):</strong><br>
            <div style="background:#fff; padding:15px; border:1px solid #ccc; border-radius:5px; margin-top:10px;">
                <?php echo $clean_info ?: "<em>(Trống)</em>"; ?>
            </div>
        <?php endif; ?>
    </div>
<?php } ?>
</body>
</html>