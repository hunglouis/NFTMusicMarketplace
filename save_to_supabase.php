<?php
header('Content-Type: application/json');
$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (!$data) {
    echo json_encode(['error' => 'Không có dữ liệu']); exit;
}

// 1. Cấu hình
$apiKey = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImhtdnZqamlpYWVsY3NmcWd4Ynh2Iiwicm9sZSI6ImFub24iLCJpYXQiOjE3NzQzNDg4MzcsImV4cCI6MjA4OTkyNDgzN30.zCpflfgSmBwpwe62P7cr1Ppf5dMUMjh782EhZeZ-kuw"; 
$url = "https://hmvvjjiiaelcsfqgxbxv.supabase.co";

// 2. Chuẩn bị dữ liệu khớp với cấu trúc bảng public.hunglouis
$payload = json_encode([
    'name'           => $data['name'],
    'price'          => (float)$data['price'], // Ép về kiểu số thực
    'image_url'      => $data['image_url'],
    'description'    => $data['description'],
    'artist'         => 'Nghệ sĩ Mạnh Hùng',
    'status'         => 'active',
    'is_for_sale'    => true,
    // Tạo ID giả để tránh lỗi Unique Constraint cho đến khi bạn đúc thật trên ví
    'contract_address' => '0x' . bin2hex(random_bytes(20)), 
    'token_id'         => (string)rand(1000, 9999)
]);

// 3. Gửi dữ liệu
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "apikey: $apiKey",
    "Authorization: Bearer $apiKey",
    "Content-Type: application/json",
    "Prefer: return=representation"
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode == 201 || $httpCode == 200) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => $response]);
}
?>
