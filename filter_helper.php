<?php
// filter_helper.php

/**
 * Hàm tự động lấy danh sách các Token ID bị ẩn từ bảng 'collections' (hoặc 'items') trên Supabase
 */
function getSupabaseHiddenIds()
{
    // 🔑 ĐIỀN THÔNG TIN SUPABASE CỦA BẠN VÀO ĐÂY
    $supabase_url = "https://hmvvjjiiaelcsfqgxbxv.supabase.co";
    $supabase_key = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImhtdnZqamlpYWVsY3NmcWd4Ynh2Iiwicm9sZSI6ImFub24iLCJpYXQiOjE3NzQzNDg4MzcsImV4cCI6MjA4OTkyNDgzN30.zCpflfgSmBwpwe62P7cr1Ppf5dMUMjh782EhZeZ-kuw";

    // Tên bảng lưu NFT của bạn (Thay đổi giữa 'collections' hoặc 'items' tùy theo bảng bạn đặt cột is_hidden)
    $tableName = "items";
    // Tên cột lưu Token ID trong bảng đó (Ví dụ: 'item_id' hoặc 'token_id' hoặc 'id')
    $idColumn = "item_id";

    // URL gọi API Supabase lấy các dòng có is_hidden bằng true
    $url = $supabase_url . "/rest/v1/" . $tableName . "?select=" . $idColumn . "&is_hidden=eq.true";

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "apikey: $supabase_key",
        "Authorization: Bearer $supabase_key",
        "Content-Type: application/json"
    ]);

    $response = curl_exec($ch);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    $hiddenIds = [];
    if ($status == 200 && $response) {
        $data = json_decode($response, true);
        if (is_array($data)) {
            foreach ($data as $row) {
                if (isset($row[$idColumn])) {
                    $hiddenIds[] = (int)$row[$idColumn];
                }
            }
        }
    }
    return $hiddenIds;
}

/**
 * Hàm trung tâm nhận dữ liệu, tự động lọc và xuất JSON sạch ra Frontend
 */
function outputCleanJson($rawData)
{
    // Tự động lấy mảng ID bị ẩn từ Supabase về
    $hiddenTokenIds = getSupabaseHiddenIds();

    // Nếu dữ liệu truyền vào là chuỗi JSON thô, giải mã thành mảng
    if (is_string($rawData)) {
        $data = json_decode($rawData, true);
    } else {
        $data = $rawData;
    }

    if (!is_array($data)) {
        echo is_string($rawData) ? $rawData : json_encode($rawData);
        return;
    }

    // 1. Xử lý cấu trúc dữ liệu từ OpenSea (trường 'nfts')
    if (isset($data['nfts']) && is_array($data['nfts'])) {
        $data['nfts'] = array_values(array_filter($data['nfts'], function ($nft) use ($hiddenTokenIds) {
            return !in_array((int)($nft['identifier'] ?? 0), $hiddenTokenIds);
        }));
    }

    // 2. Xử lý cấu trúc dữ liệu từ Alchemy (trường 'ownedNfts')
    if (isset($data['ownedNfts']) && is_array($data['ownedNfts'])) {
        $data['ownedNfts'] = array_values(array_filter($data['ownedNfts'], function ($nft) use ($hiddenTokenIds) {
            $tokenIdHex = $nft['id']['tokenId'] ?? null;
            $tokenId = $tokenIdHex ? hexdec($tokenIdHex) : null;
            return !in_array($tokenId, $hiddenTokenIds);
        }));
    }

    // 3. Xử lý mảng danh sách NFT thuần (Nếu lấy trực tiếp từ Supabase dạng danh sách phẳng)
    if (!isset($data['nfts']) && !isset($data['ownedNfts']) && isset($data)) {
        $data = array_values(array_filter($data, function ($item) use ($hiddenTokenIds) {
            $id = $item['item_id'] ?? $item['token_id'] ?? $item['id'] ?? null;
            return !in_array((int)$id, $hiddenTokenIds);
        }));
    }

    // Xuất dữ liệu sạch ra màn hình cho Frontend nhận
    echo json_encode($data);
}
