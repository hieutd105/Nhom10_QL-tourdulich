<?php
require_once __DIR__ . '/../../functions/auth.php';
require_once __DIR__ . '/../../functions/db_connection.php';
require_once __DIR__ . '/../../functions/review_functions.php';
checkLogin(__DIR__ . '/../../index.php');

$conn = getDbConnection();
$users = $conn->query("SELECT id, username FROM users ORDER BY id")->fetch_all(MYSQLI_ASSOC);
$tours = $conn->query("SELECT id, ten_tour FROM tours ORDER BY id")->fetch_all(MYSQLI_ASSOC);

$user_id = $tour_id = $rating = $comment = '';
$success_msg = $error_msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tour_id = (int)($_POST['tour_id'] ?? 0);
    $user_id = (int)($_POST['user_id'] ?? 0);
    $rating  = (int)($_POST['rating'] ?? 0);
    $comment = trim($_POST['comment'] ?? '');

    if ($tour_id && $user_id && $rating >= 1 && $rating <= 5) {
        if (addReview($tour_id, $user_id, $rating, $comment)) {
            header('Location: ../review.php?msg=created');
            exit;
        } else {
            $error_msg = 'Lỗi khi thêm đánh giá.';
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
  <title>Thêm đánh giá tour</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap + font -->
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
      box-shadow: 0 8px 32px 0 rgba(31,38,135,0.10);
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
      width: 34px; height: 34px; border-radius: 50%;
      object-fit: cover;
      box-shadow: 0 2px 8px rgba(37,99,235,0.10);
    }
    .btn-primary {
      background: linear-gradient(90deg, #2563eb 60%, #60a5fa 100%);
      border: none; font-weight: 600; font-size: 1rem;
      border-radius: 10px;
      box-shadow: 0 2px 8px rgba(37,99,235,0.08);
    }
    .btn-primary:hover { background: linear-gradient(90deg, #1d4ed8 60%, #38bdf8 100%); }
    .btn-secondary { border-radius: 10px; font-weight: 600; }
  </style>
</head>
<body>
  <div class="overlay-bg"></div>

  <div class="container py-5">
    <div class="row justify-content-center">
      <div class="col-lg-7">
        <div class="card p-4">
          <h3 class="mb-4 d-flex align-items-center gap-2 text-primary">
            <img src="https://cdn-icons-png.flaticon.com/512/3206/3206926.png" 
                alt="review icon" 
                class="header-icon"
                style="width:42px;height:42px;border-radius:50%;object-fit:cover;">
            THÊM ĐÁNH GIÁ TOUR
          </h3>


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

          <form method="POST">
            <div class="mb-3">
              <label class="form-label">Tour</label>
              <select name="tour_id" class="form-control" required>
                <option value="">-- Chọn tour --</option>
                <?php foreach ($tours as $t): ?>
                  <option value="<?= $t['id'] ?>" <?= ($tour_id==$t['id'])?'selected':'' ?>>
                    <?= htmlspecialchars($t['ten_tour']) ?> (ID: <?= $t['id'] ?>)
                  </option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="mb-3">
              <label class="form-label">Người dùng</label>
              <select name="user_id" class="form-control" required>
                <option value="">-- Chọn người dùng --</option>
                <?php foreach ($users as $u): ?>
                  <option value="<?= $u['id'] ?>" <?= ($user_id==$u['id'])?'selected':'' ?>>
                    <?= htmlspecialchars($u['username']) ?> (ID: <?= $u['id'] ?>)
                  </option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="mb-3">
              <label class="form-label">Rating (1-5)</label>
              <input type="number" name="rating" class="form-control" min="1" max="5" value="<?= htmlspecialchars($rating) ?>" required>
            </div>

            <div class="mb-3">
              <label class="form-label">Bình luận</label>
              <textarea name="comment" class="form-control" rows="4" placeholder="Nhập đánh giá chi tiết..."><?= htmlspecialchars($comment) ?></textarea>
            </div>

            <div class="d-flex gap-2">
              <button type="submit" class="btn btn-primary px-4">Lưu</button>
              <a href="../review.php" class="btn btn-secondary px-4">Hủy</a>
            </div>
          </form>

        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
