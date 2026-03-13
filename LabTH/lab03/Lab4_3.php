<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
//Kết hợp hàm và vòng lặp
function kiemtranguyento($x) //Kiểm tra 1 số có nguyên tố hay không
{
    if ($x < 2)
        return false;
    if ($x == 2)
        return true;

    $i = 2;
    do {
        if ($x % $i == 0)
            return false;

        $i++;
    } while ($i <= sqrt($x));

    return true;
}

if (kiemtranguyento(12))
    echo "là số nguyên tố";
else
    echo "không phải số nguyên tố";
?>

</body>
</html>