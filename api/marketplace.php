<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

// 🔑 config
$alchemyKey = "xaC_d2_cBXfF74xndB750";
$wallet = "0x8429BC345266D03a433b25B8Fb6301274294D81E";
$openseaKey = "b736ad1e23c74136b98079b71923bfcb";

// =======================
// 🔥 1. LẤY NFT TỪ ALCHEMY
// =======================
$alchemyUrl = "https://polygon-mainnet.g.alchemy.com/nft/v2/$alchemyKey/getNFTs?owner=$wallet";

$alchemyData = json_decode(file_get_contents($alchemyUrl), true);

// =======================
// 🔥 2. LẤY DATA OPENSEA
// =======================
$openseaUrl = "https://api.opensea.io/api/v2/chain/polygon/account/$wallet/nfts";

$opts = [
    "http" => [
        "method" => "GET",
        "header" => [
            "Accept: application/json",
            "X-API-KEY: $openseaKey"
        ]
    ]
];

$context = stream_context_create($opts);
$openseaData = json_decode(file_get_contents($openseaUrl, false, $context), true);

// =======================
// 🔥 3. MAP PRICE TỪ OPENSEA
// =======================
$priceMap = [];

if (!empty($openseaData["nfts"])) {
    foreach ($openseaData["nfts"] as $item) {
        $id = strtolower("0x" . dechex($item["identifier"]));
        $price = $item["price"]["current"]["value"] ?? null;

        $priceMap[$id] = $price;
    }
}

// =======================
// 🔥 4. GỘP DATA
// =======================
$result = [];


foreach ($alchemyData["ownedNfts"] as $nft) {

    $tokenId = strtolower($nft["id"]["tokenId"]);

    // 🎯 name
    $name = $nft["title"] ?? $nft["metadata"]["name"] ?? "No Name";

    // 🎯 image
    $image = $nft["media"][0]["thumbnail"] ?? "";

    // 🎯 audio
    $audio = $nft["media"][0]["gateway"] ?? "";

    // fix IPFS
    if (strpos($audio, "ipfs://") !== false) {
        $audio = str_replace("ipfs://", "https://ipfs.io/ipfs/", $audio);
    }

    // 🎯 price
    $price = $priceMap[$tokenId] ?? null;

   $result[] = [
    //"tokenId" => $tokenId,
    "name" => $name,
    "image" => $image,
    "audio" => $audio,
    "price" => $price,
    "opensea_url" => "https://opensea.io/assets/matic/",
    "contract" => $nft["contract"].["address"] . "/" . hexdec($nft["id"]["tokenId"])
];
}

// =======================
// 🔥 OUTPUT
// =======================
echo json_encode($result, JSON_PRETTY_PRINT);
?>
