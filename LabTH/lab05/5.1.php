<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Lab 5.1 - Sticky Form</title>
</head>

<body>
<?php
// XỬ LÝ DỮ LIỆU NGAY TRÊN CÙNG 1 FILE
// Nếu có dữ liệu POST gửi lên thì in ra
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    echo "<h3>Dữ liệu nhận được:</h3>";
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";
    echo "<hr>";
}
?>

<fieldset>
<legend>Form 3 (Sticky Form - Giữ giá trị sau khi Submit)</legend>

<form action="" method="post">
    Nhập tên: <input type="text" name="ten" 
               value="<?php echo isset($_POST['ten']) ? $_POST['ten'] : ''; ?>">
    <br><br>

    Giới tính: 
    <input type="radio" name="gt" value="1" 
           <?php echo (isset($_POST['gt']) && $_POST['gt'] == '1') ? 'checked' : ''; ?>> Nam
    
    <input type="radio" name="gt" value="0" 
           <?php echo (isset($_POST['gt']) && $_POST['gt'] == '0') ? 'checked' : ''; ?>> Nữ
    <br><br>

    Sở Thích:
    <input type="checkbox" name="st[]" value="tt" 
           <?php echo (isset($_POST['st']) && in_array('tt', $_POST['st'])) ? 'checked' : ''; ?>> Thể Thao
    
    <input type="checkbox" name="st[]" value="dl" 
           <?php echo (isset($_POST['st']) && in_array('dl', $_POST['st'])) ? 'checked' : ''; ?>> Du Lịch
    
    <input type="checkbox" name="st[]" value="game" 
           <?php echo (isset($_POST['st']) && in_array('game', $_POST['st'])) ? 'checked' : ''; ?>> Game
    <br><br>
    
    <input type="submit" value="Gửi dữ liệu">
</form>
</fieldset>

</body>
</html>