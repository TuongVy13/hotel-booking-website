<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>lab 2_5.3</title>
</head>

<body>
    <?php
    function PTB2($a, $b, $c)
    {
        if ($a == 0) {
            if ($b == 0) {
                if ($c == 0) {
                    echo "Phương trình có vô nghiệm.";
                } else {
                    echo "Phương trình vô nghiệm.";
                }
            } else {
                $kq = -$c / $b;
                echo "Phương trình có 1 nghiệm là $kq";
            }
        } else {
            $d = $b * $b - 4 * $a * $c;
            if ($d < 0) {
                $kq = -$b / 2 * $a;
                echo "Phương trình có nghiệm kép là $kq";
            } else {
                $x1 = (-$b + sqrt($d)) / 2 * $a;
                $x2 = (-$b - sqrt($d)) / 2 * $a;
                echo "Phương trình có 2 nghiệm x1= $x1, x2=$x2";
            }
        }
    }
    echo PTB2(1,-3,2);
    ?>
</body>

</html>