<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$apiKey = "xaC_d2_cBXfF74xndB750";
$owner = "0x8429BC345266D03a433b25B8Fb6301274294D81E";

// 👇 ví của bạn
$owner = "0x8429BC345266D03a433b25B8Fb6301274294D81E";

$url = "https://polygon-mainnet.g.alchemy.com/nft/v2/$apiKey/getNFTs?owner=$owner";
$data = json_decode(file_get_contents($url), true);
echo file_get_contents($url);
?>
