<?php
session_start();

// Đường dẫn đã được sửa
include '../functions/db_connection.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register'])) {
    
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    // Bỏ qua mã hóa mật khẩu và lấy trực tiếp từ form
    $password = trim($_POST['password']); 

    if (empty($username) || empty($email) || empty($password)) {
        $_SESSION['error'] = 'Vui lòng điền đầy đủ thông tin.';
        header('Location: ../register.php');
        exit();
    }

    $conn = getDbConnection();

    // Kiểm tra trùng tên đăng nhập
    $sql_check_user = "SELECT id FROM users WHERE username = ?";
    $stmt_check = mysqli_prepare($conn, $sql_check_user);

    if ($stmt_check === false) {
        die('Lỗi chuẩn bị câu lệnh kiểm tra: ' . mysqli_error($conn));
    }

    mysqli_stmt_bind_param($stmt_check, "s", $username);
    mysqli_stmt_execute($stmt_check);
    mysqli_stmt_store_result($stmt_check);
    
    if (mysqli_stmt_num_rows($stmt_check) > 0) {
        $_SESSION['error'] = 'Tên đăng nhập đã tồn tại. Vui lòng chọn tên khác.';
        mysqli_stmt_close($stmt_check);
        mysqli_close($conn);
        header('Location: ../register.php');
        exit();
    }
    mysqli_stmt_close($stmt_check);

    // Chèn người dùng mới vào database mà không mã hóa mật khẩu
    $sql_insert_user = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt_insert = mysqli_prepare($conn, $sql_insert_user);

    if ($stmt_insert === false) {
        die('Lỗi chuẩn bị câu lệnh chèn: ' . mysqli_error($conn));
    }

    // Gán biến mật khẩu gốc vào câu lệnh
    mysqli_stmt_bind_param($stmt_insert, "sss", $username, $email, $password);

    if (mysqli_stmt_execute($stmt_insert)) {
        $_SESSION['success'] = 'Đăng ký tài khoản thành công! Bây giờ bạn có thể đăng nhập.';
        header('Location: ../index.php');
    } else {
        $_SESSION['error'] = 'Đã có lỗi xảy ra. Vui lòng thử lại.';
        header('Location: ../register.php');
    }
    
    mysqli_stmt_close($stmt_insert);
    mysqli_close($conn);
    exit();
}
?>