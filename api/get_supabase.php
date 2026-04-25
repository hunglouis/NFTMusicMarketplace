<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// 1. Khai báo trực tiếp - Không dùng hàm để tránh lỗi Undefined variable
$supabaseUrl = "https://hmvvjjiiaelcsfqgxbxv.supabase.co";
$apiKey = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImhtdnZqamlpYWVsY3NmcWd4Ynh2Iiwicm9sZSI6ImFub24iLCJpYXQiOjE3NzQzNDg4MzcsImV4cCI6MjA4OTkyNDgzN30.zCpflfgSmBwpwe62P7cr1Ppf5dMUMjh782EhZeZ-kuw"; 

// 2. Cấu hình bảng dữ liệu
$tableName = "hunglouis";
$params = "select=id,name,image_url,hunglouis_id,price&order=id.asc&limit=50";

// 3. Xây dựng URL hoàn chỉnh
$fullUrl = $supabaseUrl . "/rest/v1/hunglouis?select=*&order=id.asc";

// 4. Thực thi lấy dữ liệu
$ch = curl_init($fullUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "apikey: " . $apiKey,
    "Authorization: Bearer " . $apiKey,
    "Content-Type: application/json"
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

if (curl_errno($ch)) {
    echo json_encode(["error" => curl_error($ch)]);
} else {
    // Nếu Supabase trả về lỗi (ví dụ 401, 404), ta sẽ biết ngay
    if ($httpCode !== 200) {
        echo json_encode(["error" => "Supabase returned code $httpCode", "details" => json_decode($response)]);
    } else {
        echo $response; // ĐÂY LÀ DỮ LIỆU NFT CỦA BẠN
    }
}

curl_close($ch);
?>
