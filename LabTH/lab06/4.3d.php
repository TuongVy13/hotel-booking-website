<?php
$url = "http://thethao.vnexpress.net/";

$content = @file_get_contents($url);
if ($content !== false) {
    echo "✔️ Đọc được trang web. <br/>";

    $pattern = '/<h3\s+class=["\']title-news["\'][^>]*>(.*?)<\/h3>/is';
    preg_match_all($pattern, $content, $arr);

    if (!empty($arr[1])) {
        echo '<table width="100%" border="1" cellspacing="0" cellpadding="5">';
        echo '<tr><td>STT</td><td>Nội dung</td></tr>';
        $i = 1;
        foreach ($arr[1] as $h3Content) {
            echo '<tr><td>' . $i++ . '</td><td>' . htmlspecialchars($h3Content) . '</td></tr>';
        }
        echo '</table>';
    } else {
        echo "Không tìm thấy các thẻ <h3 class='title-news'>";
    }
} else {
    echo "Không thể đọc nội dung từ: $url";
}
?>