<?php

/**
 * Fetch a URL and return its content.
 * Try file_get_contents first (if allow_url_fopen enabled),
 * then fall back to cURL if available. Returns an error message on failure.
 */
function fetch_url_content($url, $timeout = 10)
{
    if (ini_get('allow_url_fopen')) {
        $context = stream_context_create([
            'http' => [
                'timeout' => $timeout,
                'ignore_errors' => true,
            ],
            'ssl' => [
                'verify_peer' => true,
                'verify_peer_name' => true,
            ],
        ]);
        $content = @file_get_contents($url, false, $context);
        if ($content !== false) {
            return $content;
        }
    }

    if (function_exists('curl_init')) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        $content = curl_exec($ch);
        if ($content === false) {
            $err = curl_error($ch);
            curl_close($ch);
            return "Error fetching URL (cURL): " . htmlspecialchars($err);
        }
        curl_close($ch);
        return $content;
    }

    return "Error: cannot fetch URL. `allow_url_fopen` disabled and cURL not available.`";
}

$url = 'https://vnexpress.net/the-thao';
$content = fetch_url_content($url);
echo $content;
