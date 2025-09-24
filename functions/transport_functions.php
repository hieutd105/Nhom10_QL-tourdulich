<?php
require_once 'db_connection.php';

/**
 * Lấy tất cả nhà xe
 */
function getAllTransports() {
    $conn = getDbConnection();
    $sql = "SELECT id, company_name, vehicle_type, seats, license_plate, 
                   route_from, route_to, price_per_trip, phone, image 
            FROM transports ORDER BY id DESC";
    $result = mysqli_query($conn, $sql);

    $transports = [];
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $transports[] = $row;
        }
    }
    mysqli_close($conn);
    return $transports;
}

/**
 * Thêm nhà xe mới
 */
function transport_create($data) {
    $conn = getDbConnection();
    $sql = "INSERT INTO transports (company_name, vehicle_type, seats, license_plate, route_from, route_to, price_per_trip, phone, image) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param(
            $stmt,
            "ssisssdss",
            $data['company_name'],
            $data['vehicle_type'],
            $data['seats'],
            $data['license_plate'],
            $data['route_from'],
            $data['route_to'],
            $data['price_per_trip'],
            $data['phone'],
            $data['image']
        );
        $success = mysqli_stmt_execute($stmt);

        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        return $success;
    }

    mysqli_close($conn);
    return false;
}

/**
 * Lấy nhà xe theo ID
 */
function getTransportById($id) {
    $conn = getDbConnection();
    $sql = "SELECT id, company_name, vehicle_type, seats, license_plate, route_from, route_to, price_per_trip, phone, image 
            FROM transports WHERE id = ? LIMIT 1";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($result && mysqli_num_rows($result) > 0) {
            $transport = mysqli_fetch_assoc($result);
            mysqli_stmt_close($stmt);
            mysqli_close($conn);
            return $transport;
        }
        mysqli_stmt_close($stmt);
    }

    mysqli_close($conn);
    return null;
}

/**
 * Cập nhật nhà xe
 */
function transport_update($id, $data) {
    $conn = getDbConnection();
    $sql = "UPDATE transports 
            SET company_name=?, vehicle_type=?, seats=?, license_plate=?, route_from=?, route_to=?, price_per_trip=?, phone=?, image=? 
            WHERE id=?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param(
            $stmt,
            "ssisssdssi",
            $data['company_name'],
            $data['vehicle_type'],
            $data['seats'],
            $data['license_plate'],
            $data['route_from'],
            $data['route_to'],
            $data['price_per_trip'],
            $data['phone'],
            $data['image'],
            $id
        );
        $success = mysqli_stmt_execute($stmt);

        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        return $success;
    }

    mysqli_close($conn);
    return false;
}

/**
 * Xóa nhà xe
 */
function transport_delete($id) {
    $conn = getDbConnection();
    $sql = "DELETE FROM transports WHERE id = ?";
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
