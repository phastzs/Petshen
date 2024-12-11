<?php
require_once 'config/db.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Bắt đầu session
session_start();

header('Content-Type: application/json');

$response = ['status' => 'error', 'message' => 'Đã xảy ra lỗi!'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $loginInput = trim($_POST['loginInput']); // Thay đổi tên biến để nhận thông tin đăng nhập
    $password = trim($_POST['password']);
    $errors = [];

    // Kiểm tra dữ liệu đầu vào
    if (empty($loginInput)) {
        $errors[] = 'Tên người dùng hoặc email không được để trống.';
    }
    if (empty($password)) {
        $errors[] = 'Mật khẩu không được để trống.';
    }

    // Nếu có lỗi, trả về thông báo lỗi
    if (!empty($errors)) {
        $response['errors'] = $errors;
        echo json_encode($response);
        exit;
    }

    // Kiểm tra thông tin đăng nhập
    $stmt = $conn->prepare('SELECT UserID, password, userName FROM users WHERE email = ? OR userName = ?');
    $stmt->bind_param('ss', $loginInput, $loginInput);
    $stmt->execute();
    $stmt->bind_result($userID, $hashedPassword, $userName);
    $stmt->fetch();
    $stmt->close();

    // Nếu không tìm thấy người dùng hoặc mật khẩu không khớp
    if (!$userID || !password_verify($password, $hashedPassword)) {
        $response['message'] = 'Tên người dùng, email hoặc mật khẩu không đúng.';
        echo json_encode($response);
        exit;
    }

    // Đăng nhập thành công, thiết lập phiên
    $_SESSION['userID'] = $userID; // Lưu UserID vào phiên
    $_SESSION['userName'] = $userName; // Lưu tên người dùng vào phiên

    $response['status'] = 'success';
    $response['message'] = 'Đăng nhập thành công!';

    // Có thể trả về thông tin người dùng nếu cần
    $response['user'] = [
        'userID' => $userID,
        'userName' => $userName,
    ];
}

echo json_encode($response);
?>
