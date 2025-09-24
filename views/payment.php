<?php
require_once __DIR__ . '/../functions/auth.php';
require_once __DIR__ . '/../functions/payment_functions.php';
checkLogin(__DIR__ . '/../index.php');

$payments = getAllPayments();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Danh sách thanh toán</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;700&display=swap" rel="stylesheet">
    <style>
        body {
            background: url('https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=1500&q=80') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Montserrat', Arial, sans-serif;
            position: relative;
        }
        .overlay-bg {
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(255,255,255,0.78);
            z-index: 0;
        }
        .container { position: relative; z-index: 1; }
        .card {
            border-radius: 20px;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.10);
            border: none;
            background: #fff;
        }
        .table thead th {
            background: #e9f1fb;
            font-weight: 700;
            font-size: 1.08rem;
            color: #2563eb;
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
        }
        .table tbody tr:hover { background: #f1f5fd; }
        .table td, .table th { vertical-align: middle; }
        h3 {
            font-weight: 700;
            color: #2563eb;
            letter-spacing: 1px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .header-icon {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            object-fit: cover;
            box-shadow: 0 2px 8px rgba(37,99,235,0.10);
        }
        .btn-success {
            background: linear-gradient(90deg, #16a34a 60%, #4ade80 100%);
            border: none;
            font-weight: 600;
            border-radius: 8px;
        }
        .btn-danger {
            background: linear-gradient(90deg, #ef4444 60%, #f87171 100%);
            border: none;
            font-weight: 600;
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <div class="overlay-bg"></div>
    <?php include './menu.php'; ?>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-11">
                <div class="card p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h3 class="mb-0">
                            <img src="https://cdn-icons-png.flaticon.com/512/942/942751.png" alt="icon" class="header-icon">
                            DANH SÁCH THANH TOÁN
                        </h3>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Tên tour</th>
                                    <th>Giá tiền</th>
                                    <th>Phương thức</th>
                                    <th>Trạng thái</th>
                                    <th>Ngày tạo</th>
                                    <th class="text-center">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($payments && $payments->num_rows > 0): ?>
                                    <?php while ($row = $payments->fetch_assoc()): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($row['ten_tour']) ?></td>
                                            <td><?= number_format($row['so_tien'], 0, ',', '.') ?> đ</td>
                                            <td><?= htmlspecialchars($row['phuong_thuc']) ?></td>
                                            <td>
                                                <?php if ($row['trang_thai'] === 'paid'): ?>
                                                    <span class="badge bg-success">Đã duyệt</span>
                                                <?php elseif ($row['trang_thai'] === 'pending'): ?>
                                                    <span class="badge bg-warning text-dark">Chờ xét duyệt</span>
                                                <?php else: ?>
                                                    <span class="badge bg-danger">Thất bại</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?= $row['ngay_tao'] ?></td>
                                            <td class="text-center">
                                                <?php if ($_SESSION['role'] === 'admin'): ?>
                                                    <?php if ($row['trang_thai'] === 'pending'): ?>
                                                        <a href="../handle/payment_admin.php?action=approve&id=<?= $row['id'] ?>" class="btn btn-success btn-sm">Duyệt</a>
                                                    <?php endif; ?>
                                                    <a href="../handle/payment_admin.php?action=delete&id=<?= $row['id'] ?>" class="btn btn-danger btn-sm">Xóa</a>
                                                <?php else: ?>
                                                    <span class="text-muted">Không có quyền</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr><td colspan="6" class="text-center text-muted">Chưa có thanh toán nào</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
