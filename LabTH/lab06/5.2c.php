<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Lab 6 - 5.2c: VietnamNet</title>
</head>
<body>
    <h1>Tin Xã hội - VietnamNet</h1>
    
    <?php
    $url = "https://vietnamnet.vn/thoi-su";
    
    $options = [
        "http" => [
            "header" => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64)\r\n"
        ],
        "ssl" => ["verify_peer" => false, "verify_peer_name" => false]
    ];
    $context = stream_context_create($options);

    $content = file_get_contents($url, false, $context);

    if ($content) {
        $pattern = '/<h3.*?<a.*?>(.*?)<\/a>.*?<\/h3>/ims';
        
        preg_match_all($pattern, $content, $matches);
        
        echo "<table border='1' cellpadding='5'>";
        echo "<tr><th>STT</th><th>Tiêu đề tin</th></tr>";
        if (isset($matches[1])) {
            $i = 1;
            foreach ($matches[1] as $title) {
                $clean_title = trim(strip_tags($title));
                if (strlen($clean_title) > 10) {
                    echo "<tr>";
                    echo "<td>$i</td>";
                    echo "<td>$clean_title</td>";
                    echo "</tr>";
                    $i++;
                }
            }
        }
        echo "</table>";
    } else {
        echo "<p>Không thể lấy dữ liệu từ VietnamNet.</p>";
    }
    ?>
</body>
</html>