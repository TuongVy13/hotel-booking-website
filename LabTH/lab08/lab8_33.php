<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Quản lý loại sách (1 file)</title>
<style>
#container{ width:700px; margin:0 auto; font-family:Arial; }
table{ border-collapse:collapse; width:100%; margin-top:15px; }
td, th{ border:1px solid #ccc; padding:8px; text-align:left; }
input[type=text]{ width:100%; padding:5px; }
.btn{ padding:6px 12px; }
</style>
</head>

<body>
<div id="container">

<?php
include_once "db.php";


if (isset($_GET["delete"])) {
    $id = $_GET["delete"];
    $stm = $pdh->prepare("DELETE FROM category WHERE cat_id=:id");
    $stm->execute([":id"=>$id]);
    echo "<p style='color:green'>Đã xóa!</p>";
}


$editMode = false;
$editData = null;

if (isset($_GET["edit"])) {
    $editMode = true;
    $id = $_GET["edit"];

    $stm = $pdh->prepare("SELECT * FROM category WHERE cat_id=:id");
    $stm->execute([":id"=>$id]);
    $editData = $stm->fetch(PDO::FETCH_ASSOC);
}


if (isset($_POST["save"])) {

    
    if ($_POST["mode"] == "edit") {
        $sql = "UPDATE category SET cat_name=:name WHERE cat_id=:id";
        $stm = $pdh->prepare($sql);
        $stm->execute([
            ":id" => $_POST["cat_id"],
            ":name" => $_POST["cat_name"]
        ]);
        echo "<p style='color:green'>Đã cập nhật!</p>";

    } else { 
     
        $sql = "INSERT INTO category(cat_id, cat_name) VALUES(:id, :name)";
        $stm = $pdh->prepare($sql);
        $stm->execute([
            ":id" => $_POST["cat_id"],
            ":name" => $_POST["cat_name"]
        ]);
        echo "<p style='color:green'>Đã thêm mới!</p>";
    }
}


$stm = $pdh->query("SELECT * FROM category ORDER BY cat_id");
$rows = $stm->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>QUẢN LÝ LOẠI SÁCH</h2>


<form method="post">
<input type="hidden" name="mode" value="<?php echo $editMode ? 'edit' : 'add'; ?>">

<table>
<tr>
    <td>Mã loại:</td>
    <td>
        <input type="text" name="cat_id" 
               value="<?php echo $editMode ? $editData['cat_id'] : ''; ?>" 
               <?php echo $editMode ? "readonly" : ""; ?> />
    </td>
</tr>

<tr>
    <td>Tên loại:</td>
    <td>
        <input type="text" name="cat_name"
               value="<?php echo $editMode ? $editData['cat_name'] : ''; ?>" />
    </td>
</tr>

<tr>
    <td colspan="2">
        <input type="submit" name="save" class="btn" 
               value="<?php echo $editMode ? 'Update' : 'Insert'; ?>" />
        <?php if ($editMode) { ?>
            <a href="category.php" class="btn">Hủy</a>
        <?php } ?>
    </td>
</tr>
</table>
</form>


<h2>DANH SÁCH LOẠI</h2>

<table>
<tr>
    <th>Mã loại</th>
    <th>Tên loại</th>
    <th>Thao tác</th>
</tr>

<?php foreach($rows as $row){ ?>
<tr>
    <td><?php echo $row["cat_id"]; ?></td>
    <td><?php echo $row["cat_name"]; ?></td>
    <td>
        <a href="category.php?edit=<?php echo $row["cat_id"]; ?>">Sửa</a> |
        <a href="category.php?delete=<?php echo $row["cat_id"]; ?>"
           onclick="return confirm('Xóa loại này?')">Xóa</a>
    </td>
</tr>
<?php } ?>
</table>

</div>
</body>
</html>
