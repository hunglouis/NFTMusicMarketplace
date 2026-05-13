<?php
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
// 1. Cấu hình thông tin Supabase
$supabase_url = "https://hmvvjjiiaelcsfqgxbxv.supabase.co"; // Thay bằng URL của bạn
$supabase_key = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImhtdnZqamlpYWVsY3NmcWd4Ynh2Iiwicm9sZSI6ImFub24iLCJpYXQiOjE3NzQzNDg4MzcsImV4cCI6MjA4OTkyNDgzN30.zCpflfgSmBwpwe62P7cr1Ppf5dMUMjh782EhZeZ-kuw"; // Thay bằng Anon Key của bạn
$table_name = "collections"; // Thay bằng tên bảng của bạn trong Supabase

// 2. Gọi API để lấy dữ liệu (Sắp xếp theo khối lượng giảm dần chẳng hạn)
$url = $supabase_url . "/rest/v1/" . $table_name . "?select=*&limit=100";
// Thay đổi dòng này:
// Nếu bạn muốn lấy tối đa nhiều dòng hơn (ví dụ 100 dòng), hãy dùng:
// $url = $supabase_url . "/rest/v1/" . $table_name . "?select=*&limit=100";


$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "apikey: " . $supabase_key,
    "Authorization: Bearer " . $supabase_key
]);

$response = curl_exec($ch);
curl_close($ch);

// 3. Chuyển đổi dữ liệu JSON sang mảng PHP
$my_cols = json_decode($response, true);

// Kiểm tra nếu lỗi hoặc không có dữ liệu thì tạo mảng rỗng
if (!$my_cols) $my_cols = [];
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Bộ Sưu Tập | STUDIO NFT</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cloudflare.com">
    <style>
        @import url('https://googleapis.com');
        
        body {
            background: radial-gradient(circle at top right, #0891b2, #064e3b, #020617);
            color: white;
            min-height: 100vh;
            font-family: 'Inter', sans-serif;
            margin: 0;
        }

        /* Container chính để không bị đè bởi sidebar */
        /* 1. Xóa hoặc sửa margin-left ở .main-content */
        .main-content {
            flex-grow: 1;
            padding: 40px;
            margin-left: 0; /* Đưa về 0 để không bị đẩy sang phải */
            display: flex;
            flex-direction: column;
            align-items: center; /* Căn giữa tiêu đề và bảng theo chiều ngang */
            width: 100%;
        }

        /* 2. Giới hạn độ rộng tối đa của bảng để không bị tràn màn hình quá to */
        .glass-card {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 24px;
            overflow: hidden;
            width: 100%;
            max-width: 1100px; /* Độ rộng đẹp nhất cho bảng */
        }


        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            text-align: left;
            padding: 20px;
            color: #00ffff;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        td {
            padding: 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        tr:hover {
            background: rgba(255, 255, 255, 0.05);
            transition: 0.3s;
        }

        .nft-image {
            width: 50px;
            height: 50px;
            background: #1e293b;
            border-radius: 12px;
            object-fit: cover;
        }

        .address-tag {
            background: rgba(255, 255, 255, 0.1);
            padding: 6px 12px;
            border-radius: 8px;
            color: #ccc;
            font-family: monospace;
            font-size: 12px;
            text-decoration: none;
        }

        .address-tag:hover {
            background: #00ffff;
            color: #000;
        }
    </style>
</head>
<body>

    <div style="display: flex;">
        <!-- Sidebar -->
        <div class="sidebar-container" style="position: fixed; width: 250px; height: 100vh;">
            <?php if(file_exists('navbar.php')) include 'navbar.php'; ?>
        </div>

        <!-- Main Content -->
        <main class="main-content">
            <h1 style="font-size: 32px; font-weight: 800; color: #00ffff; margin-bottom: 40px; text-shadow: 0 0 20px rgba(0,255,255,0.4);">
                Bảng Xếp Hạng Bộ Sưu Tập
            </h1>

            <div class="glass-card">
                <table>
                    <thead>
                        <tr>
                            <th>Hạng</th>
                            <th>Bộ Sưu Tập</th>
                            <th>Giá Sàn</th>
                            <th>Khối Lượng</th>
                            <th style="text-align: right;">Hợp Đồng</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // 1. Cấu hình thông tin Supabase
                        $supabase_url = "https://hmvvjjiiaelcsfqgxbxv.supabase.co"; // Thay bằng URL của bạn
                        $supabase_key = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImhtdnZqamlpYWVsY3NmcWd4Ynh2Iiwicm9sZSI6ImFub24iLCJpYXQiOjE3NzQzNDg4MzcsImV4cCI6MjA4OTkyNDgzN30.zCpflfgSmBwpwe62P7cr1Ppf5dMUMjh782EhZeZ-kuw"; // Thay bằng Anon Key của bạn
                        $table_name = "collections"; // Thay bằng tên bảng của bạn trong Supabase

                        // Gọi API lấy tối đa 100 dòng
                        $url = $supabase_url . "/rest/v1/" . $table_name . "?select=*&limit=100";

                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, $url);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_HTTPHEADER, [
                            "apikey: " . $supabase_key,
                            "Authorization: Bearer " . $supabase_key
                        ]);

                        $response = curl_exec($ch);
                        curl_close($ch);

                        // Chuyển dữ liệu JSON thành mảng PHP để vòng lặp foreach bên dưới chạy được
                        $my_cols = json_decode($response, true);

                        // Nếu lỗi không lấy được dữ liệu, tạo mảng rỗng để không bị lỗi trang
                        if (!$my_cols) $my_cols = [];


                        foreach ($my_cols as $i => $c): ?>
                        <tr>
                            <td style="font-weight: 700; color: rgba(255,255,255,0.5);"><?php echo $i + 1; ?></td>
                            <td>
                                <div style="display: flex; align-items: center; gap: 15px;">
                                    <img src="<?php echo $c['image_url'] ?? 'https://placeholder.com'; ?>"
                                            onerror="this.src='https://placeholder.com';" 
                                            class="nft-image">
                                    <div>
                                        <div style="font-weight: 700; color: #fff;"><?php echo $c['collection_name']; ?> <i class="fas fa-check-circle" style="color: #2081e2; font-size: 12px;"></i></div>
                                        <div style="font-size: 11px; color: #64748b;">ERC-1155</div>
                                    </div>
                                </div>
                            </td>
                            <td style="font-weight: 700;"><?php echo $c['price']; ?> <span style="color: #64748b; font-weight: 400;">MATIC</span></td>
                            <td style="font-weight: 700;"><?php echo $c['volume_total']; ?> <span style="color: #64748b; font-weight: 400;">MATIC</span></td>
                            <td style="text-align: right;">
                                <a href="https://polygonscan.com<?php echo $c['contract_address']; ?>" target="_blank" class="address-tag">
                                    <?php echo substr($c['contract_address'], 0, 6); ?>...<?php echo substr($c['contract_address'], -4); ?>
                                    <?php echo number_format($c['price'] ?? 0, 2); ?> MATIC
                                    <i class="fas fa-external-link-alt" style="margin-left: 5px; font-size: 10px;"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

</body>
</html>
