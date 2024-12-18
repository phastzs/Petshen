<?php
session_start();
require_once '../config/db.php';

$response = ['status' => 'error', 'message' => 'Đã xảy ra lỗi!'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_SESSION['userID'];
    $email = trim($_POST['email']);
    $phoneNumber = trim($_POST['phoneNumber']);
    $province = trim($_POST['province']);
    $district = trim($_POST['district']);
    $commune = trim($_POST['commune']);
    $hamlet = trim($_POST['hamlet']);
    $address = "$province $district $commune $hamlet"; // Ghép địa chỉ

    // Nếu có mật khẩu mới, cập nhật mật khẩu
    $password = trim($_POST['password']);
    if (!empty($password)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET email = ?, phoneNumber = ?, address = ?, password = ? WHERE userID = ?");
        $stmt->bind_param("ssssi", $email, $phoneNumber, $address, $hashedPassword, $userId);
    } else {
        $stmt = $conn->prepare("UPDATE users SET email = ?, phoneNumber = ?, address = ? WHERE userID = ?");
        $stmt->bind_param("sssi", $email, $phoneNumber, $address, $userId);
    }

    if ($stmt->execute()) {
        $response['status'] = 'success';
        $response['message'] = 'Cập nhật thành công!';
    } else {
        $response['message'] = 'Cập nhật thất bại!';
    }
    $stmt->close();
}

echo json_encode($response);
?>
