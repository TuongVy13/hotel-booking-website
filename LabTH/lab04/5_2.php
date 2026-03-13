<?php
function showArray2D($arr)
{
    echo "<table border='1' cellpadding='5' cellspacing='0'>";
    echo "<tr><th>STT</th><th>Mã Sản Phẩm</th><th>Tên Sản Phẩm</th></tr>";

    $i = 1;
    foreach($arr as $row){
        echo "<tr>";
        echo "<td>".$i++."</td>";
        echo "<td>".$row['id']."</td>";
        echo "<td>".$row['name']."</td>";
        echo "</tr>";
    }

    // ➜ Thêm dòng cuối merge 3 cột lại
    $tong = count($arr);
    echo "<tr>";
    echo "<td colspan='3' style='text-align:center; font-weight:bold;'>Tổng số sản phẩm: $tong</td>";
    echo "</tr>";

    echo "</table>";
}

// Tạo mảng 2 chiều
$arr = array();

$r = array("id"=>"sp1", "name"=>"Sản phẩm 1");
$arr[] = $r;

$r = array("id"=>"sp2", "name"=>"Sản phẩm 2");
$arr[] = $r;

$r = array("id"=>"sp3", "name"=>"Sản phẩm 3");
$arr[] = $r;

showArray2D($arr);
?>
