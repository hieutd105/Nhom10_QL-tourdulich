<?php
require_once __DIR__ . '/../../functions/auth.php';
require_once __DIR__ . '/../../functions/db_connection.php';
checkLogin(__DIR__ . '/../../index.php');

$conn = getDbConnection();

$company_name = $vehicle_type = $seats = $license_plate = $route_from = $route_to = $price_per_trip = $phone = $description = $status = '';
$success_msg = $error_msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $company_name = $_POST['company_name'] ?? '';
    $vehicle_type = $_POST['vehicle_type'] ?? '';
    $seats = $_POST['seats'] ?? '';
    $license_plate = $_POST['license_plate'] ?? '';
    $route_from = $_POST['route_from'] ?? '';
    $route_to = $_POST['route_to'] ?? '';
    $price_per_trip = $_POST['price_per_trip'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $description = $_POST['description'] ?? '';
    $status = $_POST['status'] ?? 'active';

    if ($company_name && $vehicle_type && $seats && $license_plate && $route_from && $route_to && $price_per_trip) {
        $stmt = $conn->prepare("INSERT INTO transports (company_name, vehicle_type, seats, license_plate, route_from, route_to, price_per_trip, phone, description, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssisssdsss", $company_name, $vehicle_type, $seats, $license_plate, $route_from, $route_to, $price_per_trip, $phone, $description, $status);

        if ($stmt->execute()) {
            $success_msg = "Thêm nhà xe thành công!";
            $company_name = $vehicle_type = $seats = $license_plate = $route_from = $route_to = $price_per_trip = $phone = $description = $status = '';
        } else {
            $error_msg = "Lỗi khi thêm nhà xe: " . $conn->error;
        }
    } else {
        $error_msg = "Vui lòng điền đầy đủ thông tin!";
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>DNU - Thêm nhà xe mới</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card p-4 shadow-sm">
                <h3 class="mb-4 text-primary">THÊM NHÀ XE MỚI</h3>

                <?php if ($success_msg): ?>
                    <div class="alert alert-success"><?= htmlspecialchars($success_msg) ?></div>
                <?php endif; ?>
                <?php if ($error_msg): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error_msg) ?></div>
                <?php endif; ?>

                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Tên công ty</label>
                        <input type="text" class="form-control" name="company_name" value="<?= htmlspecialchars($company_name) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Loại xe</label>
                        <select class="form-control" name="vehicle_type" required>
                            <option value="bus" <?= ($vehicle_type=='bus')?'selected':'' ?>>Xe khách</option>
                            <option value="limousine" <?= ($vehicle_type=='limousine')?'selected':'' ?>>Limousine</option>
                            <option value="car" <?= ($vehicle_type=='car')?'selected':'' ?>>Xe con</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Số ghế</label>
                        <input type="number" class="form-control" name="seats" min="1" value="<?= htmlspecialchars($seats) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Biển số</label>
                        <input type="text" class="form-control" name="license_plate" value="<?= htmlspecialchars($license_plate) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tuyến đi</label>
                        <input type="text" class="form-control" name="route_from" placeholder="Đi từ..." value="<?= htmlspecialchars($route_from) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tuyến đến</label>
                        <input type="text" class="form-control" name="route_to" placeholder="Đến..." value="<?= htmlspecialchars($route_to) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Giá mỗi chuyến</label>
                        <input type="number" class="form-control" name="price_per_trip" min="0" value="<?= htmlspecialchars($price_per_trip) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Điện thoại</label>
                        <input type="text" class="form-control" name="phone" value="<?= htmlspecialchars($phone) ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mô tả</label>
                        <textarea class="form-control" name="description"><?= htmlspecialchars($description) ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Trạng thái</label>
                        <select class="form-control" name="status">
                            <option value="active" <?= ($status=='active')?'selected':'' ?>>Hoạt động</option>
                            <option value="inactive" <?= ($status=='inactive')?'selected':'' ?>>Ngưng hoạt động</option>
                        </select>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Thêm nhà xe</button>
                        <a href="../transport.php" class="btn btn-secondary">Hủy</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>
