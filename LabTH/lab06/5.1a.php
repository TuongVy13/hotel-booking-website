<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Lab 6 - Nâng cao 5.1: Web Scraping</title>
    <style>
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>

<h1>Kết quả lấy tin từ VnExpress</h1>

<?php
$url = "https://vnexpress.net/the-thao"; 

if (!ini_get('allow_url_fopen')) {
    echo "<p style='color:red;'>Lỗi: Cần bật 'allow_url_fopen' trong php.ini</p>";
    exit;
}

$content = @file_get_contents($url);

if ($content === FALSE) {
    echo "<p>Không thể lấy dữ liệu từ URL. Có thể do tường lửa hoặc website chặn bot.</p>";
    $content = '
        <html>
            <body>
                <div class="title_news"><a href="#">Tin thể thao 1: Việt Nam vô địch</a></div>
                <div class="other_content">Quảng cáo...</div>
                <div class="title_news"><a href="#">Tin thể thao 2: Manchester United thắng lớn</a></div>
                <div class="footer">Email liên hệ: admin@vnexpress.net - Hotline: 0909123456</div>
                <a href="https://google.com">Link Google</a>
            </body>
        </html>
    ';
    echo "<p><em>(Đang sử dụng dữ liệu mẫu để demo)</em></p>";
}

$pattern = '/<div class="title_news">(.*?)<\/div>/ims';

preg_match_all($pattern, $content, $matches);
?>

<h3>Danh sách tin tức tìm thấy:</h3>
<table>
    <thead>
        <tr>
            <th>STT</th>
            <th>Nội dung gốc (HTML)</th>
            <th>Nội dung text (Đã lọc thẻ)</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if (isset($matches[1]) && count($matches[1]) > 0) {
            foreach ($matches[1] as $index => $value) {
                $clean_text = strip_tags($value); 
                echo "<tr>";
                echo "<td>" . ($index + 1) . "</td>";
                echo "<td>" . htmlspecialchars($value) . "</td>";
                echo "<td>" . $clean_text . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='3'>Không tìm thấy tin nào khớp mẫu.</td></tr>";
        }
        ?>
    </tbody>
</table>

<?php

echo "<h3>Các thông tin trích xuất khác:</h3>";

$link_pattern = '/href=["\']?([^"\'>]+)["\']?/';
preg_match_all($link_pattern, $content, $links);
echo "<p><strong>Link tìm thấy:</strong> " . implode(", ", $links[1]) . "</p>";

?>

</body>
</html>