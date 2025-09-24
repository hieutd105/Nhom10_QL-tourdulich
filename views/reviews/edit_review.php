<?php
require_once __DIR__ . '/../../functions/auth.php';
require_once __DIR__ . '/../../functions/db_connection.php';
require_once __DIR__ . '/../../functions/review_functions.php';

checkLogin(__DIR__ . '/../../index.php');

/* 1) Lấy id và kiểm tra hợp lệ */
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    header('Location: ../reviews.php?msg=invalid_id');
    exit;
}

/* 2) Lấy chi tiết review */
$review = getReviewById($id);
if (!$review) {
    header('Location: ../reviews.php?msg=notfound');
    exit;
}

/* 3) Lấy danh sách users & tours */
$conn = getDbConnection();
$users = [];
if ($resU = mysqli_query($conn, "SELECT id, username FROM users ORDER BY id")) {
    while ($row = mysqli_fetch_assoc($resU)) $users[] = $row;
    mysqli_free_result($resU);
}
$tours = [];
if ($resT = mysqli_query($conn, "SELECT id, ten_tour FROM tours ORDER BY id")) {
    while ($row = mysqli_fetch_assoc($resT)) $tours[] = $row;
    mysqli_free_result($resT);
}

/* 4) Xử lý submit */
$success_msg = $error_msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tour_id = (int)($_POST['tour_id'] ?? 0);
    $user_id = (int)($_POST['user_id'] ?? 0);
    $rating  = (int)($_POST['rating'] ?? 0);
    $comment = trim($_POST['comment'] ?? '');

    if ($tour_id > 0 && $user_id > 0 && $rating >= 1 && $rating <= 5) {
        if (updateReview($id, $tour_id, $user_id, $rating, $comment)) {
            header('Location: ../review.php?msg=updated');
            exit;
        } else {
            $error_msg = 'Cập nhật thất bại (có thể do ràng buộc khóa ngoại tour_id/user_id).';
        }
    } else {
        $error_msg = 'Vui lòng chọn tour, người dùng và rating (1-5).';
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Sửa đánh giá tour</title>
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
    h3 {
        font-weight: 700;
        color: #2563eb;
        letter-spacing: 1px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .header-icon {
        width: 38px; height: 38px; border-radius: 50%;
        object-fit: cover;
        box-shadow: 0 2px 8px rgba(37,99,235,0.10);
    }
  </style>
</head>
<body>
<div class="overlay-bg"></div>
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-7">    
      <div class="card p-4">
        <h3 class="mb-4">
          <img src="https://cdn-icons-png.flaticon.com/512/1828/1828884.png" alt="review icon" class="header-icon">
          SỬA ĐÁNH GIÁ #<?= (int)$review['id'] ?>
        </h3>

        <?php if ($success_msg): ?>
          <div class="alert alert-success alert-dismissible fade show"><?= htmlspecialchars($success_msg) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          </div>
        <?php endif; ?>
        <?php if ($error_msg): ?>
          <div class="alert alert-danger alert-dismissible fade show"><?= htmlspecialchars($error_msg) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          </div>
        <?php endif; ?>

        <form method="POST">
          <div class="mb-3">
            <label class="form-label">Tour</label>
            <select name="tour_id" class="form-control" required>
              <?php foreach ($tours as $t): ?>
                <option value="<?= (int)$t['id'] ?>" <?= ((int)$review['tour_id'] === (int)$t['id']) ? 'selected' : '' ?>>
                  <?= htmlspecialchars($t['ten_tour']) ?> (ID: <?= (int)$t['id'] ?>)
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label">Người dùng</label>
            <select name="user_id" class="form-control" required>
              <?php foreach ($users as $u): ?>
                <option value="<?= (int)$u['id'] ?>" <?= ((int)$review['user_id'] === (int)$u['id']) ? 'selected' : '' ?>>
                  <?= htmlspecialchars($u['username']) ?> (ID: <?= (int)$u['id'] ?>)
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label">Rating (1-5)</label>
            <input type="number" name="rating" class="form-control" min="1" max="5"
                   value="<?= (int)$review['rating'] ?>" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Bình luận</label>
            <textarea name="comment" class="form-control" rows="4"><?= htmlspecialchars($review['comment'] ?? '') ?></textarea>
          </div>

          <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">Cập nhật</button>
            <a href="../review.php" class="btn btn-secondary">Hủy</a>
          </div>
        </form>

      </div>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
