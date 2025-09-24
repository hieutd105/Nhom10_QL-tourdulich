<?php
// handle/payment_admin_action.php
require_once __DIR__ . '/../functions/payment_functions.php';
require_once __DIR__ . '/../config/db.php';
session_start();

// TODO: kiểm tra quyền admin, ví dụ:
// if (($_SESSION['role'] ?? '') !== 'admin') { die('No permission'); }

$action = $_GET['action'] ?? '';
$payment_id = (int)($_GET['payment_id'] ?? 0);
$pm = payment_get($payment_id);
if (!$pm) die('Payment không tồn tại');

switch ($action) {
  case 'approve':
    payment_update_status($payment_id, 'success');
    // đồng bộ đơn hàng -> paid
    $conn->query("UPDATE orders o JOIN payments p ON o.id=p.order_id SET o.status='paid' WHERE p.id=".$payment_id);
    break;
  case 'reject':
    payment_update_status($payment_id, 'failed');
    break;
}
header("Location: /views/payments_admin.php");
exit;
