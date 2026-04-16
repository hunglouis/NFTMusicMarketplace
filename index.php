<?php
session_start();
// Bật chế độ "Soi lỗi" mức cao nhất
ini_set('display_errors', 1);
error_reporting(E_ALL);
// Gọi file chứa hàm kết nối (Chỉ chọn 1 trong 2 file)
require 'db.php'; 
echo "<h1>🛠 Đang kiểm tra hệ thống trên Localhost...</h1>";
// Kiểm tra thử kết nối Supabase
$check = callSupabase("music_collection?select=*");
echo "<h2>Đang kết nối hệ thống nhạc Mạnh Hùng...</h2>";
if (isset($check['error']) || isset($check['code'])) {
    echo "<h3 style='color:red;'>❌ LỖI KẾT NỐI SUPABASE:</h3>";
    echo "<pre>"; print_r($check); echo "</pre>";
    echo "<p>=> Anh hãy kiểm tra lại URL và API Key trong file db.php nhé.</p>";
} else {
    echo "<h3 style='color:lime;'>✅ KẾT NỐI THÀNH CÔNG!</h3>";
    echo "Dữ liệu tìm thấy: " . $check[0]['title'];
}
?>
