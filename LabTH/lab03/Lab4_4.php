<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab 3.5</title>
</head>
<body>
<?php
function BCC(
    $n, 
    $colorHead = "lightgreen", 
    $color1 = "white", 
    $color2 = "lightyellow"
) {
    $html = "<table border='1' cellspacing='0' cellpadding='5'>";

    $html .= "<tr style='background-color: $colorHead;'>
                <th colspan='3'>Bảng cửu chương $n</th>
              </tr>";

    for ($i = 1; $i <= 10; $i++) {

        $bgColor = ($i % 2 == 1) ? $color1 : $color2;

        $html .= "<tr style='background-color: $bgColor;'>
                    <td>$n</td>
                    <td>$i</td>
                    <td>" . ($n * $i) . "</td>
                  </tr>";
    }

    $html .= "</table><br>";

    return $html;
}
?>
<?php
// Nạp file function.php


// Gọi hàm và in ra bảng
echo BCC(5, "pink", "white", "lightblue");
echo BCC(7); // dùng màu mặc định
echo BCC(9, "orange", "lightgray", "lightyellow");
?>

</body>
</html>
