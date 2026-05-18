<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
require_once "filter_helper.php"; // 1. CHÈN THÊM VÀO ĐẦU FILE

// 1. Cấu hình chìa khóa
$opensea_api_key = "b736ad1e23c74136b98079b71923bfcb"; 
$supabase_url = "https://hmvvjjiiaelcsfqgxbxv.supabase.co";
$supabase_key = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImhtdnZqamlpYWVsY3NmcWd4Ynh2Iiwicm9sZSI6ImFub24iLCJpYXQiOjE3NzQzNDg4MzcsImV4cCI6MjA4OTkyNDgzN30.zCpflfgSmBwpwe62P7cr1Ppf5dMUMjh782EhZeZ-kuw";

// 🛑 DANH SÁCH TOKEN ID MUỐN ẨN (Mô phỏng OpenSea)
// Bạn điền các Token ID bản nhạc muốn giấu vào đây (Ví dụ muốn ẩn ID số 15 và số 10)
$hiddenTokenIds =; 

$contracts = [
    "0x718a7d4153a93331524e00d04a9bb6acffec4d50",
    "0xddcd6cf61e81c6e22a082841a388a19984ff4745",
    "0x0b7331056821b49d6a36ed2ec3666de1d482f8a6",
    "0x8c88f200ea6c9a3b812424e13baab5b0a49657c3",
    "0x83bf87ce3eb9b3ddd552fe7dff9d63acaec01b7d"
];

$wallet = "0x8429BC345266D03a433b25B8Fb6301274294D81E";
$url = "https://api.opensea.io/api/v2/chain/polygon/account/$wallet/nfts";

$options = [
    "http" => [
        "method" => "GET",
        "header" => [
            "Accept: application/json",
            "X-API-KEY: $opensea_api_key"
        ]
    ]
];

$context = stream_context_create($options);
$response = file_get_contents($url, false, $context);

if ($response) {
    $data_json = json_decode($response, true);

    if (isset($data_json['nfts']) && is_array($data_json['nfts'])) {
        $filteredNfts = [];

        foreach ($data_json['nfts'] as $nft) {
            // Lấy Token ID từ OpenSea (OpenSea lưu trường này tên là 'identifier')
            $tokenId = (int)$nft['identifier'];

            // Nếu Token ID nằm trong danh sách ẩn, ta vẫn cho phép đồng bộ lên Supabase 
            // nhưng sẽ ĐÁNH DẤU trạng thái ẩn (hoặc bỏ qua không in ra Frontend)
            if (in_array($tokenId, $hiddenTokenIds)) {
                // Bạn có thể xử lý lưu trạng thái ẩn lên DB tại đây nếu bảng có cột is_hidden
                continue; // Chặn không cho hiện ra Frontend
            }

            // Nếu không bị ẩn, đưa vào mảng hiển thị công khai
            $filteredNfts[] = $nft;

            // --- GIỮ NGUYÊN ĐOẠN CODE ĐẨY DỮ LIỆU LÊN SUPABASE CỦA BẠN ---
            $item_data = [
                "contract_address" => $nft['contract'],
                "item_id"          => $nft['identifier'],
                "name"             => $nft['name'],
                "description"      => $nft['description'],
                "image_url"        => $nft['image_url'],
                "metadata_url"     => $nft['metadata_url'],
                "owner_address"    => "0x8429BC345266D03a433b25B8Fb6301274294D81E",
                "price"            => 0.1
            ];

            $ch = curl_init("$supabase_url/rest/v1/collections");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($item_data));
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                "apikey: $supabase_key",
                "Authorization: Bearer $supabase_key",
                "Content-Type: application/json",
                "Prefer: resolution=merge-duplicates"
            ]);

            $res = curl_exec($ch);
            curl_close($ch);
            // -----------------------------------------------------------
        }

        // Gán lại danh sách đã lọc sạch vào cấu trúc JSON ban đầu
        $data_json['nfts'] = $filteredNfts;
    }

    // ĐƯA LỆNH ECHO JSON XUỐNG DƯỚI CÙNG: Xuất dữ liệu đã lọc sạch ra Frontend
    echo json_encode($data_json);
} else {
    echo json_encode(["error" => "Không thể lấy dữ liệu từ OpenSea"]);
}
?>
