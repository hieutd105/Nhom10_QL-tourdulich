<?php
// handle/hotel_process.php
require_once __DIR__ . '/../functions/hotel_functions.php';

// Lấy action
$action = $_GET['action'] ?? $_POST['action'] ?? '';

switch ($action) {
    case 'create':
        handleCreateHotel();
        break;
    case 'edit':
        handleEditHotel();
        break;
    case 'delete':
        handleDeleteHotel();
        break;
    default:
        header("Location: ../views/hotel.php");
        exit();
}

/**
 * Tạo khách sạn
 * Yêu cầu các field: name, city, address, stars, price_per_night, phone, description, status
 * Ảnh: input name="image" (tùy chọn). Lưu tại /images/img_hotel/
 */
function handleCreateHotel() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: ../views/hotel.php?error=Phương thức không hợp lệ");
        exit();
    }

    // Lấy dữ liệu
    $name            = trim($_POST['name'] ?? '');
    $city            = trim($_POST['city'] ?? '');
    $address         = trim($_POST['address'] ?? '');
    $stars           = (int)($_POST['stars'] ?? 0);
    $price_per_night = (float)($_POST['price_per_night'] ?? 0);
    $phone           = trim($_POST['phone'] ?? '');
    $description     = trim($_POST['description'] ?? '');
    $status          = isset($_POST['status']) ? (int)$_POST['status'] : 1; // 1=active, 0=inactive

    // Validate cơ bản
    if ($name === '' || $city === '' || $address === '' || $phone === '' || $price_per_night <= 0) {
        header("Location: ../views/hotel/create_hotel.php?error=Vui lòng điền đầy đủ thông tin");
        exit();
    }

    // Upload ảnh (tùy chọn)
    $image = '';
    if (!empty($_FILES['image']['name'])) {
        $upload = saveUploadImage($_FILES['image']);
        if ($upload['ok']) {
            $image = $upload['path']; // Ví dụ: images/img_hotel/1694000000_abc.jpg
        } else {
            header("Location: ../views/hotel/create_hotel.php?error=" . urlencode($upload['error']));
            exit();
        }
    }

    // Gọi hàm thêm
    $ok = addHotel($name, $city, $address, $stars, $price_per_night, $phone, $image, $description, $status);

    if ($ok) {
        header("Location: ../views/hotel.php?success=Thêm khách sạn thành công");
    } else {
        header("Location: ../views/hotel/create_hotel.php?error=Không thể thêm khách sạn");
    }
    exit();
}

/**
 * Cập nhật khách sạn
 * Yêu cầu id và các field như create. Ảnh mới nếu có, nếu không sẽ giữ ảnh cũ.
 * Form nên có input hidden name="existing_image" mang đường dẫn cũ.
 */
function handleEditHotel() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: ../views/hotel.php?error=Phương thức không hợp lệ");
        exit();
    }

    // Lấy dữ liệu
    $id              = (int)($_POST['id'] ?? 0);
    $name            = trim($_POST['name'] ?? '');
    $city            = trim($_POST['city'] ?? '');
    $address         = trim($_POST['address'] ?? '');
    $stars           = (int)($_POST['stars'] ?? 0);
    $price_per_night = (float)($_POST['price_per_night'] ?? 0);
    $phone           = trim($_POST['phone'] ?? '');
    $description     = trim($_POST['description'] ?? '');
    $status          = isset($_POST['status']) ? (int)$_POST['status'] : 1;
    $existing_image  = trim($_POST['existing_image'] ?? '');

    if ($id <= 0) {
        header("Location: ../views/hotel.php?error=Thiếu ID khách sạn");
        exit();
    }

    if ($name === '' || $city === '' || $address === '' || $phone === '' || $price_per_night <= 0) {
        header("Location: ../views/hotel/edit_hotel.php?id={$id}&error=Vui lòng điền đầy đủ thông tin");
        exit();
    }

    // Ảnh: nếu có upload mới thì thay, ngược lại dùng ảnh cũ
    $image = $existing_image;
    if (!empty($_FILES['image']['name'])) {
        $upload = saveUploadImage($_FILES['image']);
        if ($upload['ok']) {
            $image = $upload['path'];
        } else {
            header("Location: ../views/hotel/edit_hotel.php?id={$id}&error=" . urlencode($upload['error']));
            exit();
        }
    }

    $ok = updateHotel($id, $name, $city, $address, $stars, $price_per_night, $phone, $image, $description, $status);

    if ($ok) {
        header("Location: ../views/hotel.php?success=Cập nhật khách sạn thành công");
    } else {
        header("Location: ../views/hotel/edit_hotel.php?id={$id}&error=Cập nhật khách sạn thất bại");
    }
    exit();
}

/**
 * Xóa khách sạn
 */
function handleDeleteHotel() {
    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        header("Location: ../views/hotel.php?error=Phương thức không hợp lệ");
        exit();
    }

    $id = (int)($_GET['id'] ?? 0);
    if ($id <= 0) {
        header("Location: ../views/hotel.php?error=Không tìm thấy ID khách sạn");
        exit();
    }

    $ok = deleteHotel($id);
    if ($ok) {
        header("Location: ../views/hotel.php?success=Xóa khách sạn thành công");
    } else {
        header("Location: ../views/hotel.php?error=Xóa khách sạn thất bại");
    }
    exit();
}

/**
 * Lưu ảnh upload vào /images/img_hotel/ và trả về đường dẫn tương đối để lưu DB
 * @param array $file $_FILES['image']
 * @return array ['ok'=>bool, 'path'=>string, 'error'=>string]
 */
function saveUploadImage(array $file): array {
    if (!isset($file['tmp_name']) || $file['error'] !== UPLOAD_ERR_OK) {
        return ['ok' => false, 'path' => '', 'error' => 'Upload ảnh thất bại'];
    }
    // Thư mục public (tính từ gốc project): /images/img_hotel/
    $publicDir = __DIR__ . '/../images/img_hotel/';
    if (!is_dir($publicDir)) {
        if (!mkdir($publicDir, 0777, true)) {
            return ['ok' => false, 'path' => '', 'error' => 'Không tạo được thư mục ảnh'];
        }
    }

    $basename = time() . '_' . preg_replace('/[^A-Za-z0-9_\.-]/', '_', basename($file['name']));
    $targetFs = $publicDir . $basename;

    if (!move_uploaded_file($file['tmp_name'], $targetFs)) {
        return ['ok' => false, 'path' => '', 'error' => 'Không thể lưu file ảnh'];
    }

    // Đường dẫn tương đối để render trong web/DB
    $publicPath = 'images/img_hotel/' . $basename;
    return ['ok' => true, 'path' => $publicPath, 'error' => ''];
}
