<?php
// Bao gồm thư viện qrlib.php
include 'qrlib.php';

// Lấy dữ liệu từ tham số 'data' trên URL
// Ví dụ: ?data=Hello%20World
$data = isset($_GET['data']) ? urldecode($_GET['data']) : 'No data provided';

// Đặt header để trình duyệt hiểu đây là một hình ảnh PNG
header('Content-Type: image/png');

// Tạo và xuất hình ảnh QR Code ra trình duyệt
// Tham số: data, đường dẫn file (false = không lưu), mức độ sửa lỗi (L,M,Q,H), kích thước pixel, lề
QRcode::png($data, false, 'L', 5, 2);
?>s