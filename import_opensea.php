<?php

// =====================
// 1) Cấu hình
// =====================
$opensea_api_key = "b736ad1e23c74136b98079b71923bfcb"; // thay bằng API key OpenSea của bạn
$supabase_url = "https://hmvvjjiiaelcsfqgxbxv.supabase.co";
$supabase_key = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImhtdnZqamlpYWVsY3NmcWd4Ynh2Iiwicm9sZSI6ImFub24iLCJpYXQiOjE3NzQzNDg4MzcsImV4cCI6MjA4OTkyNDgzN30.zCpflfgSmBwpwe62P7cr1Ppf5dMUMjh782EhZeZ-kuw"; // nên dùng service_role nếu bạn muốn insert mạnh

// Danh sách contract Polygon
$contracts = [
    "0x718a7d4153a93331524e00d04a9bb6acffec4d50",
    "0xddcd6cf61e81c6e22a082841a388a19984ff4745",
    "0x0b7331056821b49d6a36ed2ec3666de1d482f8a6",
    "0x8c88f200ea6c9a3b812424e13baab5b0a49657c3",
    "0x83bf87ce3eb9b3ddd552fe7dff9d63acaec01b7d"
];

// Tên bảng bạn muốn upsert
$table = "items";

// Nếu cột creator_address của bạn NOT NULL:
$creator_address_default = "0x8429BC345266D03a433b25B8Fb6301274294D81E";

// =====================
// 2) Helper gọi HTTP
// =====================
function http_get_json($url, $headers = []) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    if (!empty($headers)) curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $raw = curl_exec($ch);
    $err = curl_error($ch);
    curl_close($ch);

    if ($raw === false) {
        throw new Exception("HTTP GET failed: " . $err);
    }

    $data_json = json_decode($raw, true);
    if (!is_array($data_json)) {
        throw new Exception("Invalid JSON from: " . $url);
    }
    return $data_json;
}

function http_post_json($url, $body, $headers = []) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));

    if (!empty($headers)) curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $raw = curl_exec($ch);
    $err = curl_error($ch);
    curl_close($ch);

    if ($raw === false) {
        throw new Exception("HTTP POST failed: " . $err);
    }
    $data_json = json_decode($raw, true);
    return $data_json;
}

// =====================
// 3) Sync từng contract
// =====================

$wallet = "0x8429BC345266D03a433b25B8Fb6301274294D81E";

$url = "https://api.opensea.io/api/v2/chain/polygon/account/$wallet/nfts";

$options = [
    "http" => [
        "method" => "GET",
        "header" => [
            "Accept: application/json",
            "X-API-KEY:b736ad1e23c74136b98079b71923bfcb"
        ]
    ]
];
// 1. TẠO NGỮ CẢNH ĐỂ GỌI API
$context = stream_context_create($options);

// 2. THỰC SỰ ĐI LẤY DỮ LIỆU TỪ OPENSEA (Lệnh này cực kỳ quan trọng)
$response = file_get_contents($url, false, $context);

// 3. GIẢI MÃ DỮ LIỆU (Dùng đúng tên biến $response ở trên)
$data_json = json_decode($response, true);

// --- CHỈ GIỮ LẠI DUY NHẤT ĐOẠN NÀY ĐỂ ĐẨY HÀNG ---
if (isset($data_json['nfts']) && is_array($data_json['nfts'])) {
    foreach ($data_json['nfts'] as $nft) {
    
    $itemData = [
        "contract_address" => $nft['contract'],   // Sửa thành 'contract'
        "item_id"          => $nft['identifier'],
        "name"             => $nft['name'] ?? "Di sản #" . $nft['identifier'],
        "image_url"        => $nft['image_url'],
        "creator_address"  => $wallet,           // Phải có cái này nữa
        "owner_address"    => $wallet,
        "price"            => 0.1
    ];

        // Dùng cURL - Cách này mạnh mẽ và báo lỗi chính xác nhất
        $ch = curl_init($supabase_url . "/rest/v1/items");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($itemData));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "apikey: $supabase_key",
            "Authorization: Bearer $supabase_key",
            "Content-Type: application/json",
            "Prefer: resolution=merge-duplicates"
        ]);

        $res_post = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($status == 201 || $status == 200) {
            echo "<span style='color: #00ffff;'>✅ ĐÃ VÀO KHO: " . $itemData['name'] . "</span><br>";
        } else {
            // Nếu lỗi, nó sẽ hiện nguyên nhân tại đây
            echo "<span style='color: #ff4444;'>❌ LỖI THỰC SỰ: " . $res_post . " (Mã: $status)</span><br>";
        }
    }
}
?>
