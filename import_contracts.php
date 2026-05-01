<?php
// 1. Cấu hình kết nối Supabase của bạn
$supabase_url = "https://hmvvjjiiaelcsfqgxbxv.supabase.co";
$supabase_key = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImhtdnZqamlpYWVsY3NmcWd4Ynh2Iiwicm9sZSI6ImFub24iLCJpYXQiOjE3NzQzNDg4MzcsImV4cCI6MjA4OTkyNDgzN30.zCpflfgSmBwpwe62P7cr1Ppf5dMUMjh782EhZeZ-kuw";

// 2. Danh sách 5 hợp đồng nghệ sĩ đã cung cấp
$contracts = [
    [
        "address" => "0x718a7d4153a93331524e00d04a9bb6acffec4d50",
        "name" => "Bộ sưu tập Di sản 01",
        "standard" => "ERC-1155"
    ],
    [
        "address" => "0xddcd6cf61e81c6e22a082841a388a19984ff4745",
        "name" => "Bộ sưu tập Di sản 02",
        "standard" => "ERC-1155"
    ],
    [
        "address" => "0x0b7331056821b49d6a36ed2ec3666de1d482f8a6",
        "name" => "Bộ sưu tập Di sản 03",
        "standard" => "ERC-1155"
    ],
    [
        "address" => "0x8c88f200ea6c9a3b812424e13baab5b0a49657c3",
        "name" => "Bộ sưu tập Di sản 04",
        "standard" => "ERC-1155"
    ],
    [
        "address" => "0x83bf87ce3eb9b3ddd552fe7dff9d63acaec01b7d",
        "name" => "Bộ sưu tập Di sản 05",
        "standard" => "ERC-1155"
    ]
];

// 3. Vòng lặp gửi dữ liệu lên Supabase
foreach ($contracts as $c) {
    $data = [
        "contract_address" => $c['address'],
        "collection_name"  => $c['name'],
        "creator_address"  => "0x8429BC345266D03a433b25B8Fb6301274294D81E", // Thay địa chỉ ví của bạn vào
        "is_verified"      => true // Tặng nghệ sĩ tích xanh luôn!
    ];

    $ch = curl_init("$supabase_url/rest/v1/collections");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "apikey: $supabase_key",
        "Authorization: Bearer $supabase_key",
        "Content-Type: application/json",
        "Prefer: return=representation"
    ]);

    $response = curl_exec($ch);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($status == 201) {
        echo "✅ Đã lên kệ hợp đồng: " . $c['address'] . "<br>";
    } else {
        echo "❌ Lỗi hợp đồng: " . $c['address'] . " (Có thể đã tồn tại)<br>";
    }
}
?>
