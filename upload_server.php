<?php
session_start();

 $message = '';
 $request_data = $_REQUEST; 
 $url = $request_data['url'] ?? null;
 $description = $request_data['des'] ?? null;

if ($url && $description) {

    $ch = curl_init("https://api.downloadgram.org/media");
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(['url' => $url]));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36"
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($ch);
    preg_match_all('/https:\/\/cdn\.downloadgram\.org\/\?token=[^"]+/', $response, $matches);

    if (!empty($matches[0]) && isset($matches[0][1])) {
        $videoLink = rtrim($matches[0][1], '\\');

        $video_ch = curl_init($videoLink);
        curl_setopt($video_ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($video_ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($video_ch, CURLOPT_SSL_VERIFYPEER, false);
        $videoData = curl_exec($video_ch);
        $httpCode = curl_getinfo($video_ch, CURLINFO_HTTP_CODE);

        if ($httpCode == 200 && $videoData !== false && !empty($videoData)) {
            
            if (!file_exists('videos')) {
                mkdir('videos', 0755, true);
            }
            $filename = 'videos/' . uniqid('video_', true) . '.mp4';
            if (file_put_contents($filename, $videoData)) {
                
                $all_data = [];
                if (file_exists('video.json')) {
                    $json_content = file_get_contents('video.json');
                    $all_data = json_decode($json_content, true);
                }

                $new_entry = [
                    'user' => $_SESSION['username'],
                    'video_path' => $filename, 
                    'instagram_url' => $url,
                    'description' => $description,
                    'time' => date('Y-m-d H:i:s')
                ];

                $all_data[] = $new_entry;
                $json_string = json_encode($all_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                file_put_contents('video.json', $json_string);
            }
        } 
    } 
} 

?>
