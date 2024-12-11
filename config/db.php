<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "olivia_pet"; // Tên cơ sở dữ liệu

$conn = new mysqli($servername, $username, $password, $database);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
