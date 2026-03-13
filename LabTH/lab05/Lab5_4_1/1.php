<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Danh sách sản phẩm</title>
</head>
<body>
    <h3>Danh sách sản phẩm</h3>
    
    <?php
    $arr = array();

    $r = array("id" => 1, "name" => "Product1");
    $arr[] = $r;

    $r = array("id" => 2, "name" => "Product2");
    $arr[] = $r;

    $r = array("id" => 3, "name" => "Product3");
    $arr[] = $r;

    $r = array("id" => 4, "name" => "Product4");
    $arr[] = $r;

    echo "<ul>";
    foreach ($arr as $product) {
        $id = $product['id'];
        $name = $product['name'];

        echo "<li>";
        echo "<a href='2.php?id={$id}'>{$name}</a>";
        echo "</li>";
    }
    echo "</ul>";
    ?>
</body>
</html>