<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

// 🔑 API KEY
$apiKey = "xaC_d2_cBXfF74xndB750"; // thay bằng key thật

// 👇 ví của bạn
$owner = "0x8429BC345266D03a433b25B8Fb6301274294D81E";

// URL Alchemy
$url = "https://polygon-mainnet.g.alchemy.com/nft/v2/$apiKey/getNFTs?owner=$owner";

$response = file_get_contents($url);

echo $response;
?>
