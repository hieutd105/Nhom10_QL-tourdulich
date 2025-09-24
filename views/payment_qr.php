<?php
require_once __DIR__ . '/../functions/auth.php';
checkLogin(__DIR__ . '/../index.php');

$booking_id = $_GET['booking_id'] ?? 0;
$so_tien    = $_GET['so_tien'] ?? 0;
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thanh toán bằng QR</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f4f6f9;
            font-family: 'Montserrat', Arial, sans-serif;
        }
        .container {
            margin-top: 60px;
            text-align: center;
        }
        .qr-box {
            background: #fff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            display: inline-block;
        }
        img {
            width: 280px;
            height: auto;
        }
    </style>
</head>
<body>
<div class="container">
    <h2 class="mb-3">Thanh toán bằng QR Code</h2>
    <p>Vui lòng quét mã để thanh toán  

    <div class="qr-box">
        <!-- ảnh qr-bank.png bạn phải để ở thư mục assets hoặc images -->
        <img src="../images/qr-bank.png" alt="QR Code Thanh toán">
    </div>

    <form method="POST" action="../handle/payment_process.php" class="mt-3">
        <input type="hidden" name="booking_id" value="<?= $booking_id ?>">
        <input type="hidden" name="so_tien" value="<?= $so_tien ?>">
        <button type="submit" class="btn btn-success">Tôi đã chuyển khoản</button>
    </form>
</div>
</body>
</html>
