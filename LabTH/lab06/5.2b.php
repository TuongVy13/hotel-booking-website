<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Lab 6 - 5.2b: Dân trí</title>
</head>
<body>
    <h1>Tin Xã hội - Dân trí</h1>
    
    <?php
    $url = "https://dantri.com.vn/xa-hoi.htm";
    
    $options = [
        "http" => [
            "header" => "User-Agent: Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)\r\n"
        ],
        "ssl" => ["verify_peer" => false, "verify_peer_name" => false]
    ];
    $context = stream_context_create($options);

    $content = file_get_contents($url, false, $context);

    if ($content) {
        $pattern = '/<h3 class="article-title">.*?<a.*?>(.*?)<\/a>.*?<\/h3>/ims';
        
        preg_match_all($pattern, $content, $matches);
        
        echo "<ol>";
        if (isset($matches[1])) {
            foreach ($matches[1] as $title) {
                echo "<li>" . trim(strip_tags($title)) . "</li>";
            }
        }
        echo "</ol>";
    } else {
        echo "<p>Không thể lấy dữ liệu từ Dân trí.</p>";
    }
    ?>
</body>
</html>