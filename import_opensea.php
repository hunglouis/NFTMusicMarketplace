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

// --- KIỂM TRA TÌNH HÌNH ---
echo "📡 Trạng thái kết nối OpenSea: " . ($response ? "ĐÃ KẾT NỐI ✅" : "THẤT BẠI ❌") . "<br>";
if ($data_json && isset($data_json['nfts'])) {
    echo "📦 Tìm thấy: " . count($data_json['nfts']) . " vật phẩm di sản.<br>";
}
// --- BẮT ĐẦU ĐẨY 26 MÓN VÀO SUPABASE ---
if (isset($data_json['nfts']) && is_array($data_json['nfts'])) {
    foreach ($data_json['nfts'] as $nft) {
        
        // 1. Chuẩn bị dữ liệu từng món (Lấy đúng từ OpenSea)
        $itemData = [
            "contract_address" => $nft['contract'],
            "item_id"          => $nft['identifier'],
            "name"             => $nft['name'] ?? "Di sản #" . $nft['identifier'],
            "image_url"        => $nft['image_url'],
            "owner_address"    => $wallet, // Lấy từ biến ví của nghệ sĩ bên trên
            "price"            => 0.1
        ];

        // 2. Cấu hình gửi lên Supabase
        // NGHỆ SĨ LƯU Ý: Nếu đầu file bạn đặt tên là $supabase_url thì sửa chữ Url thành _url nhé!
        $url_post = $supabase_url . "/rest/v1/items"; 
        
        $options_post = [
            "http" => [
                "method" => "POST",
                "header" => [
                    "apikey: " . $supabase_key,
                    "Authorization: Bearer " . $supabase_key,
                    "Content-Type: application/json",
                    "Prefer: resolution=merge-duplicates" // Trùng ID thì cập nhật, không báo lỗi
                ],
                "content" => json_encode($itemData)
            ]
        ];

        $context_post  = stream_context_create($options_post);
        $result_post = @file_get_contents($url_post, false, $context_post);

        // 3. Báo cáo kết quả ra màn hình
        if ($result_post !== false) {
            echo "✅ Đã đưa vào kho Supabase: " . $itemData['name'] . "<br>";
        } else {
            echo "❌ Thất bại món: " . $itemData['name'] . " (Có thể sai URL hoặc Key)<br>";
        }
    }
}

// --- ĐOẠN NÀY DÁN VÀO SAU KHI BẠN ĐÃ LẤY ĐƯỢC $response TỪ OPENSEA ---

// Thắp đèn soi dữ liệu
echo "Dữ liệu OpenSea trả về: " . ($url ? "Có" : "Trống rỗng") . "<br>";
echo "Số lượng NFT tìm thấy: " . (isset($data_json['nfts']) ? count($data_json['nfts']) : 0) . "<br>";

if (isset($data_json['nfts']) && is_array($data_json['nfts'])) {
    foreach ($data_json['nfts'] as $nft) {
        
        // 1. Chuẩn bị dữ liệu
        $itemData = [
            "contract_address" => $nft['contract'],
            "item_id"          => $nft['identifier'],
            "name"             => $nft['name'],
            "image_url"        => $nft['image_url'],
            "metadata_url"     => $nft['metadata_url']
        ];

        // 2. Gửi lên Supabase bằng hàm http_post_json của bạn
        $url = $supabase_url . "/rest/v1/items";
        $headers = [
            "apikey: $supabase_key",
            "Authorization: Bearer $supabase_key",
            "Content-Type: application/json",
            "Prefer: resolution=merge-duplicates"
        ];

        try {
            $result = http_post_json($url, $itemData, $headers);
            echo "✅ Đã lên kệ: " . $nft['name'] . "<br>";
        } catch (Exception $e) {
            echo "❌ Lỗi: " . $e->getMessage() . "<br>";
        }

    } // Đóng ngoặc của foreach (Dòng này cực kỳ quan trọng!)
} // Đóng ngoặc của if (Dòng này cũng cực kỳ quan trọng!)

