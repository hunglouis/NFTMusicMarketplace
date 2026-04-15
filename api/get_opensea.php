<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

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

$context = stream_context_create($options);
echo file_get_contents($url, false, $context);
?>
