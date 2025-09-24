<?php
// handle/checkout_qr.php
require_once __DIR__ . '/../functions/payment_functions.php';
require_once __DIR__ . '/../config/db.php';
session_start();

$user_id = $_SESSION['user_id'] ?? 0;
if (!$user_id) { header("Location: /views/login.php?next=/"); exit; }

$item_type = $_POST['item_type'] ?? 'tour';      // 'tour' | 'hotel'
$item_id   = (int)($_POST['item_id'] ?? 0);
$qty       = max(1, (int)($_POST['quantity'] ?? 1));

/** LẤY GIÁ TỪ DB — chỉnh tên bảng/cột đúng với DB của bạn */
if ($item_type === 'hotel') {
    $rs = $conn->query("SELECT id, price FROM hotels WHERE id=$item_id LIMIT 1");
} else {
    $rs = $conn->query("SELECT id, price FROM tours WHERE id=$item_id LIMIT 1");
}
$row = $rs ? $rs->fetch_assoc() : null;
if (!$row) { die('Không tìm thấy sản phẩm.'); }

$order_id   = order_create($user_id, $item_type, $item_id, $qty, (float)$row['price']);
$order      = order_find($order_id);
$payment_id = payment_create($order_id, 'bank_qr', $order['total_amount'], $order['currency']);

header("Location: /views/payment_qr.php?order_id=".$order_id);
exit;
