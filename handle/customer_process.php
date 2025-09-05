<?php

require_once __DIR__ . '/../functions/customer_functions.php';

// Kiểm tra action được truyền qua URL hoặc POST
$action = '';
if (isset($_GET['action'])) {
    $action = $_GET['action'];
} elseif (isset($_POST['action'])) {
    $action = $_POST['action'];
}

switch ($action) {
    case 'create':
        handleCreateCustomer();
        break;
    case 'edit':
        handleEditCustomer();
        break;
    case 'delete':
        handleDeleteCustomer();
        break;
}

/**
 * Lấy tất cả danh sách khách hàng
 */
function handleGetAllCustomers() {
    return getAllCustomers();
}

function handleGetCustomerById($id) {
    return getCustomerById($id);
}

/**
 * Xử lý tạo khách hàng mới
 */
function handleCreateCustomer() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: ../views/customer.php?error=Phương thức không hợp lệ");
        exit();
    }
    if (
        !isset($_POST['ho_ten']) ||
        !isset($_POST['email']) ||
        !isset($_POST['sdt'])
    ) {
        header("Location: ../views/customer/create_customer.php?error=Thiếu thông tin cần thiết");
        exit();
    }
    $ho_ten = trim($_POST['ho_ten']);
    $email = trim($_POST['email']);
    $sdt = trim($_POST['sdt']);

    if (empty($ho_ten) || empty($email) || empty($sdt)) {
        header("Location: ../views/customer/create_customer.php?error=Vui lòng điền đầy đủ thông tin");
        exit();
    }

    $result = addCustomer($ho_ten, $email, $sdt);

    if ($result) {
        header("Location: ../views/customer.php?success=Thêm khách hàng thành công");
    } else {
        header("Location: ../views/customer/create_customer.php?error=Có lỗi xảy ra khi thêm khách hàng");
    }
    exit();
}

/**
 * Xử lý chỉnh sửa khách hàng
 */
function handleEditCustomer() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: ../views/customer.php?error=Phương thức không hợp lệ");
        exit();
    }
    if (
        !isset($_POST['id']) ||
        !isset($_POST['ho_ten']) ||
        !isset($_POST['email']) ||
        !isset($_POST['sdt'])
    ) {
        header("Location: ../views/customer.php?error=Thiếu thông tin cần thiết");
        exit();
    }
    $id = $_POST['id'];
    $ho_ten = trim($_POST['ho_ten']);
    $email = trim($_POST['email']);
    $sdt = trim($_POST['sdt']);

    if (empty($ho_ten) || empty($email) || empty($sdt)) {
        header("Location: ../views/customer/edit_customer.php?id=" . $id . "&error=Vui lòng điền đầy đủ thông tin");
        exit();
    }

    $result = updateCustomer($id, $ho_ten, $email, $sdt);

    if ($result) {
        header("Location: ../views/customer.php?success=Cập nhật khách hàng thành công");
    } else {
        header("Location: ../views/customer/edit_customer.php?id=" . $id . "&error=Cập nhật khách hàng thất bại");
    }
    exit();
}

/**
 * Xử lý xóa khách hàng
 */
function handleDeleteCustomer() {
    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        header("Location: ../views/customer.php?error=Phương thức không hợp lệ");
        exit();
    }
    if (!isset($_GET['id']) || empty($_GET['id'])) {
        header("Location: ../views/customer.php?error=Không tìm thấy ID khách hàng");
        exit();
    }
    $id = $_GET['id'];
    if (!is_numeric($id)) {
        header("Location: ../views/customer.php?error=ID khách hàng không hợp lệ");
        exit();
    }
    $result = deleteCustomer($id);

    if ($result) {
        header("Location: ../views/customer.php?success=Xóa khách hàng thành công");
    } else {
        header("Location: ../views/customer.php?error=Xóa khách hàng thất bại");
    }
    exit();
}
?>