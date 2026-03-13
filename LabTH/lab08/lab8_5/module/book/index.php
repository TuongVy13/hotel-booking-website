<?php
// PHẢI CÓ HÀM getIndex (nếu file này không include common.php)
if (!function_exists('getIndex')) {
    function getIndex($name, $default = "")
    {
        return isset($_GET[$name]) ? $_GET[$name] : $default;
    }
}

$book = new Book();
$ac = getIndex("ac", "home");

switch ($ac) {
    case "home":
        include ROOT . "/module/book/home.php";
        break;

    case "list":
        include ROOT . "/module/book/list.php";
        break;

    case "detail":
        include ROOT . "/module/book/detail.php";
        break;

    default:
        include ROOT . "/module/book/home.php";
        break;
}
?>
