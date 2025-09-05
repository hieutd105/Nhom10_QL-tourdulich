<?php
require_once 'db_connection.php';

/**
 * Lấy tất cả danh sách đặt tour từ database
 * @return array Danh sách đặt tour
 */
function getAllBookings() {
    $conn = getDbConnection();
    $sql = "SELECT id, user_id, tour_id, so_nguoi, ngay_dat, trang_thai FROM bookings ORDER BY id";
    $result = mysqli_query($conn, $sql);

    $bookings = [];
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $bookings[] = $row;
        }
    }
    mysqli_close($conn);
    return $bookings;
}

/**
 * Thêm đặt tour mới
 * @param int $user_id
 * @param int $tour_id
 * @param int $so_nguoi
 * @param string $ngay_dat
 * @param string $trang_thai
 * @return bool True nếu thành công, False nếu thất bại
 */
function addBooking($user_id, $tour_id, $so_nguoi, $ngay_dat, $trang_thai) {
    $conn = getDbConnection();
    $sql = "INSERT INTO bookings (user_id, tour_id, so_nguoi, ngay_dat, trang_thai) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "iiiss", $user_id, $tour_id, $so_nguoi, $ngay_dat, $trang_thai);
        $success = mysqli_stmt_execute($stmt);

        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        return $success;
    }

    mysqli_close($conn);
    return false;
}

/**
 * Lấy thông tin một đặt tour theo ID
 * @param int $id
 * @return array|null
 */
function getBookingById($id) {
    $conn = getDbConnection();
    $sql = "SELECT id, user_id, tour_id, so_nguoi, ngay_dat, trang_thai FROM bookings WHERE id = ? LIMIT 1";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($result && mysqli_num_rows($result) > 0) {
            $booking = mysqli_fetch_assoc($result);
            mysqli_stmt_close($stmt);
            mysqli_close($conn);
            return $booking;
        }
        mysqli_stmt_close($stmt);
    }

    mysqli_close($conn);
    return null;
}

/**
 * Cập nhật thông tin đặt tour
 * @param int $id
 * @param int $user_id
 * @param int $tour_id
 * @param int $so_nguoi
 * @param string $ngay_dat
 * @param string $trang_thai
 * @return bool
 */
function updateBooking($id, $user_id, $tour_id, $so_nguoi, $ngay_dat, $trang_thai) {
    $conn = getDbConnection();
    $sql = "UPDATE bookings SET user_id = ?, tour_id = ?, so_nguoi = ?, ngay_dat = ?, trang_thai = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "iiissi", $user_id, $tour_id, $so_nguoi, $ngay_dat, $trang_thai, $id);
        $success = mysqli_stmt_execute($stmt);

        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        return $success;
    }

    mysqli_close($conn);
    return false;
}

/**
 * Xóa đặt tour theo ID
 * @param int $id
 * @return bool
 */
function deleteBooking($id) {
    $conn = getDbConnection();
    $sql = "DELETE FROM bookings WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $id);
        $success = mysqli_stmt_execute($stmt);

        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        return $success;
    }

    mysqli_close($conn);
    return false;
}
?>