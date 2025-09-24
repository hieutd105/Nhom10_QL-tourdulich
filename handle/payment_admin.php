<?php
require_once __DIR__ . '/../functions/payment_functions.php';

$action = $_GET['action'] ?? '';
$id = $_GET['id'] ?? 0;

if ($action === 'approve' && $id) {
    approvePayment($id);
    header("Location: ../views/payment.php?success=Duyệt thành công");
    exit;
}

if ($action === 'delete' && $id) {
    deletePayment($id);
    header("Location: ../views/payment.php?success=Xóa thành công");
    exit;
}

header("Location: ../views/payment.php?error=Hành động không hợp lệ");
exit;
