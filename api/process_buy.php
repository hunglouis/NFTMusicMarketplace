<?php
// api/process_buy.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$json_input = file_get_contents('php://input');
$data = json_decode($json_input, true);

header('Content-Type: application/json');

if (!isset($data['token_id']) || !isset($data['buyer_address'])) {
    echo json_encode(['status' => 'error', 'message' => 'Thiếu tham số giao dịch']);
    exit();
}

$tokenId = $data['token_id'];
$buyerAddress = strtolower(trim($data['buyer_address']));

// 1. CẤU HÌNH KẾT NỐI SUPABASE THẬT CỦA BẠN (Lấy từ file profile.php sang)
$supabaseUrl = "supabase.co"; 
$supabaseAnonKey = "your-anon-key"; 
$tableName = "items";

$apiUrl = $supabaseUrl . "/rest/v1/" . $tableName . "?token_id=eq." . urlencode($tokenId);

// 2. THỰC HIỆN LỆNH PATCH ĐỂ CẬP NHẬT CHỦ SỞ HỮU MỚI VÀ GỠ BỎ TRẠNG THÁI BÁN
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $apiUrl);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
    'owner_address' => $buyerAddress, // Người mua trở thành chủ sở hữu mới
    'price'         => 0,             // Đưa giá về 0 (Ngừng treo giá)
    'is_listed'     => false          // Gỡ trạng thái đang bán xuống khỏi chợ
]));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "apikey: " . $supabaseAnonKey,
    "Authorization: Bearer " . $supabaseAnonKey,
    "Content-Type: application/json"
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// 3. GHI NHẬN LỊCH SỬ GIAO DỊCH VÀO BẢNG ACTIVITIES NẾU THÀNH CÔNG
if ($httpCode >= 200 && $httpCode < 300) {
    include 'log_activity.php';
    if (function_exists('saveActivity')) {
        // Ghi lại sự kiện giao dịch thành công (Sale)
        saveActivity($tokenId, "Bản nhạc NFT", "Sale", $_SESSION['user_wallet'], $buyerAddress, $data['price'] ?? 0);
    }
    echo json_encode(['status' => 'success', 'message' => 'Giao dịch đổi chủ sở hữu thành công!']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Lỗi cập nhật database, mã lỗi: ' . $httpCode]);
}
exit();
?>
