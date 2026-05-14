<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Bật thông báo lỗi để dễ theo dõi nếu hệ thống bị nghẽn
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Nhận dữ liệu chuỗi JSON gửi từ JavaScript lên
$json_input = file_get_contents('php://input');
$data = json_decode($json_input, true);

header('Content-Type: application/json');

if (isset($data['wallet']) && !empty($data['wallet'])) {
    // Làm sạch chuỗi ví và ép về chữ thường hoàn toàn
    $new_wallet = strtolower(trim($data['wallet']));
    
    // GHI ĐÈ VÍ MỚI VÀO SESSION PHP
    $_SESSION['user_wallet'] = $new_wallet;
    
    echo json_encode([
        'status' => 'success', 
        'wallet' => $_SESSION['user_wallet'],
        'message' => 'Session đã được cập nhật thành công ví mới'
    ]);
} else {
    echo json_encode([
        'status' => 'error', 
        'message' => 'Dữ liệu ví gửi lên hệ thống không hợp lệ hoặc bị trống'
    ]);
}
exit();
?>
