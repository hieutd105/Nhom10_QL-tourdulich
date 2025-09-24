<?php
require_once __DIR__ . '/../functions/transport_functions.php';

// Kiểm tra action
$action = '';
if (isset($_GET['action'])) {
    $action = $_GET['action'];
} elseif (isset($_POST['action'])) {
    $action = $_POST['action'];
}

switch ($action) {
    case 'create':
        handleCreateTransport();
        break;
    case 'edit':
        handleEditTransport();
        break;
    case 'delete':
        handleDeleteTransport();
        break;
}

/**
 * Lấy tất cả danh sách nhà xe
 */
function handleGetAllTransports() {
    return getAllTransports();
}

function handleGetTransportById($id) {
    return getTransportById($id);
}

/**
 * Xử lý tạo mới nhà xe
 */
function handleCreateTransport() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: ../views/transport.php?error=Phương thức không hợp lệ");
        exit();
    }

    if (
        !isset($_POST['company_name']) ||
        !isset($_POST['vehicle_type']) ||
        !isset($_POST['seats']) ||
        !isset($_POST['license_plate']) ||
        !isset($_POST['route_from']) ||
        !isset($_POST['route_to']) ||
        !isset($_POST['price_per_trip']) ||
        !isset($_POST['phone']) ||
        !isset($_POST['status'])
    ) {
        header("Location: ../views/transport_form.php?error=Thiếu thông tin cần thiết");
        exit();
    }

    $data = [
        'company_name'   => trim($_POST['company_name']),
        'vehicle_type'   => trim($_POST['vehicle_type']),
        'seats'          => (int)$_POST['seats'],
        'license_plate'  => trim($_POST['license_plate']),
        'route_from'     => trim($_POST['route_from']),
        'route_to'       => trim($_POST['route_to']),
        'price_per_trip' => (float)$_POST['price_per_trip'],
        'phone'          => trim($_POST['phone']),
        'description'    => trim($_POST['description'] ?? ''),
        'image'          => null,
        'status'         => trim($_POST['status'])
    ];

    // Upload ảnh nếu có
    if (!empty($_FILES['image']['name'])) {
        $data['image'] = save_image_tp('image');
    }

    $result = transport_create($data);

    if ($result) {
        header("Location: ../views/transport.php?success=Thêm nhà xe thành công");
    } else {
        header("Location: ../views/transport_form.php?error=Thêm nhà xe thất bại");
    }
    exit();
}

/**
 * Xử lý chỉnh sửa nhà xe
 */
function handleEditTransport() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: ../views/transport.php?error=Phương thức không hợp lệ");
        exit();
    }
    if (!isset($_POST['id'])) {
        header("Location: ../views/transport.php?error=Thiếu ID nhà xe");
        exit();
    }

    $id = (int)$_POST['id'];
    $current = getTransportById($id);
    if (!$current) {
        header("Location: ../views/transport.php?error=Không tìm thấy nhà xe");
        exit();
    }

    $data = [
        'company_name'   => trim($_POST['company_name']),
        'vehicle_type'   => trim($_POST['vehicle_type']),
        'seats'          => (int)$_POST['seats'],
        'license_plate'  => trim($_POST['license_plate']),
        'route_from'     => trim($_POST['route_from']),
        'route_to'       => trim($_POST['route_to']),
        'price_per_trip' => (float)$_POST['price_per_trip'],
        'phone'          => trim($_POST['phone']),
        'description'    => trim($_POST['description'] ?? ''),
        'image'          => $current['image'],
        'status'         => trim($_POST['status'])
    ];

    // Upload ảnh mới nếu có
    if (!empty($_FILES['image']['name'])) {
        $newImg = save_image_tp('image');
        if ($newImg) {
            $data['image'] = $newImg;
        }
    }

    $result = transport_update($id, $data);

    if ($result) {
        header("Location: ../views/transport.php?success=Cập nhật nhà xe thành công");
    } else {
        header("Location: ../views/transport_form.php?id=$id&error=Cập nhật nhà xe thất bại");
    }
    exit();
}

/**
 * Xử lý xóa nhà xe
 */
function handleDeleteTransport() {
    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        header("Location: ../views/transport.php?error=Phương thức không hợp lệ");
        exit();
    }
    if (!isset($_GET['id']) || empty($_GET['id'])) {
        header("Location: ../views/transport.php?error=Không tìm thấy ID nhà xe");
        exit();
    }
    $id = (int)$_GET['id'];

    $result = transport_delete($id);

    if ($result) {
        header("Location: ../views/transport.php?success=Xóa nhà xe thành công");
    } else {
        header("Location: ../views/transport.php?error=Xóa nhà xe thất bại");
    }
    exit();
}

/**
 * Hàm upload ảnh riêng cho transport
 */
function save_image_tp($fieldName) {
    if (!isset($_FILES[$fieldName]) || $_FILES[$fieldName]['error'] !== UPLOAD_ERR_OK) {
        return null;
    }
    $allowed = ['image/jpeg','image/png','image/webp'];
    if (!in_array(mime_content_type($_FILES[$fieldName]['tmp_name']), $allowed)) {
        return null;
    }
    $dir = __DIR__ . '/../images/transports/';
    if (!is_dir($dir)) { mkdir($dir, 0777, true); }
    $ext = pathinfo($_FILES[$fieldName]['name'], PATHINFO_EXTENSION);
    $fname = time() . '_' . bin2hex(random_bytes(4)) . '.' . strtolower($ext);
    $dest = $dir . $fname;
    if (move_uploaded_file($_FILES[$fieldName]['tmp_name'], $dest)) {
        return 'images/transports/' . $fname;
    }
    return null;
}
?>
