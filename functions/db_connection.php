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