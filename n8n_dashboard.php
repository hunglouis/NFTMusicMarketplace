<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Bảng Điều Khiển AI Automation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input,
        textarea {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            background: #007bff;
            color: white;
            border: none;
            padding: 10px 15px;
            cursor: pointer;
            width: 100%;
            border-radius: 4px;
        }

        button:hover {
            background: #0056b3;
        }
    </style>
</head>

<body>
    <h2>🤖 Tạo Bài Đăng Tự Động</h2>
    <div class="form-group">
        <label for="topic">Chủ đề bài viết:</label>
        <input type="text" id="topic" placeholder="Ví dụ: Lợi ích của học JavaScript...">
    </div>
    <div class="form-group">
        <label for="hashtags">Hashtags mong muốn:</label>
        <input type="text" id="hashtags" placeholder="#javascript #programming">
    </div>
    <button onclick="sendToN8n()">Kích hoạt n8n chạy AI</button>
    <script>
        async function sendToN8n() {
            const n8nWebhookUrl = `https://automation-backend-aety.onrender.com/webhook/webhook-discord-auto`;
            const data = {
                chu_de: document.getElementById('topic').value,
                hashtags: document.getElementById('hashtags').value
            };
            try {
                const response = await fetch(n8nWebhookUrl, {
                    method: 'POST',
                    mode: 'cors',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(data)
                });
                if (response.ok) {
                    alert('Đã gửi dữ liệu sang n8n thành công!');
                } else {
                    alert('Lỗi phản hồi từ n8n.');
                }
            } catch (error) {
                console.error('Lỗi kết nối:', error);
                alert('Không thể kết nối tới n8n!');
            }
        }
    </script>
</body>

</html>