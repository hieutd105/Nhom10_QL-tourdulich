<?php

require_once __DIR__ . '/../../functions/auth.php';
checkLogin(__DIR__ . '/../../index.php');
?>
<!DOCTYPE html>
<html>

<head>
    <title>DNU - Chỉnh sửa tour du lịch</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-3">
        <h3 class="mt-3 mb-4 text-center">CHỈNH SỬA TOUR DU LỊCH</h3>
        <?php
            // Kiểm tra có ID không
            if (!isset($_GET['id']) || empty($_GET['id'])) {
                header("Location: ../tour.php?error=Không tìm thấy tour");
                exit;
            }
            
            $id = $_GET['id'];
            
            // Lấy thông tin tour
            require_once __DIR__ . '/../../handle/tour_process.php';
            $tour = handleGetTourById($id);

            if (!$tour) {
                header("Location: ../tour.php?error=Không tìm thấy tour");
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
                        <form action="../../handle/tour_process.php" method="POST">
                            <input type="hidden" name="action" value="edit">
                            <input type="hidden" name="id" value="<?php echo htmlspecialchars($tour['id']); ?>">

                            <div class="mb-3">
                                <label for="ten_tour" class="form-label">Tên tour</label>
                                <input type="text" class="form-control" id="ten_tour" name="ten_tour"
                                    value="<?php echo htmlspecialchars($tour['ten_tour']); ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="gia" class="form-label">Giá (VNĐ)</label>
                                <input type="number" class="form-control" id="gia" name="gia" min="0" step="1000"
                                    value="<?php echo htmlspecialchars($tour['gia']); ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="ngay_khoi_hanh" class="form-label">Ngày khởi hành</label>
                                <input type="date" class="form-control" id="ngay_khoi_hanh" name="ngay_khoi_hanh"
                                    value="<?php echo htmlspecialchars($tour['ngay_khoi_hanh']); ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="so_cho" class="form-label">Số chỗ</label>
                                <input type="number" class="form-control" id="so_cho" name="so_cho" min="1"
                                    value="<?php echo htmlspecialchars($tour['so_cho']); ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="mo_ta" class="form-label">Mô tả</label>
                                <textarea class="form-control" id="mo_ta" name="mo_ta" rows="3" required><?php echo htmlspecialchars($tour['mo_ta']); ?></textarea>
                            </div>

                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="../tour.php" class="btn btn-secondary me-md-2">Hủy</a>
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
</html>