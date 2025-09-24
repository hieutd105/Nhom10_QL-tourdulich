<?php
require_once __DIR__ . '/../../functions/auth.php';
require_once __DIR__ . '/../../functions/db_connection.php';
checkLogin(__DIR__ . '/../../index.php');

$conn = getDbConnection();

// Lấy ID khách sạn
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    header("Location: ../hotel.php?msg=notfound");
    exit;
}

// Lấy thông tin khách sạn hiện tại
$stmt = $conn->prepare("SELECT * FROM hotels WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$hotel = $result->fetch_assoc();
$stmt->close();

if (!$hotel) {
    header("Location: ../hotel.php?msg=notfound");
    exit;
}

// Biến giữ giá trị form
$name            = $hotel['name'] ?? '';
$city            = $hotel['city'] ?? '';
$address         = $hotel['address'] ?? '';
$stars           = $hotel['stars'] ?? 0;
$phone           = $hotel['phone'] ?? '';
$price_per_night = $hotel['price_per_night'] ?? 0;
$description     = $hotel['description'] ?? '';
$status          = $hotel['status'] ?? 'active';
$current_image   = $hotel['image'] ?? ''; // đường dẫn tương đối, ví dụ: images/img_hotel/xxxx.jpg

$success_msg = $error_msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name            = trim($_POST['name'] ?? '');
    $city            = trim($_POST['city'] ?? '');
    $address         = trim($_POST['address'] ?? '');
    $stars           = (int)($_POST['stars'] ?? 0);
    $phone           = trim($_POST['phone'] ?? '');
    $price_per_night = (float)($_POST['price_per_night'] ?? 0);
    $description     = trim($_POST['description'] ?? '');
    $status          = $_POST['status'] ?? 'active';

    // Ảnh cũ nhận từ hidden để giữ nếu không upload mới
    $current_image   = trim($_POST['existing_image'] ?? $current_image);
    $new_image_path  = $current_image;

    // Nếu có upload ảnh mới, xử lý upload
    if (!empty($_FILES['image']['name'])) {
        $target_dir = __DIR__ . '/../../images/img_hotel/';
        if (!is_dir($target_dir)) {
            if (!mkdir($target_dir, 0777, true)) {
                $error_msg = "Không tạo được thư mục lưu ảnh.";
            }
        }
        if (!$error_msg) {
            $safeBase = preg_replace('/[^A-Za-z0-9_\.-]/', '_', basename($_FILES['image']['name']));
            $file_name = time() . "_" . $safeBase;
            $target_file = $target_dir . $file_name;

            if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                // Lưu đường dẫn tương đối để render
                $new_image_path = 'images/img_hotel/' . $file_name;
            } else {
                $error_msg = "Không thể upload ảnh mới.";
            }
        }
    }

    if (!$error_msg) {
        if ($name && $city && $address && $stars > 0 && $price_per_night >= 0) {
            // Cập nhật cả image (dùng ảnh mới nếu có, không thì ảnh cũ)
            $sql = "UPDATE hotels
                    SET name=?, city=?, address=?, stars=?, phone=?, price_per_night=?, image=?, description=?, status=?
                    WHERE id=?";
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                $error_msg = "Prepare failed: " . $conn->error;
            } else {
                // name(s), city(s), address(s), stars(i), phone(s), price(d), image(s), description(s), status(s), id(i)
                $stmt->bind_param(
                    "sssisdsssi",
                    $name, $city, $address,
                    $stars, $phone, $price_per_night,
                    $new_image_path, $description, $status,
                    $id
                );

                if ($stmt->execute()) {
                    $success_msg = "Cập nhật khách sạn thành công!";
                    // Cập nhật biến hiển thị
                    $current_image = $new_image_path;
                } else {
                    $error_msg = "Lỗi khi cập nhật khách sạn: " . $stmt->error;
                }
                $stmt->close();
            }
        } else {
            $error_msg = "Vui lòng điền đầy đủ thông tin bắt buộc!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sửa khách sạn</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <div class="col-md-8 mx-auto">
        <div class="card p-4 shadow-sm">
            <h3 class="mb-4 text-primary">SỬA KHÁCH SẠN</h3>

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

            <form method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label class="form-label">Tên khách sạn</label>
                    <input type="text" class="form-control" name="name" value="<?= htmlspecialchars($name) ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Thành phố</label>
                    <input type="text" class="form-control" name="city" value="<?= htmlspecialchars($city) ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Địa chỉ</label>
                    <input type="text" class="form-control" name="address" value="<?= htmlspecialchars($address) ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Số sao</label>
                    <input type="number" class="form-control" name="stars" min="1" max="5" value="<?= htmlspecialchars((string)$stars) ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Điện thoại</label>
                    <input type="text" class="form-control" name="phone" value="<?= htmlspecialchars($phone) ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Giá mỗi đêm</label>
                    <input type="number" class="form-control" name="price_per_night" min="0" step="1000" value="<?= htmlspecialchars((string)$price_per_night) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label d-block">Ảnh hiện tại</label>
                    <?php if (!empty($current_image)): ?>
                        <img src="../<?= htmlspecialchars($current_image) ?>" alt="Ảnh khách sạn" class="img-fluid rounded border mb-2" style="max-height:240px;object-fit:cover;">
                    <?php else: ?>
                        <div class="text-muted">Chưa có ảnh</div>
                    <?php endif; ?>
                    <input type="hidden" name="existing_image" value="<?= htmlspecialchars($current_image) ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label">Chọn ảnh mới (tuỳ chọn)</label>
                    <input type="file" class="form-control" name="image" accept="image/*">
                    <div class="form-text">Nếu không chọn ảnh mới, ảnh cũ sẽ được giữ nguyên.</div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Mô tả</label>
                    <textarea class="form-control" name="description" rows="4"><?= htmlspecialchars($description) ?></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Trạng thái</label>
                    <select class="form-control" name="status">
                        <option value="active"   <?= ($status === 'active')   ? 'selected' : '' ?>>Hoạt động</option>
                        <option value="inactive" <?= ($status === 'inactive') ? 'selected' : '' ?>>Ngưng hoạt động</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Cập nhật</button>
                <a href="../hotel.php" class="btn btn-secondary">Quay lại</a>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
