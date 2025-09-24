<?php
/**
 * ======================
 *  HÀM XÁC THỰC & PHÂN QUYỀN
 * ======================
 */

/**
 * Hàm kiểm tra xem user đã đăng nhập chưa
 * Nếu chưa đăng nhập, chuyển hướng về trang login
 * 
 * @param string $redirectPath Đường dẫn để chuyển hướng về trang login (mặc định: '../index.php')
 */
function checkLogin($redirectPath = '../index.php') {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    if (!isset($_SESSION['user_id']) || !isset($_SESSION['username'])) {
        $_SESSION['error'] = 'Bạn cần đăng nhập để truy cập trang này!';
        header('Location: ' . $redirectPath);
        exit();
    }
}

/**
 * Hàm đăng xuất user
 * Xóa tất cả session và chuyển hướng về trang login
 * 
 * @param string $redirectPath Đường dẫn để chuyển hướng sau khi logout (mặc định: '../index.php')
 */
function logout($redirectPath = '../index.php') {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    session_unset();
    session_destroy();
    
    session_start();
    $_SESSION['success'] = 'Đăng xuất thành công!';
    
    header('Location: ' . $redirectPath);
    exit();
}

/**
 * Hàm lấy thông tin user hiện tại
 * 
 * @return array|null
 */
function getCurrentUser() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    if (isset($_SESSION['user_id']) && isset($_SESSION['username'])) {
        return [
            'id' => $_SESSION['user_id'],
            'username' => $_SESSION['username'],
            'role' => $_SESSION['role'] ?? null
        ];
    }
    return null;
}

/**
 * Hàm kiểm tra đã đăng nhập (boolean)
 */
function isLoggedIn() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    return isset($_SESSION['user_id']) && isset($_SESSION['username']);
}

/**
 * Xác thực đăng nhập từ DB
 * 
 * @param mysqli $conn
 * @param string $username
 * @param string $password
 * @return array|false
 */
function authenticateUser($conn, $username, $password) {
    $sql = "SELECT id, username, password, role FROM users WHERE username = ? LIMIT 1";
    $stmt = mysqli_prepare($conn, $sql);
    if (!$stmt) return false;

    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        // ⚠️ Nếu password có hash -> thay bằng password_verify($password, $user['password'])
        if ($password === $user['password']) {
            mysqli_stmt_close($stmt);
            return $user;
        }
    }

    if ($stmt) mysqli_stmt_close($stmt);
    return false;
}

/* ==================== PHÂN QUYỀN ==================== */

/**
 * Kiểm tra user có phải admin không
 */
function isAdmin() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

/**
 * Bắt buộc là admin
 * Nếu không, chặn và chuyển hướng
 * 
 * @param string $redirectPath
 */
function requireAdmin($redirectPath = '../index.php') {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    if (!isAdmin()) {
        $_SESSION['error'] = 'Bạn không có quyền truy cập chức năng này!';
        header("Location: " . $redirectPath);
        exit();
    }
}
