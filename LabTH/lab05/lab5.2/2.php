<?php
function postIndex($index, $value = "")
{
	if (!isset($_POST[$index])) return $value;
	return $_POST[$index];
}

$sm     = postIndex("submit");
$ten    = postIndex("ten");
$gt     = postIndex("gt");
// Danh sách định dạng cho phép
$arrImg = array("image/png", "image/jpeg", "image/bmp", "image/gif");

// Kiểm tra nếu chưa submit thì quay về
if ($sm == "") {
	header("location:1.php");
	exit;
}

$err = "";
if ($ten == "") $err .= "Phải nhập tên <br>";
if ($gt == "") $err .= "Phải chọn giới tính <br>";

// Mảng chứa tên các file upload thành công để hiển thị sau
$uploadedFiles = array();

// --- BẮT ĐẦU XỬ LÝ NHIỀU FILE ---
if (isset($_FILES["hinh"])) {
	$files = $_FILES["hinh"];
	// Đếm số lượng file được gửi lên
	$count = count($files["name"]);

	// Duyệt qua từng file
	for ($i = 0; $i < $count; $i++) {
		// Lấy thông tin của file thứ i
		$fileName = $files["name"][$i];
		$fileType = $files["type"][$i];
		$fileTmp  = $files["tmp_name"][$i];
		$fileErr  = $files["error"][$i];

		// Bỏ qua nếu không có file nào được chọn ở vị trí này
		if ($fileName == "") continue;

		if ($fileErr > 0) {
			$err .= "Lỗi file hình: " . $fileName . "<br>";
		} else {
			if (!in_array($fileType, $arrImg)) {
				$err .= "File <b>" . $fileName . "</b> không phải định dạng hình ảnh cho phép<br>";
			} else {
				// Di chuyển file
				// Lưu ý: Cần tạo thư mục 'image' trước nếu chưa có
				if (move_uploaded_file($fileTmp, "image/" . $fileName)) {
					// Thêm tên file vào mảng thành công
					$uploadedFiles[] = $fileName;
				} else {
					$err .= "Không thể lưu file: " . $fileName . "<br>";
				}
			}
		}
	}
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Lab5_3/2 - Kết quả</title>
</head>

<body>
	<?php
	if ($err != "") {
		echo "<div style='color:red'>" . $err . "</div>";
	}

	if ($ten != "" && $gt != "") {
		if ($gt == "1") echo "<h3>Chào Anh: $ten </h3>";
		else echo "<h3>Chào Chị: $ten </h3>";

		echo "<hr>";
		echo "<b>Danh sách hình ảnh đã upload:</b><br><br>";

		if (!empty($uploadedFiles)) {
			foreach ($uploadedFiles as $imgName) {
				echo "<div style='display:inline-block; margin:10px; text-align:center;'>";
				echo "<img src='image/$imgName' width='150' height='150' style='object-fit:cover; border:1px solid #ccc'><br>";
				echo "<span>$imgName</span>";
				echo "</div>";
			}
		} else {
			echo "Chưa có hình nào được upload thành công.";
		}
	}
	?>
	<p>
		<a href="1.php">Tiếp tục upload</a>
	</p>
</body>

</html>