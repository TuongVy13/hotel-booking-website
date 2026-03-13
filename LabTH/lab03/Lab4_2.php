<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
$n = 0;
$sum = 0;

do {
    $n++;
    $sum += $n;
} while ($sum <= 1000);

echo "n nhỏ nhất là: $n";  
?>

</body>
</html>