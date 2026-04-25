<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

// 1. Điền Key Alchemy của bạn (Lấy trên dashboard.alchemy.com)
$apiKey = "xaC_d2_cBXfF74xndB750"; 

// 2. Điền địa chỉ ví Polygon của bạn
$owner = "0x8429BC345266D03a433b25B8Fb6301274294D81E"; 

$url = "https://polygon-mainnet.g.alchemy.com/nft/v2/$apiKey/getNFTs?owner=$owner";

// Sử dụng cURL thay vì file_get_contents để ổn định hơn
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

echo $response;
?>
