<?php
session_start();
$ch = curl_init($_GET["url"]);

curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
    'Referer: https://downloadgram.org/' 
]);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

 $videoData = curl_exec($ch);
 $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
 $contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
if ($httpCode == 200 && $videoData !== false && !empty($videoData)) {
    header('Content-Type: ' . $contentType);
    header('Content-Length: ' . strlen($videoData));
    header('Cache-Control: public, max-age=3600');
    header('Content-Disposition: inline; filename="video.mp4"');
    ob_clean();
    flush();
    echo $videoData;
    exit;
    $all_data = [];
        
        if (file_exists('video.json')) {
            $json_content = file_get_contents('video.json');
        }
        
         $new_entry = [
            'user' => $_SESSION['username'],
            'video' => $cleanLink,
            'instagram' => $instagramUrl,
            'description' => $_GET["des"],
            'time' => date('Y-m-d H:i:s')
        ];
        
         $all_data[] = $new_entry;
         $json_string = json_encode($all_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        file_put_contents('video.json', $json_string);
}

?>