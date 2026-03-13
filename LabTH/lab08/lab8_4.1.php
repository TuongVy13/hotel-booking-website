<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Tìm kiếm sách</title>
</head>
<body>

<h2>Tìm kiếm sách theo tên</h2>

<form method="post">
    <label>Nhập tên sách:</label>
    <input type="text" name="keyword" required>
    <input type="submit" value="Search">
</form>

<hr>

<?php
include_once "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $keyword = $_POST["keyword"];
    $sql = "SELECT * FROM book WHERE book_name LIKE :kw";
    $stm = $pdh->prepare($sql);
    $stm->bindValue(":kw", "%$keyword%");
    $stm->execute();

    $rows = $stm->fetchAll(PDO::FETCH_ASSOC);

    if (count($rows) > 0) {
        echo "<h3>Kết quả tìm kiếm:</h3>";
        echo "<table border='1' cellpadding='5'>";
        echo "<tr>
                <th>ID</th>
                <th>Tên sách</th>
                <th>Mô tả</th>
                <th>Giá</th>
                <th>Publisher</th>
                <th>Category</th>
              </tr>";

        foreach ($rows as $r) {
            echo "<tr>";
            echo "<td>" . $r['book_id'] . "</td>";
            echo "<td>" . $r['book_name'] . "</td>";
            echo "<td>" . $r['description'] . "</td>";
            echo "<td>" . $r['price'] . "</td>";
            echo "<td>" . $r['pub_id'] . "</td>";
            echo "<td>" . $r['cat_id'] . "</td>";
            echo "</tr>";
        }

        echo "</table>";
    }
    else {
        echo "<p>Không tìm thấy sách nào.</p>";
    }
}
?>

</body>
</html>
