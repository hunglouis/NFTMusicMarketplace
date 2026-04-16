<?php
$host = "aws-0-ap-southeast-1.pooler.supabase.com";
$port = "6543";
$dbname = "hunglouis";
$user = "hunglouis";
$pass = "sb_publishable__5FJJ7E8LE8I1rhFXS2Z5A_SdiVSdYS";

try {
    $conn = new PDO(
        "pgsql:host=aws-0-ap-southeast-1.pooler.supabase.com;port=6543;dbname=hunglouis",
        "hunglouis",
        "sb_publishable__5FJJ7E8LE8I1rhFXS2Z5A_SdiVSdYS"
    );

    // bật lỗi để debug
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    die("Kết nối thất bại: " . $e->getMessage());
}
?>

