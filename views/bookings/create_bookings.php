<?php
require_once __DIR__ . '/../../functions/auth.php';
require_once __DIR__ . '/../../functions/db_connection.php';
checkLogin(__DIR__ . '/../../index.php');

$conn = getDbConnection();

// Lấy danh sách khách hàng (username)
$result_users = $conn->query("SELECT id, username FROM users");
if ($result_users) {
    $users = $result_users->fetch_all(MYSQLI_ASSOC);
} else {
    die("Lỗi khi truy vấn bảng users: " . $conn->error);
}

// Lấy danh sách tour (ten_tour)
$result_tours = $conn->query("SELECT id, ten_tour FROM tours");
if ($result_tours) {
    $tours = $result_tours->fetch_all(MYSQLI_ASSOC);
} else {
    die("Lỗi khi truy vấn bảng tours: " . $conn->error);
}

// Biến giữ giá trị form
$user_id = $tour_id = $so_nguoi = $ngay_dat = $trang_thai = '';
$success_msg = $error_msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'] ?? '';
    $tour_id = $_POST['tour_id'] ?? '';
    $so_nguoi = $_POST['so_nguoi'] ?? '';
    $ngay_dat = $_POST['ngay_dat'] ?? '';
    $trang_thai = $_POST['trang_thai'] ?? '';

    if ($user_id && $tour_id && $so_nguoi && $ngay_dat && $trang_thai) {
        // Kiểm tra user_id tồn tại
        $stmt = $conn->prepare("SELECT id FROM users WHERE id=?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $res = $stmt->get_result();
        if (!$res || $res->num_rows == 0) {
            $error_msg = "Người dùng không tồn tại!";
        }

        // Kiểm tra tour_id tồn tại
        $stmt = $conn->prepare("SELECT id FROM tours WHERE id=?");
        $stmt->bind_param("i", $tour_id);
        $stmt->execute();
        $res = $stmt->get_result();
        if (!$res || $res->num_rows == 0) {
            $error_msg = "Tour không tồn tại!";
        }

        // Nếu hợp lệ, insert dữ liệu
        if (!$error_msg) {
            $stmt = $conn->prepare("INSERT INTO bookings (user_id, tour_id, so_nguoi, ngay_dat, trang_thai) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("iiiss", $user_id, $tour_id, $so_nguoi, $ngay_dat, $trang_thai);

            if ($stmt->execute()) {
                $success_msg = "Đã thêm đặt tour thành công!";
                $user_id = $tour_id = $so_nguoi = $ngay_dat = $trang_thai = '';
            } else {
                $error_msg = "Lỗi khi thêm đặt tour: " . $conn->error;
            }
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
    <title>DNU - Thêm đặt tour mới</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card p-4 shadow-sm">
                <h3 class="mb-4 text-primary">THÊM ĐẶT TOUR MỚI</h3>

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

                <script>
                setTimeout(() => {
                    document.querySelectorAll('.alert').forEach(alertNode => {
                        let bsAlert = bootstrap.Alert.getOrCreateInstance(alertNode);
                        bsAlert.close();
                    });
                }, 3000);
                </script>

                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="user_id" class="form-label">Khách hàng</label>
                        <select class="form-control" id="user_id" name="user_id" required>
                            <option value="">-- Chọn khách hàng --</option>
                            <?php foreach ($users as $user): ?>
                                <option value="<?= $user['id'] ?>" <?= ($user['id']==$user_id)?'selected':'' ?>>
                                    <?= htmlspecialchars($user['username']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="tour_id" class="form-label">Tour</label>
                        <select class="form-control" id="tour_id" name="tour_id" required>
                            <option value="">-- Chọn tour --</option>
                            <?php foreach ($tours as $tour): ?>
                                <option value="<?= $tour['id'] ?>" <?= ($tour['id']==$tour_id)?'selected':'' ?>>
                                    <?= htmlspecialchars($tour['ten_tour']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="so_nguoi" class="form-label">Số người</label>
                        <input type="number" class="form-control" id="so_nguoi" name="so_nguoi" min="1" value="<?= htmlspecialchars($so_nguoi) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="ngay_dat" class="form-label">Ngày đặt</label>
                        <input type="date" class="form-control" id="ngay_dat" name="ngay_dat" value="<?= htmlspecialchars($ngay_dat) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="trang_thai" class="form-label">Trạng thái</label>
                        <select class="form-control" id="trang_thai" name="trang_thai" required>
                            <option value="">-- Chọn trạng thái --</option>
                            <option value="Đã duyệt" <?= ($trang_thai=='Đã duyệt')?'selected':'' ?>>Đã duyệt</option>
                            <option value="Chờ duyệt" <?= ($trang_thai=='Chờ duyệt')?'selected':'' ?>>Chờ duyệt</option>
                            <option value="Hủy" <?= ($trang_thai=='Hủy')?'selected':'' ?>>Hủy</option>
                        </select>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Thêm đặt tour</button>
                        <a href="../bookings.php" class="btn btn-secondary">Hủy</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
