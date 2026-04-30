<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "<body style='background:#0d1117; color:white; font-family:sans-serif; padding:20px;'>";
echo "<h1>🚀 ĐANG KHỞI ĐỘNG TIẾN TRÌNH...</h1>";

require 'supabase_config.php'; 

$openseaKey  = "9def0462b8a642d8a2b3f139bc794322";
$wallet = "0x8429BC345266D03a433b25B8Fb6301274294D81E";

// DÙNG ENDPOINT ACCOUNT (VÍ) - THƯỜNG ỔN ĐỊNH HƠN
$url = "https://opensea.io";

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
// GIẢ LẬP TRÌNH DUYỆT THẬT SỰ
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36');

curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "X-API-KEY: $openseaKey",
    "Accept: application/json", // ÉP PHẢI TRẢ VỀ JSON
    "Referer: https://opensea.io"
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "Kết nối OpenSea: Mã $httpCode <br><hr>";

$data = json_decode($response, true);

if (isset($data['nfts']) && is_array($data['nfts'])) {
    echo "Đã tìm thấy " . count($data['nfts']) . " bài hát. Đang nạp vào Supabase...<br><br>";
    
    foreach ($data['nfts'] as $nft) {
        $name = $nft['name'] ?? ("Tác phẩm #" . $nft['identifier']);
        
        // Đẩy lên mây Supabase
        callSupabase("music_collection", "POST", [
            'title'      => $name,
            'image_url'  => $nft['image_url'] ?? '',
            'identifier' => $nft['identifier'],
            'price'      => 500
        ]);
        
        echo "✅ Đã nạp: <b>" . $name . "</b><br>";
    }
    echo "<h2 style='color:lime;'>🔥 THÀNH CÔNG RỰC RỠ!</h2>";
    echo "<a href='dashboard.php' style='color:cyan;'>=> XEM DASHBOARD</a>";
} else {
    echo "<p style='color:orange;'>⚠️ Vẫn nhận được HTML. OpenSea đang yêu cầu xác minh người dùng.</p>";
    echo "Phản hồi đầu trang: " . htmlspecialchars(substr($response, 0, 200));
}
?>
