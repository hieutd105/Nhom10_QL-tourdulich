<?php
require_once __DIR__ . '/../functions/tour_functions.php';

// Kiểm tra action được truyền qua URL hoặc POST
$action = '';
if (isset($_GET['action'])) {
    $action = $_GET['action'];
} elseif (isset($_POST['action'])) {
    $action = $_POST['action'];
}

switch ($action) {
    case 'create':
        handleCreateTour();
        break;
    case 'edit':
        handleEditTour();
        break;
    case 'delete':
        handleDeleteTour();
        break;
}

/**
 * Lấy tất cả danh sách tour
 */
function handleGetAllTours() {
    return getAllTours();
}

function handleGetTourById($id) {
    return getTourById($id);
}

/**
 * Xử lý tạo tour mới
 */
function handleCreateTour() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: ../views/tour.php?error=Phương thức không hợp lệ");
        exit();
    }

    if (
        !isset($_POST['ten_tour']) ||
        !isset($_POST['gia']) ||
        !isset($_POST['ngay_khoi_hanh']) ||
        !isset($_POST['so_cho']) ||
        !isset($_POST['mo_ta'])
    ) {
        header("Location: ../views/tour/create_tour.php?error=Thiếu thông tin cần thiết");
        exit();
    }

    $ten_tour = trim($_POST['ten_tour']);
    $gia = trim($_POST['gia']);
    $ngay_khoi_hanh = trim($_POST['ngay_khoi_hanh']);
    $so_cho = trim($_POST['so_cho']);
    $mo_ta = trim($_POST['mo_ta']);

    // Chuẩn hóa ngày
    if (!empty($ngay_khoi_hanh)) {
        $ngay_khoi_hanh = date('Y-m-d', strtotime($ngay_khoi_hanh));
    }

    // Validate dữ liệu
    if (empty($ten_tour) || empty($gia) || empty($ngay_khoi_hanh) || empty($so_cho) || empty($mo_ta)) {
        header("Location: ../views/tour/create_tour.php?error=Vui lòng điền đầy đủ thông tin");
        exit();
    }
    if (!is_numeric($gia) || $gia < 0) {
        header("Location: ../views/tour/create_tour.php?error=Giá phải là số dương");
        exit();
    }
    if (!is_numeric($so_cho) || $so_cho < 1) {
        header("Location: ../views/tour/create_tour.php?error=Số chỗ phải là số nguyên dương");
        exit();
    }

    $result = addTour($ten_tour, $gia, $ngay_khoi_hanh, $so_cho, $mo_ta);

    if ($result) {
        header("Location: ../views/tour.php?success=Thêm tour thành công");
    } else {
        header("Location: ../views/tour/create_tour.php?error=Có lỗi xảy ra khi thêm tour");
    }
    exit();
}

/**
 * Xử lý chỉnh sửa tour
 */
function handleEditTour() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: ../views/tour.php?error=Phương thức không hợp lệ");
        exit();
    }

    if (
        !isset($_POST['id']) ||
        !isset($_POST['ten_tour']) ||
        !isset($_POST['gia']) ||
        !isset($_POST['ngay_khoi_hanh']) ||
        !isset($_POST['so_cho']) ||
        !isset($_POST['mo_ta'])
    ) {
        header("Location: ../views/tour.php?error=Thiếu thông tin cần thiết");
        exit();
    }

    $id = $_POST['id'];
    $ten_tour = trim($_POST['ten_tour']);
    $gia = trim($_POST['gia']);
    $ngay_khoi_hanh = trim($_POST['ngay_khoi_hanh']);
    $so_cho = trim($_POST['so_cho']);
    $mo_ta = trim($_POST['mo_ta']);

    // Chuẩn hóa ngày
    if (!empty($ngay_khoi_hanh)) {
        $ngay_khoi_hanh = date('Y-m-d', strtotime($ngay_khoi_hanh));
    }

    // Validate dữ liệu
    if (empty($ten_tour) || empty($gia) || empty($ngay_khoi_hanh) || empty($so_cho) || empty($mo_ta)) {
        header("Location: ../views/tour/edit_tour.php?id=" . $id . "&error=Vui lòng điền đầy đủ thông tin");
        exit();
    }
    if (!is_numeric($gia) || $gia < 0) {
        header("Location: ../views/tour/edit_tour.php?id=" . $id . "&error=Giá phải là số dương");
        exit();
    }
    if (!is_numeric($so_cho) || $so_cho < 1) {
        header("Location: ../views/tour/edit_tour.php?id=" . $id . "&error=Số chỗ phải là số nguyên dương");
        exit();
    }

    $result = updateTour($id, $ten_tour, $gia, $ngay_khoi_hanh, $so_cho, $mo_ta);

    if ($result) {
        header("Location: ../views/tour.php?success=Cập nhật tour thành công");
    } else {
        header("Location: ../views/tour/edit_tour.php?id=" . $id . "&error=Cập nhật tour thất bại");
    }
    exit();
}

/**
 * Xử lý xóa tour
 */
function handleDeleteTour() {
    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        header("Location: ../views/tour.php?error=Phương thức không hợp lệ");
        exit();
    }

    if (!isset($_GET['id']) || empty($_GET['id'])) {
        header("Location: ../views/tour.php?error=Không tìm thấy ID tour");
        exit();
    }

    $id = $_GET['id'];

    // Validate ID là số
    if (!is_numeric($id)) {
        header("Location: ../views/tour.php?error=ID tour không hợp lệ");
        exit();
    }

    $result = deleteTour($id);

    if ($result) {
        header("Location: ../views/tour.php?success=Xóa tour thành công");
    } else {
        header("Location: ../views/tour.php?error=Xóa tour thất bại");
    }
    exit();
}
?>
