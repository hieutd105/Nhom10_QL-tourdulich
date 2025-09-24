<?php
// functions/review_functions.php
require_once __DIR__ . '/db_connection.php';

/**
 * Đổi tên bảng review tại đây nếu bạn dùng tên khác.
 * Mặc định: 'reviews'
 */
const REVIEW_TABLE = 'reviews';

/**
 * Lấy toàn bộ danh sách reviews, join với tours và users.
 */
function getAllReviews() {
    $conn = getDbConnection();
    $sql = "
        SELECT r.id, r.tour_id, r.user_id, r.rating, r.comment, r.created_at,
               t.ten_tour, u.username
        FROM " . REVIEW_TABLE . " r
        JOIN tours t ON t.id = r.tour_id
        JOIN users u ON u.id = r.user_id
        ORDER BY r.id DESC
    ";
    $res = mysqli_query($conn, $sql);

    $rows = [];
    if ($res) {
        while ($row = mysqli_fetch_assoc($res)) {
            $rows[] = $row;
        }
        mysqli_free_result($res);
    } else {
        error_log('[getAllReviews] MySQL error: ' . mysqli_error($conn));
    }
    mysqli_close($conn);
    return $rows;
}

/**
 * Lấy 1 review theo id.
 */
function getReviewById($id) {
    $id = (int)$id;

    $conn = getDbConnection();
    $sql = "SELECT id, tour_id, user_id, rating, comment, created_at
            FROM " . REVIEW_TABLE . " WHERE id = ? LIMIT 1";
    $stmt = mysqli_prepare($conn, $sql);
    if (!$stmt) {
        error_log('[getReviewById] Prepare failed: ' . mysqli_error($conn));
        mysqli_close($conn);
        return null;
    }

    mysqli_stmt_bind_param($stmt, "i", $id);
    if (!mysqli_stmt_execute($stmt)) {
        error_log('[getReviewById] Execute failed: ' . mysqli_error($conn));
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        return null;
    }

    $res = mysqli_stmt_get_result($stmt);
    $row = $res ? mysqli_fetch_assoc($res) : null;

    if ($res) mysqli_free_result($res);
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    return $row;
}

/**
 * (Tiện ích) Lấy review theo tour.
 */
function getReviewsByTour($tour_id) {
    $tour_id = (int)$tour_id;

    $conn = getDbConnection();
    $sql = "
        SELECT r.id, r.tour_id, r.user_id, r.rating, r.comment, r.created_at,
               u.username
        FROM " . REVIEW_TABLE . " r
        JOIN users u ON u.id = r.user_id
        WHERE r.tour_id = ?
        ORDER BY r.created_at DESC
    ";
    $stmt = mysqli_prepare($conn, $sql);
    if (!$stmt) {
        error_log('[getReviewsByTour] Prepare failed: ' . mysqli_error($conn));
        mysqli_close($conn);
        return [];
    }

    mysqli_stmt_bind_param($stmt, "i", $tour_id);
    if (!mysqli_stmt_execute($stmt)) {
        error_log('[getReviewsByTour] Execute failed: ' . mysqli_error($conn));
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        return [];
    }

    $res = mysqli_stmt_get_result($stmt);
    $rows = [];
    if ($res) {
        while ($row = mysqli_fetch_assoc($res)) {
            $rows[] = $row;
        }
        mysqli_free_result($res);
    }
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    return $rows;
}

/**
 * Thêm review mới.
 */
function addReview($tour_id, $user_id, $rating, $comment) {
    $tour_id = (int)$tour_id;
    $user_id = (int)$user_id;
    $rating  = max(1, min(5, (int)$rating));
    $comment = (string)$comment;

    $conn = getDbConnection();
    $sql = "INSERT INTO " . REVIEW_TABLE . " (tour_id, user_id, rating, comment)
            VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    if (!$stmt) {
        error_log('[addReview] Prepare failed: ' . mysqli_error($conn));
        mysqli_close($conn);
        return false;
    }

    mysqli_stmt_bind_param($stmt, "iiis", $tour_id, $user_id, $rating, $comment);
    $ok = mysqli_stmt_execute($stmt);
    if (!$ok) {
        error_log('[addReview] Execute failed: ' . mysqli_error($conn));
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    return $ok;
}

/**
 * Cập nhật review.
 */
function updateReview($id, $tour_id, $user_id, $rating, $comment) {
    $id      = (int)$id;
    $tour_id = (int)$tour_id;
    $user_id = (int)$user_id;
    $rating  = max(1, min(5, (int)$rating));
    $comment = (string)$comment;

    $conn = getDbConnection();
    $sql = "UPDATE " . REVIEW_TABLE . "
            SET tour_id = ?, user_id = ?, rating = ?, comment = ?
            WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    if (!$stmt) {
        error_log('[updateReview] Prepare failed: ' . mysqli_error($conn));
        mysqli_close($conn);
        return false;
    }

    mysqli_stmt_bind_param($stmt, "iiisi", $tour_id, $user_id, $rating, $comment, $id);
    $ok = mysqli_stmt_execute($stmt);
    if (!$ok) {
        error_log('[updateReview] Execute failed: ' . mysqli_error($conn));
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    return $ok;
}

/**
 * Xóa review theo id.
 * 
 */
function deleteReview($id) {
    $id = (int)$id;

    $conn = getDbConnection();
    $sql = "DELETE FROM " . REVIEW_TABLE . " WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    if (!$stmt) {
        error_log('[deleteReview] Prepare failed: ' . mysqli_error($conn));
        mysqli_close($conn);
        return false;
    }

    mysqli_stmt_bind_param($stmt, "i", $id);
    $ok = mysqli_stmt_execute($stmt);
    if (!$ok) {
        error_log('[deleteReview] Execute failed: ' . mysqli_error($conn));
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    return $ok;
}
