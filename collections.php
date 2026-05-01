<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Bộ Sưu Tập | STUDIO NFT</title>
    <script src="https://cdn.tailwindcss.com"></script>
        <style>
        body {
            background: radial-gradient(circle at top right, #0891b2, #064e3b, #020617);
            color: white;
            min-height: 100vh;
        }
        
        body { 
            padding-left: 16rem;
        }

        .card-nft {
            background: rgba(255, 255, 255, 0.02); /* Trong suốt hơn */
            backdrop-filter: blur(12px); /* Hiệu ứng kính mờ */
            border: 1px solid rgba(255, 255, 255, 0.05); /* Viền cực mảnh */
            border-radius: 24px; /* Bo tròn sâu hơn cho sang trọng */
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .card-nft:hover {
            transform: translateY(-12px) scale(1.02); /* Bay bổng hơn khi rà chuột */
            border-color: rgba(6, 182, 212, 0.4);
            box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.7);
            background: rgba(255, 255, 255, 0.05);
        }

        .btn-action {
             border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            h1 { color: #00ffff; text-transform: uppercase; letter-spacing: -1px; font-size: 32px; margin-bottom: 30px; }
        }
               
    </style>
</head>
<body class="p-5 md:p-10">
     <?php if(file_exists('navbar.php')) include 'navbar.php'; ?>


    <!-- 2. BÊN PHẢI: NỘI DUNG CHÍNH -->
    <div class="page-wrapper" style="display: flex; background: radial-gradient(circle at top right, #001f1f, #000); min-height: 100vh; font-family: 'Inter', sans-serif;">
    
    <!-- 1. GIỮ NGUYÊN MENU CỦA BẠN -->
    <div class="sidebar-container" style="background: #000; border-right: 1px solid #1a3a3a;">
        <?php include 'navbar.php'; ?>
    </div>

    <!-- 2. PHẦN CHÍNH: MÀU XANH MÁT MẮT -->
    <div class="main-content" style="flex-grow: 1; margin-left: 120px; padding: 60px 50px;">
        <h1 style="color: #00ffff; font-size: 32px; font-weight: 800; letter-spacing: -1px; margin-bottom: 40px; text-shadow: 0 0 15px rgba(0,255,255,0.3);">
            Bảng Xếp Hạng Bộ Sưu Tập
        </h1>
        
        <!-- Bảng bây giờ sẽ có nền hơi trong suốt để thấy màu xanh phía sau -->
        <div style="background: rgba(255, 255, 255, 0.03); backdrop-filter: blur(10px); border-radius: 16px; border: 1px solid rgba(0, 255, 255, 0.1); overflow: hidden;">
            <!-- ... Giữ phần <table> cũ nhưng đổi màu chữ tiêu đề thành #00ffff ... -->


        <div style="background: #ffffff; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); overflow: hidden; border: 1px solid #eee;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #fcfcfc; border-bottom: 1px solid #eee;">
                        <th style="text-align: left; padding: 25px 20px; color: #888; font-size: 11px; text-transform: uppercase; font-weight: 600;">Hạng</th>
                        <th style="text-align: left; padding: 25px 20px; color: #888; font-size: 11px; text-transform: uppercase; font-weight: 600;">Bộ Sưu Tập</th>
                        <th style="text-align: left; padding: 25px 20px; color: #888; font-size: 11px; text-transform: uppercase; font-weight: 600;">Giá Sàn</th>
                        <th style="text-align: left; padding: 25px 20px; color: #888; font-size: 11px; text-transform: uppercase; font-weight: 600;">Khối Lượng</th>
                        <th style="text-align: right; padding: 25px 20px; color: #888; font-size: 11px; text-transform: uppercase; font-weight: 600;">Hợp Đồng</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $my_cols = [
                        ['name' => 'Di sản Folk #01', 'addr' => '0x718a7d4153a93331524e00d04a9bb6acffec4d50', 'floor' => '0.5', 'vol' => '12.5'],
                        ['name' => 'Báo cáo Tài chính #02', 'addr' => '0xddcd6cf61e22a082841a388a19984ff4745', 'floor' => '1.2', 'vol' => '45.0'],
                        ['name' => 'Độ dốc tàn nhẫn #03', 'addr' => '0x0b7331056821b49d6a36ed2ec3666de1d482f8a6', 'floor' => '0.8', 'vol' => '8.2'],
                        ['name' => 'Studio NFT #04', 'addr' => '0x8c88f200ea6c9a3b812424e13baab5b0a49657c3', 'floor' => '2.5', 'vol' => '150.0'],
                    ];

                    foreach ($my_cols as $i => $c): ?>
                    <tr style="border-bottom: 1px solid #f5f5f5; transition: 0.2s;" onmouseover="this.style.background='#f9f9f9'" onmouseout="this.style.background='transparent'">
                        <td style="padding: 20px; color: #aaa; font-weight: 600; font-family: 'Inter', sans-serif;"><?php echo $i + 1; ?></td>
                        <td style="padding: 20px;">
                            <div style="display: flex; align-items: center; gap: 15px;">
                                <div style="width: 50px; height: 50px; background: #eee; border-radius: 12px; border: 1px solid #eee; overflow: hidden;">
                                    <img src="https://placeholder.com" style="width:100%; height:100%; object-fit:cover;">
                                </div>
                                <div>
                                    <div style="font-weight: 700; color: #1a1a1a; font-size: 15px;"><?php echo $c['name']; ?> <i class="fas fa-check-circle" style="color: #2081e2; font-size: 12px;"></i></div>
                                    <div style="font-size: 11px; color: #999;">ERC-1155</div>
                                </div>
                            </div>
                        </td>
                        <td style="padding: 20px; color: #1a1a1a; font-weight: 700; font-size: 15px;"><?php echo $c['floor']; ?> <span style="color: #999; font-weight: 400;">MATIC</span></td>
                        <td style="padding: 20px; color: #1a1a1a; font-weight: 700; font-size: 15px;"><?php echo $c['vol']; ?> <span style="color: #999; font-weight: 400;">MATIC</span></td>
                        <td style="padding: 20px; text-align: right;">
                            <a href="https://polygonscan.com<?php echo $c['addr']; ?>" target="_blank" style="display: inline-block; padding: 8px 12px; background: #f0f0f0; border-radius: 8px; color: #555; font-family: monospace; font-size: 11px; text-decoration: none; transition: 0.3s;" onmouseover="this.style.background='#e0e0e0'" onmouseout="this.style.background='#f0f0f0'">
                                <?php echo substr($c['addr'], 0, 6); ?>...<?php echo substr($c['addr'], -4); ?>
                                <i class="fas fa-external-link-alt" style="margin-left: 5px; opacity: 0.5;"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>



</body>
</html>
