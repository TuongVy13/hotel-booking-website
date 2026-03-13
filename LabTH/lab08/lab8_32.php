<?php

include_once "db.php";

$book_id = isset($_GET["book_id"])?$_GET["book_id"]:"";
$sql ="delete from book where book_id = :book_id ";
$arr = array(":book_id"=>$book_id);

$stm = $pdh->prepare($sql);
$stm->execute($arr);
$n = $stm->rowCount();
if ($n>0) $thongbao="Da xoa $n loai sach! ";
else $thongbao="Loi xoa!";
?>
<script language="javascript">
alert("<?php echo $thongbao;?>");
window.location = "lab8_3_2.php";
</script>