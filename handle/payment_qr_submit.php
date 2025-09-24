<?php
// handle/payment_qr_submit.php
require_once __DIR__ . '/../functions/payment_functions.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); echo "Phương thức không hợp lệ"; exit; }
$payment_id = (int)($_POST['payment_id'] ?? 0);
$pm = payment_get($payment_id);
if (!$pm) { echo "Không tìm thấy payment."; exit; }

// Upload ảnh (tùy chọn)
if (isset($_FILES['proof']) && $_FILES['proof']['error'] === UPLOAD_ERR_OK) {
  $ext = strtolower(pathinfo($_FILES['proof']['name'], PATHINFO_EXTENSION));
  $ext = preg_replace('/[^a-z0-9]/','', $ext);
  if (!in_array($ext, ['jpg','jpeg','png','webp'])) $ext='jpg';
  $newName = 'proof_'.$payment_id.'_'.time().'.'.$ext;
  $uploadDir = __DIR__ . '/../uploads/proofs';
  if (!is_dir($uploadDir)) { mkdir($uploadDir, 0775, true); }
  $dest = $uploadDir.'/'.$newName;
  if (move_uploaded_file($_FILES['proof']['tmp_name'], $dest)) {
    payment_attach_proof($payment_id, '/uploads/proofs/'.$newName);
  }
}

// Đổi trạng thái -> submitted
payment_update_status($payment_id, 'submitted');
// Chuyển về trang đơn/đặt chỗ của bạn (sửa đường dẫn nếu khác)
header("Location: /views/bookings.php?notice=submitted");
exit;
