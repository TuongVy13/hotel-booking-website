<?php
if (!defined("ROOT")) {
	echo "You don't have permission to access this page!";
	exit;
}

// Lấy tham số mod, mặc định là rỗng
$mod = isset($_GET["mod"]) ? $_GET["mod"] : "";
$ac = isset($_GET["ac"]) ? $_GET["ac"] : "";

// Mặc định đường dẫn nếu không chọn gì cả (Trang chủ Admin)
$path = "module/dashboard.php";

if ($mod == "sach") {
	$path = "module/sach/index.php";
} elseif ($mod == "loai") {
	$path = "module/loai/index.php";
} elseif ($mod == "nhaxb") {
	$path = "module/nhaxb/index.php";
} elseif ($mod == "order") {
	$path = "module/order/index.php";
} elseif ($mod == "user") {
	$path = "module/user/index.php";
}

// Kiểm tra và include file
if (file_exists($path)) {
	include $path;
} else {
	// Trường hợp chưa tạo file hoặc đường dẫn sai
	echo "Hệ thống đang cập nhật module: <b>$mod</b>";
}
