<?php
require_once __DIR__ . '/../functions/bookings_functions.php';

// Kiểm tra action được truyền qua URL hoặc POST
$action = '';
if (isset($_GET['action'])) {
    $action = $_GET['action'];
} elseif (isset($_POST['action'])) {
    $action = $_POST['action'];
}

switch ($action) {
    case 'create':
        handleCreateBooking();
        break;
    case 'edit':
        handleEditBooking();
        break;
    case 'delete':
        handleDeleteBooking();
        break;
}

/**
 * Lấy tất cả danh sách đặt tour
 */
function handleGetAllBookings() {
    return getAllBookings();
}

function handleGetBookingById($id) {
    return getBookingById($id);
}

/**
 * Xử lý tạo đặt tour mới
 */
function handleCreateBooking() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: ../views/bookings.php?error=Phương thức không hợp lệ");
        exit();
    }
    if (
        !isset($_POST['user_id']) ||
        !isset($_POST['tour_id']) ||
        !isset($_POST['so_nguoi']) ||
        !isset($_POST['ngay_dat']) ||
        !isset($_POST['trang_thai'])
    ) {
        header("Location: ../views/bookings/create_bookings.php?error=Thiếu thông tin cần thiết");
        exit();
    }
    $user_id = trim($_POST['user_id']);
    $tour_id = trim($_POST['tour_id']);
    $so_nguoi = trim($_POST['so_nguoi']);
    $ngay_dat = trim($_POST['ngay_dat']);
    $trang_thai = trim($_POST['trang_thai']);

    if (
        empty($user_id) || empty($tour_id) || empty($so_nguoi) ||
        empty($ngay_dat) || empty($trang_thai)
    ) {
        header("Location: ../views/bookings/create_bookings.php?error=Vui lòng điền đầy đủ thông tin");
        exit();
    }

    $result = addBooking($user_id, $tour_id, $so_nguoi, $ngay_dat, $trang_thai);

    if ($result) {
        header("Location: ../views/bookings.php?success=Thêm đặt tour thành công");
    } else {
        header("Location: ../views/bookings/create_bookings.php?error=Có lỗi xảy ra khi thêm đặt tour");
    }
    exit();
}

/**
 * Xử lý chỉnh sửa đặt tour
 */
function handleEditBooking() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: ../views/bookings.php?error=Phương thức không hợp lệ");
        exit();
    }
    if (
        !isset($_POST['id']) ||
        !isset($_POST['user_id']) ||
        !isset($_POST['tour_id']) ||
        !isset($_POST['so_nguoi']) ||
        !isset($_POST['ngay_dat']) ||
        !isset($_POST['trang_thai'])
    ) {
        header("Location: ../views/bookings.php?error=Thiếu thông tin cần thiết");
        exit();
    }
    $id = $_POST['id'];
    $user_id = trim($_POST['user_id']);
    $tour_id = trim($_POST['tour_id']);
    $so_nguoi = trim($_POST['so_nguoi']);
    $ngay_dat = trim($_POST['ngay_dat']);
    $trang_thai = trim($_POST['trang_thai']);

    if (
        empty($user_id) || empty($tour_id) || empty($so_nguoi) ||
        empty($ngay_dat) || empty($trang_thai)
    ) {
        header("Location: ../views/bookings/edit_bookings.php?id=" . $id . "&error=Vui lòng điền đầy đủ thông tin");
        exit();
    }

    $result = updateBooking($id, $user_id, $tour_id, $so_nguoi, $ngay_dat, $trang_thai);

    if ($result) {
        header("Location: ../views/bookings.php?success=Cập nhật đặt tour thành công");
    } else {
        header("Location: ../views/bookings/edit_bookings.php?id=" . $id . "&error=Cập nhật đặt tour thất bại");
    }
    exit();
}

/**
 * Xử lý xóa đặt tour
 */
function handleDeleteBooking() {
    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        header("Location: ../views/bookings.php?error=Phương thức không hợp lệ");
        exit();
    }
    if (!isset($_GET['id']) || empty($_GET['id'])) {
        header("Location: ../views/bookings.php?error=Không tìm thấy ID đặt tour");
        exit();
    }
    $id = $_GET['id'];
    if (!is_numeric($id)) {
        header("Location: ../views/bookings.php?error=ID đặt tour không hợp lệ");
        exit();
    }
    $result = deleteBooking($id);

    if ($result) {
        header("Location: ../views/bookings.php?success=Xóa đặt tour thành công");
    } else {
        header("Location: ../views/bookings.php?error=Xóa đặt tour thất bại");
    }
    exit();
}
?>