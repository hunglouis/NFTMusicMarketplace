<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
require_once "filter_helper.php"; // 1. CHÈN THÊM VÀO ĐẦU FILE

// 🔑 API KEY
$apiKey = "xaC_d2_cBXfF74xndB750"; // thay bằng key thật

// 👇 ví của bạn
$owner = "0x8429BC345266D03a433b25B8Fb6301274294D81E";

// 🛑 DANH SÁCH TOKEN ID MUỐN ẨN (Mô phỏng OpenSea)
// Bạn muốn ẩn ID nào thì điền số vào mảng này, ví dụ muốn ẩn ID số 3 và số 12:
$hiddenTokenIds = [3, 12];

// URL Alchemy
$url = "https://polygon-mainnet.g.alchemy.com/nft/v2/$apiKey/getNFTs?owner=$owner";

$pageKey = $_GET['pageKey'] ?? '';
if ($pageKey) {
    $url .= "&pageKey=" . urlencode($pageKey);
}

// Lấy dữ liệu từ Alchemy
$response = file_get_contents($url);

if ($response) {
    // Giải mã chuỗi JSON thành mảng PHP để xử lý
    $data = json_decode($response, true);

    // Kiểm tra nếu có danh sách NFTs trả về
    if (isset($data['ownedNfts']) && is_array($data['ownedNfts'])) {
        $filteredNfts = [];

        foreach ($data['ownedNfts'] as $nft) {
            // Lấy Token ID của NFT hiện tại (chuyển về dạng số thập phân)
            $tokenId = isset($nft['id']['tokenId']) ? hexdec($nft['id']['tokenId']) : null;

            // Nếu Token ID nằm trong danh sách ẩn, bỏ qua không đưa vào mảng hiển thị
            if ($tokenId !== null && in_array($tokenId, $hiddenTokenIds)) {
                continue;
            }

            // Nếu không bị ẩn, giữ lại NFT này
            $filteredNfts[] = $nft;
        }

        // Cập nhật lại danh sách NFTs sau khi đã lọc sạch
        $data['ownedNfts'] = $filteredNfts;
    }

    // In dữ liệu đã lọc sạch ra dưới dạng JSON cho Frontend nhận
    echo json_encode($data);
} else {
    echo json_encode(["error" => "Không thể lấy dữ liệu từ Alchemy"]);
}
