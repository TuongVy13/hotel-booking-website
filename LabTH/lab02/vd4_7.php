<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>lab 2_5</title>
</head>

<body>
<?php
	require("lab2_5a.php");
	require("lab2_5b.php");
    require_once("lab2_5b.php");
	echo "Kết quả của vd4_6.php là 30 khác với vd4_7.php là 20";
    echo "<br>Thay include thành require ở lab2_5b.php ở dòng 10 đến 12 sẽ cho ra kết quả của lab2_5b.php không thay đổi với khi dùng include là ";
	if(isset($x))
		echo "Giá trị của x là: $x";
	else
		echo "Biến x không tồn tại";
?>
</body>
</html>