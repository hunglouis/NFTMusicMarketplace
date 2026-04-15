<?php
require 'supabase_config.php';
$data = json_decode(file_get_contents('php://input'), true);
if ($data) {
    callSupabase("music_collection", "POST", [
        'title' => $data['title'],
        'image_url' => $data['image_url'],
        'identifier' => $data['identifier'],
        'price' => 500
    ]);
}
?>
