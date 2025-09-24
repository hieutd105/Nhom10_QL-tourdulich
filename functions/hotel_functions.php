<?php
require_once 'db_connection.php';

/**
 * Lấy tất cả khách sạn
 * Trả về đầy đủ cả description và status
 * @return array
 */
function getAllHotels() {
    $conn = getDbConnection();
    $sql = "SELECT id, name, city, address, stars, price_per_night, phone, image, description, status
            FROM hotels
            ORDER BY id DESC";
    $result = mysqli_query($conn, $sql);

    $hotels = [];
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $hotels[] = $row;
        }
        mysqli_free_result($result);
    }
    mysqli_close($conn);
    return $hotels;
}

/**
 * Thêm khách sạn mới (có description, status)
 * @return bool
 */
function addHotel($name, $city, $address, $stars, $price_per_night, $phone, $image, $description, $status) {
    $conn = getDbConnection();
    $sql = "INSERT INTO hotels (name, city, address, stars, price_per_night, phone, image, description, status)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $sql);
    if (!$stmt) {
        // Nhật ký lỗi để dễ debug (không die để tránh blank page)
        error_log("Prepare addHotel failed: " . mysqli_error($conn));
        mysqli_close($conn);
        return false;
    }

    // name(s), city(s), address(s), stars(i), price(d), phone(s), image(s), description(s), status(i)
    mysqli_stmt_bind_param(
        $stmt,
        "sssidsssi",
        $name, $city, $address,
        $stars, $price_per_night,
        $phone, $image, $description,
        $status
    );

    $ok = mysqli_stmt_execute($stmt);
    if (!$ok) {
        error_log("Execute addHotel failed: " . mysqli_stmt_error($stmt));
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    return $ok;
}

/**
 * Lấy khách sạn theo ID (có description, status)
 * @return ?array
 */
function getHotelById($id) {
    $conn = getDbConnection();
    $sql = "SELECT id, name, city, address, stars, price_per_night, phone, image, description, status
            FROM hotels
            WHERE id = ? LIMIT 1";
    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) {
        error_log("Prepare getHotelById failed: " . mysqli_error($conn));
        mysqli_close($conn);
        return null;
    }

    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $hotel = null;
    if ($result && mysqli_num_rows($result) > 0) {
        $hotel = mysqli_fetch_assoc($result);
        mysqli_free_result($result);
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    return $hotel;
}

/**
 * Cập nhật khách sạn (có description, status)
 * @return bool
 */
function updateHotel($id, $name, $city, $address, $stars, $price_per_night, $phone, $image, $description, $status) {
    $conn = getDbConnection();
    $sql = "UPDATE hotels
            SET name = ?, city = ?, address = ?, stars = ?, price_per_night = ?, phone = ?, image = ?, description = ?, status = ?
            WHERE id = ?";

    $stmt = mysqli_prepare($conn, $sql);
    if (!$stmt) {
        error_log("Prepare updateHotel failed: " . mysqli_error($conn));
        mysqli_close($conn);
        return false;
    }

    // như trên + id cuối là int
    mysqli_stmt_bind_param(
        $stmt,
        "sssidsssii",
        $name, $city, $address,
        $stars, $price_per_night,
        $phone, $image, $description,
        $status, $id
    );

    $ok = mysqli_stmt_execute($stmt);
    if (!$ok) {
        error_log("Execute updateHotel failed: " . mysqli_stmt_error($stmt));
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    return $ok;
}

/**
 * Xóa khách sạn theo ID
 * @return bool
 */
function deleteHotel($id) {
    $conn = getDbConnection();
    $sql = "DELETE FROM hotels WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) {
        error_log("Prepare deleteHotel failed: " . mysqli_error($conn));
        mysqli_close($conn);
        return false;
    }

    mysqli_stmt_bind_param($stmt, "i", $id);
    $ok = mysqli_stmt_execute($stmt);
    if (!$ok) {
        error_log("Execute deleteHotel failed: " . mysqli_stmt_error($stmt));
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    return $ok;
}
