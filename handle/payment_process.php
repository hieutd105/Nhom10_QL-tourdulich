<?php
require_once __DIR__ . '/../functions/payment_functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $booking_id = $_POST['booking_id'] ?? 0;
    $so_tien = $_POST['so_tien'] ?? 0;

    if ($booking_id && $so_tien > 0) {
        luuThanhToan($booking_id, $so_tien);
        header("Location: ../views/payment_success.php");
        exit;
    } else {
        header("Location: ../views/payment.php?error=Thiếu thông tin");
        exit;
    }
}
