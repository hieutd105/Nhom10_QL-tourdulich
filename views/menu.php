<?php
require_once __DIR__ . '/../functions/auth.php';
checkLogin(__DIR__ . '/../index.php');
$currentUser = getCurrentUser();
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang chủ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
    <?php
    $currentPage = basename($_SERVER['PHP_SELF']);
    ?>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <!-- Toggle button -->
            <button data-mdb-collapse-init class="navbar-toggler" type="button"
                data-mdb-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <i class="fas fa-bars"></i>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <a class="navbar-brand mt-2 mt-lg-0" href="#">
                    <img src="../images/logo_menu.png" height="75"
                        alt="FIT-DNU Logo" loading="lazy" />
                </a>
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="tour.php">Quản lý Tour du lịch</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="hotel.php">Quản lý Khách sạn</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="transport.php">Quản lý Nhà xe</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="bookings.php">Quản lý Bookings</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="customer.php">Quản lý Khách du lịch</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="payment.php">Quản lý Thanh toán</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="payment_qr.php">QR Thanh Toán</a>
                    </li>
                </ul>
            </div>

            <div class="d-flex align-items-center">
                <a class="text-reset me-3" href="#">
                    <i class="fas fa-shopping-cart"></i>
                </a>
                <div class="dropdown">
                    <a data-mdb-dropdown-init class="dropdown-toggle d-flex align-items-center hidden-arrow" href="#"
                        id="navbarDropdownMenuAvatar" role="button" aria-expanded="false">
                        <img src="../images/logo_dangxuat.png" class="rounded-circle" height="25"
                            alt="AVT" loading="lazy" />
                        <span class="ms-2"><a class="dropdown-item" href="../handle/logout_process.php">Đăng xuất</a></span>
                    </a>
                </div>
            </div>
        </div>
    </nav>
</body>
</html>
