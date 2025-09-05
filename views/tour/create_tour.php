<?php
require_once __DIR__ . '/../../functions/auth.php';
checkLogin(__DIR__ . '/../../index.php');
?>
<!DOCTYPE html>
<html>

<head>
    <title>DNU - Thêm tour mới</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-3">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h3 class="mt-3 mb-4">THÊM TOUR DU LỊCH MỚI</h3>
                
                <?php
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
                
                <form action="../../handle/tour_process.php" method="POST">
                    <input type="hidden" name="action" value="create">
                    
                    <div class="mb-3">
                        <label for="ten_tour" class="form-label">Tên tour</label>
                        <input type="text" class="form-control" id="ten_tour" name="ten_tour" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="gia" class="form-label">Giá (VNĐ)</label>
                        <input type="number" class="form-control" id="gia" name="gia" min="0" step="1000" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="ngay_khoi_hanh" class="form-label">Ngày khởi hành</label>
                        <input type="date" class="form-control" id="ngay_khoi_hanh" name="ngay_khoi_hanh" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="so_cho" class="form-label">Số chỗ</label>
                        <input type="number" class="form-control" id="so_cho" name="so_cho" min="1" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="mo_ta" class="form-label">Mô tả</label>
                        <textarea class="form-control" id="mo_ta" name="mo_ta" rows="3" required></textarea>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Thêm tour</button>
                        <a href="../tour.php" class="btn btn-secondary">Hủy</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
