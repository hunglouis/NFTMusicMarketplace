<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['token_id']) || !isset($data['new_owner'])) {
    echo json_encode(['status' => 'error', 'message' => 'Thiếu tham số truyền vào']);
    exit();
}

$tokenId = $data['token_id'];
$newOwner = $data['new_owner'];

// Cấu hình Supabase của bạn
$supabaseUrl = "https://hmvvjjiiaelcsfqgxbxv.supabase.co"; // Thay URL thật của bạn
$supabaseAnonKey = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImhtdnZqamlpYWVsY3NmcWd4Ynh2Iiwicm9sZSI6ImFub24iLCJpYXQiOjE3NzQzNDg4MzcsImV4cCI6MjA4OTkyNDgzN30.zCpflfgSmBwpwe62P7cr1Ppf5dMUMjh782EhZeZ-kuw"; // Thay Key thật của bạn
$tableName = "items";

// Thực hiện lệnh PATCH (Update) lên Supabase để đổi owner_address
$apiUrl = $supabaseUrl . "/rest/v1/" . $tableName . "?token_id=eq." . urlencode($tokenId);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $apiUrl);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
    'owner_address' => $newOwner // Gán chủ mới
]));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "apikey: " . $supabaseAnonKey,
    "Authorization: Bearer " . $supabaseAnonKey,
    "Content-Type: application/json",
    "Prefer: return=representation"
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode >= 200 && $httpCode < 300) {
    echo json_encode(['status' => 'success', 'message' => 'Cập nhật database thành công']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Lỗi kết nối Supabase, mã lỗi: ' . $httpCode]);
}
?>
