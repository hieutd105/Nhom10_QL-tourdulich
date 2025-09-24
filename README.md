<h2 align="center">
    <a href="https://dainam.edu.vn/vi/khoa-cong-nghe-thong-tin">
    🎓 Faculty of Information Technology (DaiNam University)
    </a>
</h2>
<h2 align="center">
    Open Source Software Development
</h2>
<div align="center">
    <p align="center">
        <img src="docs/logo/aiotlab_logo.png" alt="AIoTLab Logo" width="170"/>
        <img src="docs/logo/fitdnu_logo.png" alt="AIoTLab Logo" width="180"/>
        <img src="docs/logo/dnu_logo.png" alt="DaiNam University Logo" width="200"/>
    </p>

[![AIoTLab](https://img.shields.io/badge/AIoTLab-green?style=for-the-badge)](https://www.facebook.com/DNUAIoTLab)
[![Faculty of Information Technology](https://img.shields.io/badge/Faculty%20of%20Information%20Technology-blue?style=for-the-badge)](https://dainam.edu.vn/vi/khoa-cong-nghe-thong-tin)
[![DaiNam University](https://img.shields.io/badge/DaiNam%20University-orange?style=for-the-badge)](https://dainam.edu.vn)

</div>
## 📖 1. Giới thiệu
Đây là hệ thống quản lý và đặt tour du lịch trực tuyến, giúp bạn dễ dàng tìm kiếm, lựa chọn và đặt tour nhanh chóng. Website cung cấp đầy đủ thông tin về lịch trình, giá cả, khách sạn, phương tiện và các dịch vụ đi kèm. Với giao diện thân thiện và thanh toán tiện lợi, chúng tôi mang đến cho bạn những chuyến đi an toàn và trọn vẹn.

## 🔧 2. Các công nghệ được sử dụng
<div align="center">

### Hệ điều hành
![macOS](https://img.shields.io/badge/macOS-000000?style=for-the-badge&logo=macos&logoColor=F0F0F0)
[![Windows](https://img.shields.io/badge/Windows-0078D6?style=for-the-badge&logo=windows&logoColor=white)](https://www.microsoft.com/en-us/windows/)
[![Ubuntu](https://img.shields.io/badge/Ubuntu-E95420?style=for-the-badge&logo=ubuntu&logoColor=white)](https://ubuntu.com/)

### Công nghệ chính
[![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://www.php.net/)
[![HTML5](https://img.shields.io/badge/HTML5-E34F26?style=for-the-badge&logo=html5&logoColor=white)](#)
[![CSS](https://img.shields.io/badge/CSS-1572B6?style=for-the-badge&logo=css3&logoColor=white)](#)
[![SCSS](https://img.shields.io/badge/SCSS-CC6699?style=for-the-badge&logo=sass&logoColor=white)](#)
[![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)](#)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-563D7C?style=for-the-badge&logo=bootstrap&logoColor=white)](https://getbootstrap.com/)

### Web Server & Database
[![Apache](https://img.shields.io/badge/Apache-D22128?style=for-the-badge&logo=apache&logoColor=white)](https://httpd.apache.org/)
[![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://www.mysql.com/) 
[![XAMPP](https://img.shields.io/badge/XAMPP-FB7A24?style=for-the-badge&logo=xampp&logoColor=white)](https://www.apachefriends.org/)

### Database Management Tools
[![MySQL Workbench](https://img.shields.io/badge/MySQL_Workbench-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://dev.mysql.com/downloads/workbench/)
</div>

## 🚀 3. Hình ảnh các chức năng
### Trang đăng nhập tài khoản
<img width="1903" height="935" alt="image" src="https://github.com/hieutd105/Nhom10_QL-tourdulich/blob/main/image/tour.png" />
### Trang đăng kí tài khoản
<img width="1903" height="935" alt="image" src="https://github.com/user-attachments/assets/a8475173-e8b8-4de6-aea2-2809a29e50e7" />
### Trang xem danh sách tour du lịch
<img width="1888" height="943" alt="image" src="https://github.com/user-attachments/assets/1c7176f3-f662-4dd4-a99e-94474d9c62d2" />
### Trang xem khách sạn 
<img width="1879" height="944" alt="image" src="https://github.com/user-attachments/assets/13aba4fa-922f-4a67-99e3-e1e8df1ffaa1" />
### Trang xem nhà xe 
<img width="1888" height="949" alt="image" src="https://github.com/user-attachments/assets/66e4c8b4-c39f-4dc1-a472-ef7e569f35c4" />
### Trang xem danh sách thanh toán
<img width="1888" height="939" alt="image" src="https://github.com/user-attachments/assets/8bbe2d42-1d4f-4a7a-bf60-9d2e6120e9a7" />
### Trang xem qr thanh toán 
<img width="1888" height="942" alt="image" src="https://github.com/user-attachments/assets/e5103264-da76-4983-ab73-a86999144eed" />

## ⚙️ 4. Cài đặt

### 4.1. Cài đặt công cụ, môi trường và các thư viện cần thiết

- Tải và cài đặt **XAMPP**  
  👉 https://www.apachefriends.org/download.html  
  (Khuyến nghị bản XAMPP với PHP 8.x)

- Cài đặt **Visual Studio Code** và các extension:
  - PHP Intelephense  
  - MySQL  
  - Prettier – Code Formatter  
### 4.2. Tải project
Clone project về thư mục `htdocs` của XAMPP (ví dụ ổ C):

```bash
cd C:\xampp\htdocs
https://github.com/hieutd105/Nhom10_QL-tourdulich

```
### 4.3. Setup database
Mở XAMPP Control Panel, Start Apache và MySQL

Truy cập MySQL WorkBench
Tạo database:
```bash
CREATE DATABASE db_dulich
   CHARACTER SET utf8mb4
   COLLATE utf8mb4_unicode_ci;
```

### 4.4. Setup tham số kết nối
Mở file config.php (hoặc .env) trong project, chỉnh thông tin DB:
```bash

<?php

function getDbConnection() {
    $servername = "127.0.0.1";
    $username = "root";
    $password = "";
    $dbname = "db_dulich";
    $port = 3306;

    $conn = mysqli_connect($servername, $username, $password, $dbname, $port);

    if (!$conn) {
        die("Kết nối database thất bại: " . mysqli_connect_error());
    }
    mysqli_set_charset($conn, "utf8");
    return $conn;
}

?>
```
### 4.5. Chạy hệ thống
Mở XAMPP Control Panel → Start Apache và MySQL

Truy cập hệ thống:
👉 http://localhost/hieu/index.php

### 4.6. Đăng nhập lần đầu
Hệ thống có thể cấp tài khoản admin 

Sau khi đăng nhập Admin có thể:

Tạo thông tin tour du lịch bao gồm tên tour, lịch trình, giá và dịch vụ kèm theo.

Quản lý khách hàng và theo dõi thông tin đặt tour.

Thêm và quản lý phương tiện, khách sạn liên kết.

Quản lý thanh toán với quét QR.

Quản lý đánh giá và phản hồi từ khách hàng để nâng cao chất lượng dịch vụ.

Phân quyền người dùng (Admin, Khách hàng) để vận hành hệ thống hiệu quả.
