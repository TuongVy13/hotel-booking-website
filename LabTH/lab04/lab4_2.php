<pre>
<?php
// Mảng đầu vào
$a = array(1, -3, 5); 
$b = array("a"=>2, "b"=>4, "c"=>-6);


$dem_duong_a = 0;

foreach ($a as $value) {
    if ($value > 0) {
        $dem_duong_a++;
    }
}


$c = array();

foreach ($b as $key => $value) {
    if ($value > 0) {
        $c[$key] = $value;
    }
}
?>

<h3>Nội dung giá trị mảng a :</h3>
<?php
foreach($a as $value) {
	echo $value ." ";	
}
?>

<br><br><b>Nội dung mảng a (key-value):</b><br>
<?php
foreach($a as $key=>$value) {
	echo "($key - $value ) ";	
}
?>

<br><br><b>Nội dung mảng b (key-value):</b><br>
<?php
foreach($b as $k=>$v) {
	echo "($k - $v) ";	
}
?>

<br><br><b>Hiển thị mảng b dạng bảng:</b>
<table border="1" cellpadding="5">
	<tr><td><b>STT</b></td><td><b>Key</b></td><td><b>Value</b></td></tr>
    <?php
	$i = 0;
	foreach($b as $k=>$v) {
		$i++;
		echo "<tr><td>$i</td><td>$k</td><td>$v</td></tr>";
	}
	?>
</table>

<hr>

Số phần tử dương trong mảng <b>$a</b>: 
<b><?php echo $dem_duong_a; ?></b><br><br>

Mảng <b>$c</b> được tạo từ các phần tử dương của <b>$b</b>:<br>
<?php print_r($c); ?>

</pre>
