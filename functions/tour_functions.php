<?php
require_once 'db_connection.php';

/**
 * Lấy tất cả danh sách tour từ database
 * @return array Danh sách tour
 */
function getAllTours() {
    $conn = getDbConnection();
    
    $sql = "SELECT id, ten_tour, gia, ngay_khoi_hanh, so_cho, mo_ta FROM tours ORDER BY id";
    $result = mysqli_query($conn, $sql);
    
    $tours = [];
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) { 
            $tours[] = $row;
        }
    }
    
    mysqli_close($conn);
    return $tours;
}

/**
 * Thêm tour mới
 * @param string $ten_tour
 * @param float $gia
 * @param string $ngay_khoi_hanh
 * @param int $so_cho
 * @param string $mo_ta
 * @return bool True nếu thành công, False nếu thất bại
 */
function addTour($ten_tour, $gia, $ngay_khoi_hanh, $so_cho, $mo_ta) {
    $conn = getDbConnection();
    
    $sql = "INSERT INTO tours (ten_tour, gia, ngay_khoi_hanh, so_cho, mo_ta) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "sdsss", $ten_tour, $gia, $ngay_khoi_hanh, $so_cho, $mo_ta);
        // Sửa kiểu dữ liệu: "sdsss" => string, double, string, string, string
        // Nhưng so_cho là int, nên phải là "sdiss"
        mysqli_stmt_bind_param($stmt, "sdiss", $ten_tour, $gia, $ngay_khoi_hanh, $so_cho, $mo_ta);
        $success = mysqli_stmt_execute($stmt);
        
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        return $success;
    }
    
    mysqli_close($conn);
    return false;
}

/**
 * Lấy thông tin một tour theo ID
 * @param int $id
 * @return array|null
 */
function getTourById($id) {
    $conn = getDbConnection();
    
    $sql = "SELECT id, ten_tour, gia, ngay_khoi_hanh, so_cho, mo_ta FROM tours WHERE id = ? LIMIT 1";
    $stmt = mysqli_prepare($conn, $sql);
    
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if ($result && mysqli_num_rows($result) > 0) {
            $tour = mysqli_fetch_assoc($result);
            mysqli_stmt_close($stmt);
            mysqli_close($conn);
            return $tour;
        }
        
        mysqli_stmt_close($stmt);
    }
    
    mysqli_close($conn);
    return null;
}

/**
 * Cập nhật thông tin tour
 * @param int $id
 * @param string $ten_tour
 * @param float $gia
 * @param string $ngay_khoi_hanh
 * @param int $so_cho
 * @param string $mo_ta
 * @return bool
 */
function updateTour($id, $ten_tour, $gia, $ngay_khoi_hanh, $so_cho, $mo_ta) {
    $conn = getDbConnection();
    
    $sql = "UPDATE tours SET ten_tour = ?, gia = ?, ngay_khoi_hanh = ?, so_cho = ?, mo_ta = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "sdissi", $ten_tour, $gia, $ngay_khoi_hanh, $so_cho, $mo_ta, $id);
        $success = mysqli_stmt_execute($stmt);
        
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        return $success;
    }
    
    mysqli_close($conn);
    return false;
}

/**
 * Xóa tour theo ID
 * @param int $id
 * @return bool
 */
function deleteTour($id) {
    $conn = getDbConnection();
    
    $sql = "DELETE FROM tours WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $id);
        $success = mysqli_stmt_execute($stmt);
        
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        return $success;
    }
    
    mysqli_close($conn);
    return false;
}
?>