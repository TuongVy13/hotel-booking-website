<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Lab 6 - 5.2a: Zing News (Fixed)</title>
</head>

<body>
    <h1>Tin Xã hội - Znews (Zing News)</h1>

    <?php
    $url = "https://znews.vn/xa-hoi.html";

    $options = [
        "http" => [
            "method" => "GET",
            "header" => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36\r\n" .
                "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,*/*;q=0.8\r\n" .
                "Accept-Language: vi-VN,vi;q=0.9,en-US;q=0.8,en;q=0.7\r\n"
        ],
        "ssl" => [
            "verify_peer" => false,
            "verify_peer_name" => false,
        ]
    ];
    $context = stream_context_create($options);

    $content = @file_get_contents($url, false, $context);

    if ($content) {
        $pattern = '/class="[^"]*article-title[^"]*".*?>(.*?)<\/(?:p|h3|a)>/ims';

        preg_match_all($pattern, $content, $matches);

        if (isset($matches[1]) && count($matches[1]) > 0) {
            echo "<ul>";
            foreach ($matches[1] as $title) {
                $clean_title = trim(strip_tags($title));
                if (!empty($clean_title)) {
                    echo "<li>" . $clean_title . "</li>";
                }
            }
            echo "</ul>";
            echo "<p><i>Đã tìm thấy " . count($matches[1]) . " tin.</i></p>";
        } else {
            echo "<p style='color:red'>Đã tải được trang web nhưng không khớp mẫu Regex nào.</p>";
            echo "<p>Cấu trúc HTML có thể đã thay đổi. Hãy dùng chức năng 'Inspect Element' (F12) trên trình duyệt để kiểm tra class của tiêu đề tin.</p>";
        }
    } else {
        echo "<p style='color:red'>Không thể kết nối đến Znews. Có thể IP của bạn bị chặn hoặc lỗi mạng.</p>";
    }
    ?>
</body>

</html>