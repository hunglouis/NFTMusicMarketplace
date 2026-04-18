<?php
// Thông tin "chìa khóa" của anh
$supabaseUrl = "https://hmvvjjiiaelcsfqgxbxv.supabase.co";
$supabaseKey = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImhtdnZqamlpYWVsY3NmcWd4Ynh2Iiwicm9sZSI6ImFub24iLCJpYXQiOjE3NzQzNDg4MzcsImV4cCI6MjA4OTkyNDgzN30.zCpflfgSmBwpwe62P7cr1Ppf5dMUMjh782EhZeZ-kuw";
// URL dành cho hình ảnh và âm nhạc (Dùng chung cho toàn hệ thống)
$storageUrl = "https://hmvvjjiiaelcsfqgxbxv.supabase.co/storage/v1/object/public/music/";
 $url = "https://hmvvjjiiaelcsfqgxbxv.supabase.co/rest/v1/" ;

// $path: Tên bảng (vd: 'hunglouis')
// $method: GET (lấy dữ liệu), POST (thêm mới), PATCH (sửa)
// $data: Mảng dữ liệu gửi đi (nếu có)
if (!function_exists('callSupabase')) {
    function callSupabase($path, $method = 'GET', $data = null) {
        global $supabaseUrl, $supabaseKey;
        $url = $supabaseUrl . "/rest/v1/" ;
           
             
        $ch = curl_init($url);
        $headers = [
            "apikey: $supabaseKey",
            "Authorization: Bearer $supabaseKey",
            "Content-Type: application/json",
            "Prefer: return=representation"
        ];
        
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);           
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Quan trọng để chạy trên Render

        if ($method == 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        } elseif ($method == 'PATCH') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }

        $response = curl_exec($ch);
        curl_close($ch);
        return json_decode($response, true);
    }
} 

?>

