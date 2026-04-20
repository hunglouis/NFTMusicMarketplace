<?php
require_once 'supabase.php';
require_once '../config.php';

$url = "https://polygon-mainnet.g.alchemy.com/v2/".ALCHEMY_KEY."/getNFTs?owner=".WALLET;

$data = json_decode(file_get_contents($url), true);

foreach ($data['ownedNfts'] as $nft) {

    $name = $nft['title'] ?? 'NFT';
    $image = $nft['media'][0]['gateway'] ?? '';
    $audio = $nft['metadata']['animation_url'] ?? '';

    supabaseRequest("songs", "POST", [
        "name" => $name,
        "image_url" => $image,
        "audio_url" => $audio
    ]);
}

echo "SYNC DONE";
