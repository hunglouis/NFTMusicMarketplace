<?php
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['token_id']) || !isset($data['price'])) {
    echo json_encode(['status' => 'error', 'message' => 'Thiếu tham số']);
    exit();
}

$tokenId = $data['token_id'];
$price = $data['price'];
$isListed = $data['is_listed'];

// Cấu hình Supabase của bạn
$supabaseUrl = "https://hmvvjjiiaelcsfqgxbxv.supabase.co"; // Thay URL thật
$supabaseAnonKey = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImhtdnZqamlpYWVsY3NmcWd4Ynh2Iiwicm9sZSI6ImFub24iLCJpYXQiOjE3NzQzNDg4MzcsImV4cCI6MjA4OTkyNDgzN30.zCpflfgSmBwpwe62P7cr1Ppf5dMUMjh782EhZeZ-kuw"; // Thay Key thật
$tableName = "items";

$apiUrl = $supabaseUrl . "/rest/v1/" . $tableName . "?token_id=eq." . urlencode($tokenId);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $apiUrl);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
    'price' => $price,
    'is_listed' => $isListed
]));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "apikey: " . $supabaseAnonKey,
    "Authorization: Bearer " . $supabaseAnonKey,
    "Content-Type: application/json"
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode >= 200 && $httpCode < 300) {
    include 'log_activity.php';
    $event = $isListed ? "List" : "Cancel";
    saveActivity($tokenId, "Bản nhạc NFT", $event, $_SESSION['user_wallet'], null, $price);
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Lỗi kết nối database']);
}
?>
