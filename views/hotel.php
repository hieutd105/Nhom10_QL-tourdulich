<?php
require_once __DIR__ . '/../functions/auth.php';
require_once __DIR__ . '/../functions/hotel_functions.php';

checkLogin();

// ✅ Thêm biến kiểm tra quyền
$isAdmin = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';

// Gợi ý: getAllHotels() nên SELECT đủ field: id,name,city,address,stars,price_per_night,phone,image,description,status
$hotels = getAllHotels();

// Helper an toàn XSS
$safe = fn($v) => htmlspecialchars((string)($v ?? ''), ENT_QUOTES, 'UTF-8');
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Danh sách khách sạn</title>
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
        .overlay-bg { position: fixed; inset: 0; background: rgba(255,255,255,0.78); z-index: 0; pointer-events: none; }
        .container { position: relative; z-index: 1; }
        .card { border-radius: 20px; box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.10); border: none; background: #fff; }
        .table thead th { background: #e9f1fb; font-weight: 700; font-size: 1.08rem; color: #2563eb; border-top-left-radius: 12px; border-top-right-radius: 12px; }
        .table tbody tr { transition: background 0.2s; }
        .table tbody tr:hover { background: #f1f5fd; }
        .btn-primary { background: linear-gradient(90deg, #2563eb 60%, #60a5fa 100%); border: none; font-weight: 600; font-size: 1rem; border-radius: 10px; box-shadow: 0 2px 8px rgba(37,99,235,0.08); }
        .btn-primary:hover { background: linear-gradient(90deg, #1d4ed8 60%, #38bdf8 100%); }
        .btn-warning { color: #fff; background: linear-gradient(90deg, #f59e42 60%, #fbbf24 100%); border: none; font-weight: 600; border-radius: 8px; }
        .btn-warning:hover { background: linear-gradient(90deg, #f97316 60%, #fde68a 100%); }
        .btn-danger { background: linear-gradient(90deg, #ef4444 60%, #f87171 100%); border: none; font-weight: 600; border-radius: 8px; }
        .btn-danger:hover { background: linear-gradient(90deg, #dc2626 60%, #fca5a5 100%); }
        .table td, .table th { vertical-align: middle; }
        .table { border-radius: 16px; overflow: hidden; background: #fff; box-shadow: 0 2px 16px rgba(0,0,0,0.04); }
        h3 { font-weight: 700; color: #2563eb; letter-spacing: 1px; display: flex; align-items: center; gap: 10px; }
        .header-icon { width: 38px; height: 38px; border-radius: 50%; object-fit: cover; box-shadow: 0 2px 8px rgba(37,99,235,0.10); }
        .desc-cell { max-width: 320px; }
        .btn-link.small { text-decoration: none; }
        .btn-link.small:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="overlay-bg"></div>
    <?php include './menu.php'; ?>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="card p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h3 class="mb-0">
                            <img src="https://cdn-icons-png.flaticon.com/512/888/888063.png" alt="hotel icon" class="header-icon">
                            DANH SÁCH KHÁCH SẠN
                        </h3>
                        <!-- ✅ Chỉ admin mới được thêm -->
                        <?php if ($isAdmin): ?>
                            <a href="hotel/create_hotel.php" class="btn btn-primary px-4 py-2">+ Thêm khách sạn</a>
                        <?php endif; ?>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered align-middle mb-0 text-center">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Tên khách sạn</th>
                                    <th>Sao</th>
                                    <th>Giá/đêm</th>
                                    <th>Mô tả</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($hotels)): ?>
                                    <?php foreach($hotels as $h): ?>
                                        <?php
                                            $desc = trim((string)($h['description'] ?? ''));
                                            $descShort = mb_strlen($desc) > 140 ? mb_substr($desc, 0, 140) . '…' : $desc;
                                        ?>
                                        <tr>
                                            <td><?= (int)$h['id'] ?></td>
                                            <td class="text-start">
                                                <?= $safe($h['name']) ?>
                                                <div>
                                                    <button type="button"
                                                        class="btn btn-link p-0 text-primary small btn-detail"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#hotelDetailModal"
                                                        data-id="<?= (int)$h['id'] ?>"
                                                        data-name="<?= $safe($h['name']) ?>"
                                                        data-city="<?= $safe($h['city'] ?? '') ?>"
                                                        data-address="<?= $safe($h['address'] ?? '') ?>"
                                                        data-phone="<?= $safe($h['phone'] ?? '') ?>"
                                                        data-stars="<?= (int)($h['stars'] ?? 0) ?>"
                                                        data-price="<?= (float)($h['price_per_night'] ?? 0) ?>"
                                                        data-status="<?= $safe($h['status'] ?? '') ?>"
                                                        data-description="<?= $safe($h['description'] ?? '') ?>"
                                                        data-image="<?= !empty($h['image']) ? '../'.$safe($h['image']) : '' ?>"
                                                    >Chi tiết</button>
                                                </div>
                                            </td>
                                            <td><?= (int)$h['stars'] ?> ★</td>
                                            <td><?= number_format((float)$h['price_per_night'], 0, ',', '.') ?> đ</td>
                                            <td class="text-start desc-cell"><div class="text-truncate"><?= $safe($descShort) ?></div></td>
                                            <td>
                                                <?php if ($isAdmin): ?>
                                                    <a href="hotel/edit_hotel.php?id=<?= (int)$h['id'] ?>" class="btn btn-warning btn-sm me-1">Sửa</a>
                                                    <a href="../handle/hotel_process.php?action=delete&id=<?= (int)$h['id'] ?>" class="btn btn-danger btn-sm"
                                                       onclick="return confirm('Bạn có chắc chắn muốn xóa?')">Xóa</a>
                                                <?php else: ?>
                                                    <span class="text-muted">Không có quyền</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr><td colspan="6" class="text-center text-muted">Chưa có khách sạn nào.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Modal Chi tiết -->
    <div class="modal fade" id="hotelDetailModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="dt-title">Chi tiết khách sạn</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
          </div>
          <div class="modal-body">
            <div class="row g-3">
              <div class="col-md-8">
                <p><strong>Thành phố:</strong> <span id="dt-city"></span></p>
                <p><strong>Địa chỉ:</strong> <span id="dt-address"></span></p>
                <p><strong>Điện thoại:</strong> <span id="dt-phone"></span></p>
                <p><strong>Sao:</strong> <span id="dt-stars"></span> ★</p>
                <p><strong>Giá/đêm:</strong> <span id="dt-price"></span> đ</p>
                <p><strong>Trạng thái:</strong> <span id="dt-status"></span></p>
                <div><strong>Mô tả:</strong><div id="dt-description" class="border rounded p-2 bg-light"></div></div>
              </div>
              <div class="col-md-4 text-center">
                <img id="dt-image" class="img-fluid rounded border mb-2 d-none" style="max-height:350px;object-fit:cover;" alt="Hotel image">
                <div id="dt-noimage" class="text-muted fst-italic d-none">Chưa có hình ảnh</div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <!-- ✅ Nút sửa chỉ admin mới thấy -->
            <?php if ($isAdmin): ?>
              <a id="dt-edit" class="btn btn-warning">Sửa</a>
            <?php endif; ?>
            <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Đóng lại</button>
          </div>
        </div>
      </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
      document.querySelectorAll('.btn-detail').forEach(btn => {
        btn.addEventListener('click', () => {
          const d = btn.dataset;
          document.getElementById('dt-title').textContent = d.name || 'Chi tiết khách sạn';
          document.getElementById('dt-city').textContent   = d.city || '';
          document.getElementById('dt-address').textContent= d.address || '';
          document.getElementById('dt-phone').textContent  = d.phone || '';
          document.getElementById('dt-stars').textContent  = d.stars || '0';
          document.getElementById('dt-price').textContent  = Number(d.price || 0).toLocaleString('vi-VN');
          const raw = (d.status || '').toString().toLowerCase();
          const active = raw === '1' || raw === 'active';
          document.getElementById('dt-status').innerHTML = active ? '<span class="badge bg-success">Hoạt động</span>' : '<span class="badge bg-secondary">Tạm dừng</span>';
          document.getElementById('dt-description').textContent = d.description || '';
          const imgEl = document.getElementById('dt-image');
          const noImgEl = document.getElementById('dt-noimage');
          if (d.image) { imgEl.src = d.image; imgEl.classList.remove('d-none'); noImgEl.classList.add('d-none'); }
          else { imgEl.src = ''; imgEl.classList.add('d-none'); noImgEl.classList.remove('d-none'); }
          const editBtn = document.getElementById('dt-edit');
          if (editBtn) { editBtn.href = 'hotel/edit_hotel.php?id=' + encodeURIComponent(d.id || ''); }
        });
      });
    });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
