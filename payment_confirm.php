<?php
require_once __DIR__ . '/../functions/auth.php';
require_once __DIR__ . '/../functions/payment_functions.php';
checkLogin(__DIR__ . '/../index.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tour_id = $_POST['tour_id'] ?? null;
    $amount  = $_POST['amount'] ?? null;
    $user_id = $_SESSION['user_id'];

    if ($tour_id && $amount) {
        luuThanhToan($user_id, $tour_id, $amount, 'QR');
        header("Location: payment.php?success=Thanh toán đã được ghi nhận (Chờ xét duyệt)");
        exit;
    }
}

header("Location: tour.php?error=Thiếu thông tin thanh toán");
exit;
