<?php
$limit = 10; 
$cat_id = getIndex("cat_id", "all");
$pub_id = getIndex("pub_id", "all");
$sql ="select * from book where 1 ";
$arr = array();
if ($cat_id !="all")
{	$sql .=" and cat_id =:cat_id ";
	$arr[":cat_id"] = $cat_id;
}

if ($pub_id !="all")
{	$sql .=" and pub_id =:pub_id ";
	$arr[":pub_id"] = $pub_id;
}


$page = isset($_GET["page"]) ? (int)$_GET["page"] : 1;
if ($page < 1) $page = 1;

$start = ($page - 1) * $limit;


$sqlCount = "select count(*) as total from book where 1 ";
$arrCount = array();
if ($cat_id != "all") {
    $sqlCount .= " and cat_id = :cat_id ";
    $arrCount[":cat_id"] = $cat_id;
}
if ($pub_id != "all") {
    $sqlCount .= " and pub_id = :pub_id ";
    $arrCount[":pub_id"] = $pub_id;
}
$totalRowArr = $book->select($sqlCount, $arrCount);
$total = isset($totalRowArr[0]['total']) ? (int)$totalRowArr[0]['total'] : 0;
$totalPage = ($total > 0) ? ceil($total / $limit) : 1;


$sqlWithLimit = $sql . " LIMIT $start, $limit ";
$list = $book->select($sqlWithLimit, $arr);


echo "<p>Có <strong>$total</strong> kết quả (Hiển thị <strong>".$book->getRowCount()."</strong> trên trang này).</p>";


foreach($list as $r)
{
    ?>
    <div class="book">
        <?php echo htmlspecialchars($r["book_name"]);?>
    </div>
    <?php   
}
?>


<?php if ($totalPage > 1): ?>
    <div class="pagination" style="margin-top:15px;">
        <?php
        
        $prev = $page - 1;
        if ($prev >= 1) {
            echo "<a href='index.php?mod=book&ac=list&page=$prev&cat_id=$cat_id&pub_id=$pub_id'>&laquo; Prev</a> ";
        }

      
        for ($i = 1; $i <= $totalPage; $i++) {
            if ($i == $page) {
                echo "<strong style='margin:0 6px;'>[$i]</strong>";
            } else {
                echo "<a style='margin:0 6px;' href='index.php?mod=book&ac=list&page=$i&cat_id=$cat_id&pub_id=$pub_id'>$i</a>";
            }
        }

    
        $next = $page + 1;
        if ($next <= $totalPage) {
            echo " <a href='index.php?mod=book&ac=list&page=$next&cat_id=$cat_id&pub_id=$pub_id'>Next &raquo;</a>";
        }
        ?>
    </div>
<?php endif; ?>
