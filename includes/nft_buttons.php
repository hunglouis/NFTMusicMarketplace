<?php
// Vì đây là trang Profile cá nhân, 100% vật phẩm hiển thị ở đây đều thuộc quyền sở hữu của chính bạn
$isListed = isset($nft['is_listed']) ? $nft['is_listed'] : false;
$tokenId = isset($nft['token_id']) ? $nft['token_id'] : '';
?>