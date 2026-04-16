<?php
// Thông tin "chìa khóa" của anh
$supabaseUrl = "https://hmvvjjiiaelcsfqgxbxv.supabase.co";
$supabaseKey = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImhtdnZqamlpYWVsY3NmcWd4Ynh2Iiwicm9sZSI6ImFub24iLCJpYXQiOjE3NzQzNDg4MzcsImV4cCI6MjA4OTkyNDgzN30.zCpflfgSmBwpwe62P7cr1Ppf5dMUMjh782EhZeZ-kuw";



 * $path: Tên bảng (vd: 'music_collection')
 * $method: GET (lấy dữ liệu), POST (thêm mới), PATCH (sửa)
 * $data: Mảng dữ liệu gửi đi (nếu có)
 */
function callSupabase($path, $method = 'GET', $data = null) {
    global $supabaseUrl, $supabaseKey;
    
    // 1. Phải tạo URL trước khi dùng
    $url = $supabaseUrl . "/rest/v1/" . $path;
    $ch = curl_init($url);
    
    $headers = [
        "apikey: $supabaseKey",
        "Authorization: Bearer $supabaseKey",
        "Content-Type: application/json",
        "Prefer: return=representation"
    ];
    
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    // 2. Xử lý các phương thức POST/PATCH
    if ($method == 'POST') {
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    }

    // 3. THỰC THI (Lệnh này phải nằm TRONG hàm)
    $response = curl_exec($ch);
    
    // Kiểm tra lỗi nếu curl thất bại
    if(curl_errno($ch)) {
        return "CURL Error: " . curl_error($ch);
    }
    
    curl_close($ch);

    // 4. TRẢ VỀ KẾT QUẢ
    return json_decode($response, true);
} // Dấu đóng ngoặc hàm phải nằm ở ĐÂY

?>
