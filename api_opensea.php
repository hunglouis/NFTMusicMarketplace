<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
// 1. Cấu hình chìa khóa
$opensea_api_key = "b736ad1e23c74136b98079b71923bfcb"; // Dán API Key của bạn vào đây
$supabase_url = "https://hmvvjjiiaelcsfqgxbxv.supabase.co";
$supabase_key = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImhtdnZqamlpYWVsY3NmcWd4Ynh2Iiwicm9sZSI6ImFub24iLCJpYXQiOjE3NzQzNDg4MzcsImV4cCI6MjA4OTkyNDgzN30.zCpflfgSmBwpwe62P7cr1Ppf5dMUMjh782EhZeZ-kuw";

// Danh sách các hợp đồng bạn cung cấp
$contracts = [
    "0x718a7d4153a93331524e00d04a9bb6acffec4d50",
"0xddcd6cf61e81c6e22a082841a388a19984ff4745",
"0x0b7331056821b49d6a36ed2ec3666de1d482f8a6",
"0x8c88f200ea6c9a3b812424e13baab5b0a49657c3",
"0x83bf87ce3eb9b3ddd552fe7dff9d63acaec01b7d"
];
// 🔑 API KEY
$apiKey = "b736ad1e23c74136b98079b71923bfcb"; // thay bằng key thật

$wallet = "0x8429BC345266D03a433b25B8Fb6301274294D81E";

$url = "https://api.opensea.io/api/v2/chain/polygon/account/$wallet/nfts";
$options = [
    "http" => [
        "method" => "GET",
        "header" => [
            "Accept: application/json",
            "X-API-KEY:$opensea_api_key"
        ]
    ]
];

$context = stream_context_create($options);
$response = file_get_contents($url, false, $context);

echo $response;
// Giả sử $response là biến chứa dữ liệu JSON bạn đang thấy trong ảnh
$data_json = json_decode($response, true);

if (isset($data_json['nfts'])) {
    foreach ($data_json['nfts'] as $nft) {
        
        // Chuẩn bị dữ liệu để đẩy lên Supabase
        $item_data = [
            "contract_address" => $nft['contract'],
            "item_id"          => $nft['identifier'], // Token ID (15, 10,...)
            "name"             => $nft['name'],
            "description"      => $nft['description'],
            "image_url"        => $nft['image_url'],
            "metadata_url"     => $nft['metadata_url'],
            "owner_address"    => "0x8429BC345266D03a433b25B8Fb6301274294D81E", // Ví chủ sở hữu
            "price"            => 0.1 // Bạn có thể để mặc định hoặc lấy từ API khác
        ];

        // Lệnh đẩy lên Supabase dùng cURL
        $ch = curl_init("$supabase_url/rest/v1/collections");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($item_data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "apikey: $supabase_key",
            "Authorization: Bearer $supabase_key",
            "Content-Type: application/json",
            "Prefer: resolution=merge-duplicates" // Nếu trùng ID thì sẽ cập nhật lại (không lỗi)
        ]);

        $res = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($status == 201 || $status == 200) {
            echo "✅ Đã đẩy lên kệ: " . $nft['name'] . " (ID: " . $nft['identifier'] . ")<br>";
        } else {
            echo "❌ Lỗi ID " . $nft['identifier'] . ": " . $res . "<br>";
        }
    }
} else {
    echo "Không tìm thấy danh sách NFT nào.";
}
?>
