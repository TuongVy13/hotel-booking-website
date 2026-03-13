<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng ký thành viên (JS Validation)</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .form-container { width: 500px; border: 1px solid #ccc; padding: 20px; background: #f0f0f0; }
        .row { margin-bottom: 15px; }
        .label { display: inline-block; width: 140px; font-weight: bold; vertical-align: top; }
        .error { color: red; }
        .success { color: green; border: 1px solid green; padding: 10px; margin-bottom: 20px; background: #eaffea; }
        .required { color: red; }
        /* Thêm style cho khung lỗi của JS */
        #js-error-box { color: red; border: 1px solid red; padding: 10px; margin-bottom: 10px; background: #ffeaea; display: none; }
    </style>
</head>
<body>

<?php
    $errors = array();
    $show_result = false;
    
    if (isset($_POST['btn_register'])) {

        if (empty($_POST['txt_user'])) {
            $errors[] = "Tên đăng nhập không được để trống.";
        }

        if (empty($_POST['txt_pass'])) {
            $errors[] = "Mật khẩu không được để trống.";
        }

        if (empty($_POST['txt_repass'])) {
            $errors[] = "Vui lòng nhập lại mật khẩu.";
        } elseif ($_POST['txt_pass'] != $_POST['txt_repass']) {
            $errors[] = "Mật khẩu nhập lại không khớp.";
        }

        if (!isset($_POST['rd_sex'])) {
            $errors[] = "Vui lòng chọn giới tính.";
        }

        if ($_POST['sel_province'] == "") {
            $errors[] = "Vui lòng chọn Tỉnh.";
        }

        $file_name = "";
        if (isset($_FILES['file_avatar']) && $_FILES['file_avatar']['error'] == 0) {
            $allowed = array('jpg', 'png', 'bmp', 'gif');
            $filename = $_FILES['file_avatar']['name'];
            $ext = pathinfo($filename, PATHINFO_EXTENSION);
            
            if (!in_array(strtolower($ext), $allowed)) {
                $errors[] = "File ảnh không hợp lệ. Chỉ chấp nhận: jpg, png, bmp, gif.";
            } else {
                $file_name = $filename;
            }
        }

        if (empty($errors)) {
            $show_result = true;
        }
    }
?>

    <h2>Đăng ký thành viên (Có kiểm tra Javascript)</h2>

    <?php if ($show_result): ?>
        <div class="success">
            <h3>Đăng ký thành công!</h3>
            <p><strong>Tên đăng nhập:</strong> <?php echo $_POST['txt_user']; ?></p>
            <p><strong>Giới tính:</strong> <?php echo $_POST['rd_sex']; ?></p>
            <p><strong>Sở thích:</strong> 
                <?php 
                if (isset($_POST['chk_hobby'])) {
                    echo implode(", ", $_POST['chk_hobby']); 
                } else {
                    echo "Không có";
                }
                ?>
            </p>
            <p><strong>Tỉnh:</strong> <?php echo $_POST['sel_province']; ?></p>
            <p><strong>Ảnh đại diện:</strong> 
                <?php echo ($file_name != "") ? $file_name : "Chưa upload"; ?>
            </p>
        </div>
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
        <div class="error">
            <ul>
                <?php foreach ($errors as $err): ?>
                    <li><?php echo $err; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <div id="js-error-box"></div>

    <form name="registerForm" action="" method="post" enctype="multipart/form-data" class="form-container" onsubmit="return validateForm()">
        
        <div class="row">
            <label class="label">Tên đăng nhập <span class="required">*</span></label>
            <input type="text" name="txt_user" id="txt_user" value="<?php echo isset($_POST['txt_user']) ? $_POST['txt_user'] : ''; ?>">
        </div>

        <div class="row">
            <label class="label">Mật khẩu <span class="required">*</span></label>
            <input type="password" name="txt_pass" id="txt_pass">
        </div>

        <div class="row">
            <label class="label">Nhập lại MK <span class="required">*</span></label>
            <input type="password" name="txt_repass" id="txt_repass">
        </div>

        <div class="row">
            <label class="label">Giới tính <span class="required">*</span></label>
            <input type="radio" name="rd_sex" value="Nam" <?php echo (isset($_POST['rd_sex']) && $_POST['rd_sex']=='Nam')?'checked':''; ?>> Nam
            <input type="radio" name="rd_sex" value="Nữ" <?php echo (isset($_POST['rd_sex']) && $_POST['rd_sex']=='Nữ')?'checked':''; ?>> Nữ
        </div>

        <div class="row">
            <label class="label">Sở thích</label>
            <input type="checkbox" name="chk_hobby[]" value="Đọc sách"> Đọc sách
            <input type="checkbox" name="chk_hobby[]" value="Du lịch"> Du lịch
            <input type="checkbox" name="chk_hobby[]" value="Thể thao"> Thể thao
        </div>

        <div class="row">
            <label class="label">Tỉnh <span class="required">*</span></label>
            <select name="sel_province" id="sel_province">
                <option value="">-- Chọn tỉnh --</option>
                <option value="Hà Nội" <?php echo (isset($_POST['sel_province']) && $_POST['sel_province']=='Hà Nội')?'selected':''; ?>>Hà Nội</option>
                <option value="TP.HCM" <?php echo (isset($_POST['sel_province']) && $_POST['sel_province']=='TP.HCM')?'selected':''; ?>>TP.HCM</option>
                <option value="Đà Nẵng" <?php echo (isset($_POST['sel_province']) && $_POST['sel_province']=='Đà Nẵng')?'selected':''; ?>>Đà Nẵng</option>
            </select>
        </div>

        <div class="row">
            <label class="label">Hình ảnh</label>
            <input type="file" name="file_avatar" id="file_avatar">
        </div>

        <div class="row">
            <label class="label"></label>
            <input type="submit" name="btn_register" value="Đăng ký">
            <input type="reset" value="Nhập lại" onclick="document.getElementById('js-error-box').style.display='none';">
        </div>

    </form>

    <script type="text/javascript">
        function validateForm() {
            // 1. Khai báo mảng chứa lỗi
            var errors = [];
            
            // 2. Lấy các giá trị từ form thông qua document.forms
            var form = document.forms["registerForm"];
            var user = form["txt_user"].value;
            var pass = form["txt_pass"].value;
            var repass = form["txt_repass"].value;
            var sex = form["rd_sex"].value; // Radio trả về value của nút được chọn, hoặc rỗng nếu chưa chọn
            var province = form["sel_province"].value;
            var fileInput = document.getElementById("file_avatar");

            // 3. Thực hiện kiểm tra
            
            // - Kiểm tra Tên đăng nhập
            if (user.trim() == "") {
                errors.push("Tên đăng nhập không được để trống (JS).");
            }

            // - Kiểm tra Mật khẩu
            if (pass == "") {
                errors.push("Mật khẩu không được để trống (JS).");
            }

            // - Kiểm tra Nhập lại mật khẩu
            if (repass == "") {
                errors.push("Vui lòng nhập lại mật khẩu (JS).");
            } else if (pass != repass) {
                errors.push("Mật khẩu nhập lại không khớp (JS).");
            }

            // - Kiểm tra Giới tính (Radio button hơi đặc biệt)
            // Cách khác: kiểm tra querySelector
            var sexOption = document.querySelector('input[name="rd_sex"]:checked');
            if (!sexOption) {
                errors.push("Vui lòng chọn giới tính (JS).");
            }

            // - Kiểm tra Tỉnh
            if (province == "") {
                errors.push("Vui lòng chọn Tỉnh (JS).");
            }

            // - Kiểm tra File (nếu có chọn file)
            if (fileInput.value != "") {
                var filePath = fileInput.value;
                // Lấy đuôi file
                var allowedExtensions = /(\.jpg|\.jpeg|\.png|\.bmp|\.gif)$/i;
                if (!allowedExtensions.exec(filePath)) {
                    errors.push("File ảnh không hợp lệ. Chỉ chấp nhận: jpg, png, bmp, gif (JS).");
                }
            }

            // 4. Hiển thị lỗi (nếu có)
            var errorBox = document.getElementById("js-error-box");
            if (errors.length > 0) {
                // Nối mảng lỗi thành chuỗi HTML
                errorBox.innerHTML = "<ul><li>" + errors.join("</li><li>") + "</li></ul>";
                errorBox.style.display = "block"; // Hiện khung lỗi
                
                // Trả về false để CHẶN việc gửi form đi
                return false; 
            } else {
                // Nếu không có lỗi, ẩn khung lỗi và cho phép gửi
                errorBox.style.display = "none";
                return true;
            }
        }
    </script>

</body>
</html>