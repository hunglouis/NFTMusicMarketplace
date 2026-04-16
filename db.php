<?php
$host = "aws-1-ap-northeast-1.pooler.supabase.com";
$port = "5432";
$dbname = "postgres";
$user = "postgres.hmvvjjiiaelcsfqgxbxv";
$pass = "sb_publishable__5FJJ7E8LE8I1rhFXS2Z5A_SdiVSdYS";

try {
    $conn = new PDO(
        "DATABASE_URL=postgresql://postgres.hmvvjjiiaelcsfqgxbxv:[sb_publishable__5FJJ7E8LE8I1rhFXS2Z5A_SdiVSdYS]@aws-1-ap-northeast-1.pooler.supabase.com:5432/postgres"
    );

    // bật lỗi để debug
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    die("Ket noi that bai:" . $e->getMessage());
}
?>

