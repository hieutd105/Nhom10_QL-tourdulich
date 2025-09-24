<?php
require_once 'db_connection.php';

/**
 * Lưu thanh toán mới (trạng thái mặc định: pending)
 */
function luuThanhToan($user_id, $tour_id, $so_tien, $phuong_thuc = 'QR') {
    $conn = getDbConnection();

    // Ép kiểu an toàn
    $user_id   = (int)$user_id;
    $tour_id   = (int)$tour_id;
    $so_tien   = (float)$so_tien; // DECIMAL trong DB, bind 'd' ok
    $phuong_thuc = (string)$phuong_thuc;

    $sql = "INSERT INTO payments (user_id, tour_id, so_tien, phuong_thuc, trang_thai, ngay_tao)
            VALUES (?, ?, ?, ?, 'pending', NOW())";

    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        // Báo lỗi rõ ràng để bạn biết sai cột/ bảng
        die("SQL prepare failed: " . $conn->error . " | SQL: " . $sql);
    }

    if (!$stmt->bind_param("iids", $user_id, $tour_id, $so_tien, $phuong_thuc)) {
        die("bind_param failed: " . $stmt->error);
    }

    if (!$stmt->execute()) {
        die("execute failed: " . $stmt->error);
    }

    $stmt->close();
    $conn->close();
}

/**
 * Lấy tất cả thanh toán (JOIN để lấy tên tour)
 */
function getAllPayments() {
    $conn = getDbConnection();
    $sql = "SELECT p.id, t.ten_tour, p.so_tien, p.phuong_thuc, p.trang_thai, p.ngay_tao
            FROM payments p
            JOIN tours t ON p.tour_id = t.id
            ORDER BY p.ngay_tao DESC";
    $result = $conn->query($sql);
    if ($result === false) {
        die("Query failed: " . $conn->error);
    }
    return $result; // dùng while fetch_assoc trong view
}

/**
 * Admin duyệt thanh toán (pending -> paid)
 */
function approvePayment($id) {
    $conn = getDbConnection();
    $id = (int)$id;
    $sql = "UPDATE payments SET trang_thai='paid', ngay_cap_nhat=NOW() WHERE id=?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("SQL prepare failed: " . $conn->error . " | SQL: " . $sql);
    }
    if (!$stmt->bind_param("i", $id)) {
        die("bind_param failed: " . $stmt->error);
    }
    if (!$stmt->execute()) {
        die("execute failed: " . $stmt->error);
    }
    $stmt->close();
    $conn->close();
}

/**
 * Xóa thanh toán
 */
function deletePayment($id) {
    $conn = getDbConnection();
    $id = (int)$id;
    $sql = "DELETE FROM payments WHERE id=?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("SQL prepare failed: " . $conn->error . " | SQL: " . $sql);
    }
    if (!$stmt->bind_param("i", $id)) {
        die("bind_param failed: " . $stmt->error);
    }
    if (!$stmt->execute()) {
        die("execute failed: " . $stmt->error);
    }
    $stmt->close();
    $conn->close();
}
