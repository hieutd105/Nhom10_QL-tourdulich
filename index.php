<?php
session_start();
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập - FITDNU</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Các hiệu ứng CSS để làm cho trang sống động hơn */
        :root {
            --primary-color: #007bff;
            --secondary-color: #6c757d;
            --card-bg-color: rgba(255, 255, 255, 0.9);
            --border-radius: 15px;
            --box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background-image: url('./images/travel-bg.jpg'); /* Đặt ảnh nền ở đây */
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            position: relative;
        }

        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5); /* Lớp phủ mờ */
            backdrop-filter: blur(5px); /* Hiệu ứng làm mờ nền */
            z-index: 1;
        }
        
        .login-container {
            position: relative;
            z-index: 2;
        }

        .login-card {
            background: var(--card-bg-color);
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            animation: fadeInScale 0.8s ease-in-out;
            border: none;
        }

        @keyframes fadeInScale {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }

        .card-body {
            padding: 2.5rem;
        }

        .card-title {
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }

        .form-floating .form-control {
            border-radius: 50px;
            padding: 1rem 1.5rem;
            background-color: #f8f9fa;
            border: 1px solid #ddd;
            transition: all 0.3s ease;
        }

        .form-floating .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        .form-floating > label {
            padding: 1rem 1.5rem;
        }

        .btn-primary {
            background-color: var(--primary-color) !important;
            border-color: var(--primary-color) !important;
            border-radius: 50px;
            padding: 0.75rem 2.5rem;
            font-size: 1.1rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #0056b3 !important;
            border-color: #0056b3 !important;
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 123, 255, 0.3);
        }

        .alert {
            border-radius: 10px;
            animation: shake 0.5s;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
            20%, 40%, 60%, 80% { transform: translateX(5px); }
        }

        .register-link a {
            color: var(--primary-color);
            font-weight: 600;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .register-link a:hover {
            color: #0056b3;
            text-decoration: underline;
        }
    </style>
</head>

<body class="bg-tour">
    <div class="container login-container">
        <div class="row d-flex justify-content-center align-items-center">
            <div class="col-md-8 col-lg-6 col-xl-5">
                <div class="card login-card text-center">
                    <div class="card-body">
                        <div class="mb-4">
                            <h2 class="card-title">Quản lý tour du lịch</h2>
                            <p class="text-muted">Chào mừng bạn quay trở lại! Vui lòng đăng nhập.</p>
                        </div>
                        <form action="./handle/login_process.php" method="POST">
                            <div class="form-floating mb-3">
                                <input type="text" name="username" id="usernameInput" class="form-control" placeholder="Tên đăng nhập" required>
                                <label for="usernameInput">Tên đăng nhập</label>
                            </div>

                            <div class="form-floating mb-3">
                                <input type="password" name="password" id="passwordInput" class="form-control" placeholder="Mật khẩu" required>
                                <label for="passwordInput">Mật khẩu</label>
                            </div>

                            <?php if (isset($_SESSION['error'])): ?>
                                <div class="alert alert-danger mt-3" role="alert">
                                    <?php 
                                    echo $_SESSION['error']; 
                                    unset($_SESSION['error']);
                                    ?>
                                </div>
                            <?php endif; ?>
                            
                            <?php if (isset($_SESSION['success'])): ?>
                                <div class="alert alert-success mt-3" role="alert">
                                    <?php 
                                    echo $_SESSION['success']; 
                                    unset($_SESSION['success']);
                                    ?>
                                </div>
                            <?php endif; ?>

                            <div class="d-grid mt-4">
                                <button type="submit" name="login" class="btn btn-primary btn-lg">Đăng nhập</button>
                            </div>
                        </form>
                        <div class="mt-4 register-link">
                            <p class="mb-0">Bạn chưa có tài khoản? <a href="register.php">Đăng ký ngay</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>