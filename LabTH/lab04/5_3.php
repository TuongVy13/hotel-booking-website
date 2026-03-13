<?php
// Danh sách câu hỏi
$questions = array(
    "PHP là viết tắt của từ gì?",
    "Hàm nào dùng để đếm số phần tử trong mảng?",
    "Biến trong PHP bắt đầu bằng ký tự nào?",
    "Lệnh nào dùng để in ra màn hình?",
    "Hàm sắp xếp tăng dần theo value?",
    "Hàm để tạo cookie trong PHP là gì?",
    "Hàm nào kiểm tra một biến có tồn tại không?"
);

// số lượng câu hỏi
$n = count($questions);

// lấy ngẫu nhiên $m câu hỏi
$m = 3;
$rand_keys = array_rand($questions, $m);

echo "<h2>Đề thi trắc nghiệm ($m câu)</h2>";
echo "<ol>";

foreach($rand_keys as $k){
    echo "<li>".$questions[$k]."</li>";
}

echo "</ol>";
?>
