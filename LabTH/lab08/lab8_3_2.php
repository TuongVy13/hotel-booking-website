<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Quản lý loại sách</title>
<style>
#container{width:600px; margin:0 auto;}
</style>
</head>

<body>
<div id="container">

<form action="lab8_3_2.php" method="post">
<table>
<tr><td>Ma Sach:</td><td><input type="text" name="book_id" /></td></tr>
<tr><td>Ten Sach:</td><td><input type="text" name="book_name" /></td></tr>
<tr><td>Description:</td><td><input type="text" name="description" /></td></tr>
<tr><td>Price:</td><td><input type="text" name="price" /></td></tr>
<tr><td>Hinh Anh:</td><td><input type="text" name="img" /></td></tr>
<tr><td>Ma NCC:</td><td><input type="text" name="pub_id" /></td></tr>
<tr><td>Ma Loai:</td><td><input type="text" name="cat_id" /></td></tr>
<tr><td colspan="2"> <input type="submit" name="sm" value="Insert" /></td></tr>
</table>
</form>
<?php
include_once "db.php";

if (isset($_POST["sm"]))
{
	$sql="insert into book(book_id, book_name, description, price , img, pub_id, cat_id) values(:book_id, :book_name, :description, :price , :img, :pub_id, :cat_id) ";
	$arr = array(":book_id"=>$_POST["book_id"], ":book_name"=>$_POST["book_name"],":description"=>$_POST["description"],":price"=>$_POST["price"],":img"=>$_POST["img"],":pub_id"=>$_POST["pub_id"],":cat_id"=>$_POST["cat_id"]);
	$stm= $pdh->prepare($sql);
	$stm->execute($arr);
	$n = $stm->rowCount();
	if ($n>0) echo "Đã thêm $n loại ";
	else echo "Lỗi thêm ";
}
$stm = $pdh->prepare("select * from book");
$stm->execute();
$rows = $stm->fetchAll(PDO::FETCH_ASSOC);
?>
<table><tr><td>Ma Sach</td><td>Tên sach</td><td>Description</td><td>Price</td><td>Hinh Anh</td><td>Ma NCC</td><td>Ma Loai</td>
		<td>Thao tác</td></tr>
<?php
foreach($rows as $row)
{
	?>
    <tr><td><?php echo $row["book_id"];?></td>
    	<td><?php echo $row["book_name"];?></td>
        <td><a href='lab8_31.php?cat_id=<?php echo $row["cat_id"];?>'>Xóa</a></td>
        </tr>
    <?php
}
?>
</table>
</div>
</body>
</html>