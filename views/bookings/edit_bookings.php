<?php
require_once __DIR__ .'/../../functions/auth.php';
checkLogin(__DIR__ .'/../../index.php');
?>
<!DOCTYPE html>
<html>

<head>
    <title>DNU - Chỉnh sửa đặt tour</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-3">
        <h3 class="mt-3 mb-4 text-center">CHỈNH SỬA ĐẶT TOUR DU LỊCH</h3>
        
        <?php
        // Kiểm tra có ID không
        if (!isset($_GET['id']) || empty($_GET['id'])) {
            header("Location: ../bookings.php?error=Không tìm thấy đặt tour");
            exit;
        }
        
        $id = $_GET['id'];
        
        // Lấy thông tin đặt tour
        require_once __DIR__ .'/../../functions/bookings_functions.php';
        $booking = getBookingById($id);

        if (!$booking) {
            header("Location: ../bookings.php?error=Không tìm thấy đặt tour");
            exit;
        }
        
        // Hiển thị thông báo lỗi
        if (isset($_GET['error'])) {
            echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                ' . htmlspecialchars($_GET['error']) . '
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>';
        }
        ?>
        <script>
        // Sau 3 giây sẽ tự động ẩn alert
        setTimeout(() => {
            let alertNode = document.querySelector('.alert');
            if (alertNode) {
                let bsAlert = bootstrap.Alert.getOrCreateInstance(alertNode);
                bsAlert.close();
            }
        }, 3000);
        </script>
        
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <form action="../../handle/bookings_process.php" method="POST">
                            <input type="hidden" name="action" value="edit">
                            <input type="hidden" name="id" value="<?php echo htmlspecialchars($booking['id']); ?>">
                            
                            <div class="mb-3">
                                <label for="user_id" class="form-label">Mã khách hàng (user_id)</label>
                                <input type="number" class="form-control" id="user_id" name="user_id" 
                                       value="<?php echo htmlspecialchars($booking['user_id']); ?>" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="tour_id" class="form-label">Mã tour (tour_id)</label>
                                <input type="number" class="form-control" id="tour_id" name="tour_id" 
                                       value="<?php echo htmlspecialchars($booking['tour_id']); ?>" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="so_nguoi" class="form-label">Số người</label>
                                <input type="number" class="form-control" id="so_nguoi" name="so_nguoi" min="1"
                                       value="<?php echo htmlspecialchars($booking['so_nguoi']); ?>" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="ngay_dat" class="form-label">Ngày đặt</label>
                                <input type="date" class="form-control" id="ngay_dat" name="ngay_dat"
                                       value="<?php echo htmlspecialchars($booking['ngay_dat']); ?>" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="trang_thai" class="form-label">Trạng thái</label>
                                <input type="text" class="form-control" id="trang_thai" name="trang_thai"
                                       value="<?php echo htmlspecialchars($booking['trang_thai']); ?>" required>
                            </div>
                            
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="../bookings.php" class="btn btn-secondary me-md-2">Hủy</a>
                                <button type="submit" class="btn btn-primary">Cập nhật</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>