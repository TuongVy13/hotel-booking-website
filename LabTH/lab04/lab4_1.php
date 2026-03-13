<pre>
<?php
// Khởi tạo các mảng mẫu
$a = array();                 // mảng rỗng
$b = array(1, 3, 5);          // mảng số
$c = array("a"=>2, "b"=>4, "c"=>6); // mảng key-value

echo "=== Mảng ban đầu ===<br>";
print_r($a);
print_r($b);
print_r($c);

echo "<hr>";


echo "Kiểm tra phần tử 3 trong mảng b:<br>";

$searchValue = 3;
$index = array_search($searchValue, $b);    // tìm vị trí

if ($index !== false) {
    echo "Tìm thấy $searchValue → Tiến hành xóa<br>";
    unset($b[$index]);
} else {
    echo "Không tìm thấy $searchValue trong mảng b<br>";
}

echo "Mảng b sau khi thay đổi:<br>";
print_r($b);

echo "<hr>";


echo "Kiểm tra key 'b' trong mảng c:<br>";

$key = "b";

if (array_key_exists($key, $c)) {
    echo "Tìm thấy key '$key' → tăng giá trị thêm 10<br>";
    $c[$key] += 10;
} else {
    echo "Không có key '$key' → thêm mới với giá trị 10<br>";
    $c[$key] = 10;
}

echo "Mảng c sau khi thay đổi:<br>";
print_r($c);

?>
</pre>
