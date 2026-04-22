<?php
// Cho phép Frontend gọi dữ liệu (CORS)
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once '/../config.php';
require_once __DIR__ . '/../db.php'; // Đường dẫn tới file db.php của bạn

// Lấy 12 tấm Quỳnh Hương
$data = callSupabase("SELECT id, name, image_url, audio_url, price FROM hunglouis ORDER BY id ASC LIMIT 50");
function supabaseRequest($endpoint, $method = "GET", $data = null) {
    $URL="https://hmvvjjiiaelcsfqgxbxv.supabase.co"/rest/v1/".$endpoint;
$headers = [
   "apikey: "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImhtdnZqamlpYWVsY3NmcWd4Ynh2Iiwicm9sZSI6InNlcnZpY2Vfcm9sZSIsImlhdCI6MTc3NDM0ODgzNywiZXhwIjoyMDg5OTI0ODM3fQ._nfvEI7MYFfppe-SOrdsS6t5O54wXOPOYDQP-HJoOQQ,
   "Authorization: Bearer "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImhtdnZqamlpYWVsY3NmcWd4Ynh2Iiwicm9sZSI6InNlcnZpY2Vfcm9sZSIsImlhdCI6MTc3NDM0ODgzNywiZXhwIjoyMDg5OTI0ODM3fQ._nfvEI7MYFfppe-SOrdsS6t5O54wXOPOYDQP-HJoOQQ,
   "Content-Type: application/json"
    ];


    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);

    if ($data) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    }

    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response, true);
}

