<?php
function showArray($arr)
{
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><td><b>STT</b></td><td><b>Key</b></td><td><b>Value</b></td></tr>";

    $i = 1;
    foreach ($arr as $k => $v) {
        echo "<tr>";
        echo "<td>$i</td>";
        echo "<td>$k</td>";
        echo "<td>$v</td>";
        echo "</tr>";
        $i++;
    }

    echo "</table>";
}
?>
<?php
$a = array(6, 2, 7, 8, 5);
$b = array("a"=>4, "b"=>2, "c"=>3, "d"=>8);

echo "<h3>Mảng a:</h3>";
showArray($a);

echo "<h3>Mảng b:</h3>";
showArray($b);
?>

