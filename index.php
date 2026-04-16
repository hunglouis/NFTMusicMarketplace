<?php
session_start(); // Phải nằm ở dòng đầu tiên
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Gọi file chứa hàm kết nối (Chỉ chọn 1 trong 2 file)
require 'db.php'; 

echo "<h2>Đang kết nối hệ thống nhạc Mạnh Hùng...</h2>";

// Thử lấy dữ liệu từ bảng (Anh nhớ kiểm tra tên bảng trên Supabase nhé)
$songs = callSupabase("music_collection?select=*");

if (isset($songs['code'])) {
    echo "<p style='color:red;'>⚠️ Lỗi: " . $songs['message'] . "</p>";
    echo "<p>Gợi ý: Anh hãy vào Supabase tạo bảng tên là 'music_collection' nhé.</p>";
} else {
    echo "<p style='color:green;'>✅ Thành công! Tìm thấy " . count($songs) . " tác phẩm.</p>";
}
?>
