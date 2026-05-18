<?php
// api/log_activity.php
function saveActivity($itemId, $itemName, $eventType, $fromAddress, $toAddress = null, $price = 0)
{
    require_once "filter_helper.php"; // 1. CHÈN THÊM VÀO ĐẦU FILE   

    $supabaseUrl = "supabase.co"; // Thay URL thật của bạn
    $supabaseAnonKey = "your-anon-key"; // Thay Key thật của bạn
    $tableName = "activities";

    $apiUrl = $supabaseUrl . "/rest/v1/" . $tableName;

    $data = [
        'item_id'     => strtolower(trim($itemId)),
        'item_name'    => $itemName,
        'event_type'   => $eventType,
        'from_address' => strtolower(trim($fromAddress)),
        'to_address'   => $toAddress ? strtolower(trim($toAddress)) : null,
        'price'        => (float)$price
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiUrl);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "apikey: " . $supabaseAnonKey,
        "Authorization: Bearer " . $supabaseAnonKey,
        "Content-Type: application/json"
    ]);

    $response = curl_exec($ch);
    curl_close($ch);
    return $response;
}
