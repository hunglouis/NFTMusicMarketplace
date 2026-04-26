<?php
header('Content-Type: application/json');

// 1. Nhận dữ liệu từ Xưởng chế tác gửi sang
$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (!$data) {
    echo json_encode(['error' => 'Không nhận được dữ liệu']);
    exit;
}

// 2. Cấu hình Supabase của bạn
$supabaseUrl = "https://hmvvjjiiaelcsfqgxbxv.supabase.co";
$apiKey = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImhtdnZqamlpYWVsY3NmcWd4Ynh2Iiwicm9sZSI6ImFub24iLCJpYXQiOjE3NzQzNDg4MzcsImV4cCI6MjA4OTkyNDgzN30.zCpflfgSmBwpwe62P7cr1Ppf5dMUMjh782EhZeZ-kuw"; // <--- QUAN TRỌNG: Dán Key của bạn vào đây

$url = $supabaseUrl . "/rest/v1/hunglouis";

// 3. Chuẩn bị dữ liệu để "chép" vào bảng
$payload = json_encode([
    'name'        => $data['name'],
    'price'       => $data['price'],
    'image_url'   => $data['image_url'],
    'description' => $data['description'],
    'created_at'  => date('Y-m-d H:i:s')
]);

// 4. Thực hiện lệnh gửi (POST) lên Supabase
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "apikey: " . $apiKey,
    "Authorization: Bearer " . $apiKey,
    "Content-Type: application/json",
    "Prefer: return=representation"
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// 5. Trả kết quả về cho Xưởng chế tác
if ($httpCode == 201 || $httpCode == 200) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['error' => 'Lỗi lưu dữ liệu: ' . $response]);
}
?>
