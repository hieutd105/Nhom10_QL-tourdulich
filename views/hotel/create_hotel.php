<?php
require_once __DIR__ . '/../../functions/auth.php';
require_once __DIR__ . '/../../functions/db_connection.php';
checkLogin(__DIR__ . '/../../index.php');

$conn = getDbConnection();

// Biến giữ giá trị form
$name = $description = $phone = '';
$price = 0.0;
$image = '';
$success_msg = $error_msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price = (float)($_POST['price'] ?? 0);
    $phone = trim($_POST['phone'] ?? '');

    // Xử lý upload ảnh
    if (!empty($_FILES['image']['name'])) {
        $target_dir = __DIR__ . '/../../images/img_hotel/';
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $file_name = time() . "_" . basename($_FILES['image']['name']);
        $target_file = $target_dir . $file_name;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            // đường dẫn tương đối từ gốc project (public)
            $image = 'images/img_hotel/' . $file_name;
        } else {
            $error_msg = "Không thể upload ảnh!";
        }
    }

    // Nếu hợp lệ thì insert
    if ($name && $description && $price > 0 && $phone && $image && !$error_msg) {

        // LƯU Ý: cột giá đúng là price_per_night (KHÔNG phải price)
        $sql = "INSERT INTO hotels (name, description, price_per_night, phone, image)
                VALUES (?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            // Hiện lỗi để biết vì sao prepare fail (sai tên cột, quyền, v.v.)
            $error_msg = "Prepare failed: " . $conn->error;
        } else {
            // name(s), description(s), price_per_night(d), phone(s), image(s)
            $stmt->bind_param("ssdss", $name, $description, $price, $phone, $image);

            if ($stmt->execute()) {
                $success_msg = "Thêm khách sạn thành công!";
                // reset form
                $name = $description = $phone = '';
                $price = 0;
                $image = '';
            } else {
                $error_msg = "Execute failed: " . $stmt->error;
            }
            $stmt->close();
        }
    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && !$error_msg) {
        $error_msg = "Vui lòng nhập đầy đủ thông tin!";
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>DNU - Thêm khách sạn</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card p-4 shadow-sm">
                <h3 class="mb-4 text-primary">THÊM KHÁCH SẠN MỚI</h3>

                <?php if ($success_msg): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?= htmlspecialchars($success_msg) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                <?php if ($error_msg): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?= htmlspecialchars($error_msg) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <form method="POST" action="" enctype="multipart/form-data" novalidate>
                    <div class="mb-3">
                        <label for="name" class="form-label">Tên khách sạn</label>
                        <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($name) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Mô tả</label>
                        <textarea class="form-control" id="description" name="description" rows="4" required><?= htmlspecialchars($description) ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="price" class="form-label">Giá / đêm (VNĐ)</label>
                        <input type="number" class="form-control" id="price" name="price" min="0" step="1000" value="<?= htmlspecialchars((string)$price) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="phone" class="form-label">Số điện thoại</label>
                        <input type="text" class="form-control" id="phone" name="phone" value="<?= htmlspecialchars($phone) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="image" class="form-label">Ảnh khách sạn</label>
                        <input type="file" class="form-control" id="image" name="image" accept="image/*" <?= $image ? '' : 'required' ?>>
                        <?php if ($image): ?>
                            <small class="text-muted d-block mt-1">Đã chọn: <?= htmlspecialchars($image) ?></small>
                        <?php endif; ?>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Thêm khách sạn</button>
                        <a href="../hotel.php" class="btn btn-secondary">Hủy</a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<!-- Đặt sau khi nạp Bootstrap để tránh lỗi -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
  // Tự đóng alert sau 3s (nếu có)
  setTimeout(() => {
    document.querySelectorAll('.alert').forEach(alertNode => {
      if (window.bootstrap && bootstrap.Alert) {
        const bsAlert = bootstrap.Alert.getOrCreateInstance(alertNode);
        bsAlert.close();
      } else {
        alertNode.remove();
      }
    });
  }, 3000);
});
</script>
</body>
</html>
