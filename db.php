<?php
$host = "db.hmvvjjiiaelcsfqgxbxv.supabase.co";
$port = "5432";
$dbname = "hunglouis";
$user = "hunglouis";
$pass = "sb_publishable__5FJJ7E8LE8I1rhFXS2Z5A_SdiVSdYS";

try {
    $conn = new PDO(
        "pgsql:host=$host;port=$port;dbname=$dbname",
        $user,
        $pass
    );

    // bật lỗi để debug
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    die("Kết nối thất bại: " . $e->getMessage());
}
?>

