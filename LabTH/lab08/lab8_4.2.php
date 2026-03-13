<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="utf-8" />
<title>Quản lý Sách</title>
<style>
#container { width: 800px; margin: 0 auto; }
table { border-collapse: collapse; width: 100%; }
table, th, td { border: 1px solid black; padding: 5px; }
</style>
</head>

<body>
<div id="container">

<h2>Thêm Sách</h2>

<form action="lab8_book.php" method="post">
<table>
<tr><td>Mã sách:</td><td><input type="text" name="book_id" required></td></tr>
<tr><td>Tên sách:</td><td><input type="text" name="book_name" required></td></tr>
<tr><td>Mô tả:</td><td><input type="text" name="description"></td></tr>
<tr><td>Giá:</td><td><input type="number" name="price" required></td></tr>
<tr><td>Publisher ID:</td><td><input type="text" name="pub_id" required></td></tr>
<tr><td>Category ID:</td><td><input type="text" name="cat_id" required></td></tr>

<tr><td colspan="2"><input type="submit" name="sm" value="Insert"></td></tr>
</table>
</form>

<?php

include_once "db.php";


if (isset($_POST["sm"])) {
    $sql = "INSERT INTO book(book_id, book_name, description, price, pub_id, cat_id) 
            VALUES(:book_id, :book_name, :description, :price, :pub_id, :cat_id)";
    $arr = array(
        ":book_id" => $_POST["book_id"],
        ":book_name" => $_POST["book_name"],
        ":description" => $_POST["description"],
        ":price" => $_POST["price"],
        ":pub_id" => $_POST["pub_id"],
        ":cat_id" => $_POST["cat_id"]
    );

    $stm = $pdh->prepare($sql);
    $stm->execute($arr);

    $n = $stm->rowCount();
    if ($n > 0) echo "<p style='color:green;'>Đã thêm $n sách!</p>";
    else echo "<p style='color:red;'>Lỗi khi thêm!</p>";
}


if (isset($_GET["delete"])) {
    $id = $_GET["delete"];
    $sql = "DELETE FROM book WHERE book_id = :id";
    $stm = $pdh->prepare($sql);
    $stm->execute([":id" => $id]);

    echo "<p style='color:blue;'>Đã xóa sách có ID: $id</p>";
}

$stm = $pdh->prepare("SELECT * FROM book");
$stm->execute();
$rows = $stm->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Danh sách Sách</h2>

<table>
<tr>
    <th>ID</th>
    <th>Tên sách</th>
    <th>Mô tả</th>
    <th>Giá</th>
    <th>Publisher</th>
    <th>Category</th>
    <th>Thao tác</th>
</tr>

<?php
foreach ($rows as $row) {
?>
<tr>
    <td><?php echo $row["book_id"]; ?></td>
    <td><?php echo $row["book_name"]; ?></td>
    <td><?php echo $row["description"]; ?></td>
    <td><?php echo $row["price"]; ?></td>
    <td><?php echo $row["pub_id"]; ?></td>
    <td><?php echo $row["cat_id"]; ?></td>
    <td>
        <a href="lab8_book.php?delete=<?php echo $row['book_id']; ?>" 
           onclick="return confirm('Xóa sách này?');">Xóa</a>
    </td>
</tr>
<?php } ?>

</table>

</div>
</body>
</html>