// KIỂM TRA CUỐI FILE: Xóa sạch mọi dấu } lẻ loi phía dưới dòng này
$context = stream_context_create($options);
// --- 1. GIẢI MÃ DỮ LIỆU ---
// --- 2. BẮT ĐẦU VÒNG LẶP ĐỂ ĐẨY LÊN SUPABASE ---
if (isset($data_json['nfts']) && is_array($data_json['nfts'])) {
    foreach ($data_json['nfts'] as $nft) {
        
        // Chuẩn bị dữ liệu cho mỗi Item
        $contractAddress = $nft['contract'];
        $item_id = $nft['identifier'];
        $name = $nft['name'];
        $image_url = $nft['image_url'];

        echo "🚀 Đang xử lý: " . $name . " (ID: " . $item_id . ")<br>";
        
        // Chỗ này sẽ là đoạn code cURL gửi lên Supabase của bạn...
    }
}
     // Code lưu vào Supabase của bạn ở đây...
if (isset($data_json['nfts'])) {
    foreach ($data_json['nfts'] as $nft) {
        // ... code xử lý của bạn ...
    // --- ĐÂY LÀ 2 DÒNG BẠN CẦN GHI VÀO ĐỂ HẾT LỖI ---
    $contractAddress = $nft['contract']; // Lấy địa chỉ hợp đồng từ dữ liệu OpenSea
    $endpoint = $supabase_url . "/rest/v1/items"; // Tạo đường dẫn gửi đến Supabase
    // ------------------------------------------------
            // Chuẩn hóa trường từ OpenSea
            // identifier/tokenId/nft identifier có thể khác key
            $itemId =
                $nft['identifier'] ??
                $nft['token_id'] ??
                $nft['tokenId'] ??
                ($nft['token'] ?? null);

            $name =
                $nft['name'] ??
                ($nft['collection'] ?? null);

            // image/metadata
            $imageUrl =
                $nft['image_url'] ??
                $nft['imageUrl'] ??
                ($nft['metadata'] ?? null);

            $metadataUrl =
                $nft['metadata_url'] ??
                $nft['metadataUrl'] ??
                null;

            // Nếu OpenSea trả collection name riêng
            $collectionName =
                $nft['items']['name'] ??
                $nft['items_name'] ??
                $name;

            $data = [
                // ====== các cột KHỚP với bảng items của bạn ======
                "contract_address" => $contractAddress,
                "collection_name"  => $collectionName,

                // cột bạn dùng trước đó (trước mình thấy “item”)
                "item" => (string)$itemId,

                "image_url"    => $imageUrl,
                "metadata_url" => $metadataUrl,

                // nếu bảng yêu cầu NOT NULL
                "creator_address" => $creator_address_default
            ];

            // Upsert vào Supabase REST
            $postUrl = $supabase_url . "/rest/v1/" . $endpoint;
            ;

            $postHeaders = [
                "apikey: $supabase_key",
                "Authorization: Bearer $supabase_key",
                "Content-Type: application/json",
                // merge-duplicates giúp upsert theo unique/index bạn đã đặt trong DB
                "Prefer: resolution=merge-duplicates"
            ];
            
            // echo trạng thái
            echo "✅ Upsert: contract=" . $contractAddress .
                 " item=" . $itemId .
                 " name=" . ($name ?? "") . "<br>";

                  // PHẢI CÓ DÒNG NÀY THÌ MÀN HÌNH MỚI HẾT TRẮNG
        echo "💎 Đang xử lý: " . $nft['name'] . " - ID: " . $nft['identifier'] . "<br>";
    }
} else {
    // Nếu không thấy gì thì báo ở đây
    echo "⚠️ Chẳng thấy món hàng nào từ OpenSea gửi về cả, nghệ sĩ ơi!";
    print_r($data_json); // Hiện thử đống dữ liệu thô để soi lỗi
}
   
?>