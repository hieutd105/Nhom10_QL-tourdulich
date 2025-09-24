<?php
require_once 'db_connection.php';


function getAllCustomers() {
    $conn = getDbConnection();
    $sql = "SELECT id, ho_ten, email, sdt FROM customers ORDER BY id";
    $result = mysqli_query($conn, $sql);
    $customers = [];
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $customers[] = $row;
        }
    }
    mysqli_close($conn);
    return $customers;
}

function addCustomer($ho_ten, $email, $sdt) {
    $conn = getDbConnection();
    $sql = "INSERT INTO customers (ho_ten, email, sdt) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "sss", $ho_ten, $email, $sdt);
        $success = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        return $success;
    }
    mysqli_close($conn);
    return false;
}

function getCustomerById($id) {
    $conn = getDbConnection();
    $sql = "SELECT id, ho_ten, email, sdt FROM customers WHERE id = ? LIMIT 1";
    $stmt = mysqli_prepare($conn, $sql);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if ($result && mysqli_num_rows($result) > 0) {
            $customer = mysqli_fetch_assoc($result);
            mysqli_stmt_close($stmt);
            mysqli_close($conn);
            return $customer;
        }
        mysqli_stmt_close($stmt);
    }
    mysqli_close($conn);
    return null;
}

function updateCustomer($id, $ho_ten, $email, $sdt) {
    $conn = getDbConnection();
    $sql = "UPDATE customers SET ho_ten = ?, email = ?, sdt = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "sssi", $ho_ten, $email, $sdt, $id);
        $success = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        return $success;
    }
    mysqli_close($conn);
    return false;
}

function deleteCustomer($id) {
    $conn = getDbConnection();
    $sql = "DELETE FROM customers WHERE id = ?";
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