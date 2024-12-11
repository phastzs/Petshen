<?php
require_once 'config/db.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

$response = ['status' => 'error', 'message' => 'Đã xảy ra lỗi!'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['userName']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirmPassword = trim($_POST['confirmPassword']);
    $phoneNumber = trim($_POST['phoneNumber']);
    $errors = [];

    // Kiểm tra dữ liệu đầu vào
    if (empty($username)) {
        $errors[] = 'Tên người dùng không được để trống.';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Email không hợp lệ.';
    }
    if (strlen($password) < 6) {
        $errors[] = 'Mật khẩu phải có ít nhất 6 ký tự.';
    }
    if ($password !== $confirmPassword) {
        $errors[] = 'Mật khẩu xác nhận không khớp.';
    }
    if (!preg_match('/^[0-9]{10}$/', $phoneNumber)) {
        $errors[] = 'Số điện thoại không hợp lệ.';
    }

    // Nếu có lỗi, trả về thông báo lỗi
    if (!empty($errors)) {
        $response['errors'] = $errors;
        echo json_encode($response);
        exit;
    }

    // Mã hóa mật khẩu
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Kiểm tra email đã tồn tại
    $stmt = $conn->prepare('SELECT UserID FROM users WHERE email = ?');
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $response['message'] = 'Email đã được sử dụng.';
        echo json_encode($response);
        exit;
    }
    $stmt->close();

    // Tạo UserID mới
    $stmt = $conn->prepare('SELECT UserID FROM users ORDER BY UserID DESC LIMIT 1');
    $stmt->execute();
    $stmt->bind_result($lastId);
    $stmt->fetch();
    $stmt->close();

    if ($lastId) {
        // Tăng giá trị ID
        $num = (int)substr($lastId, 3); // Lấy phần số từ UserID
        $newId = 'CUS' . str_pad($num + 1, 5, '0', STR_PAD_LEFT);
    } else {
        $newId = 'CUS00001'; // ID đầu tiên
    }

    // Thêm người dùng mới vào cơ sở dữ liệu
    $stmt = $conn->prepare('INSERT INTO users (UserID, userName, email, password, phoneNumber) VALUES (?, ?, ?, ?, ?)');
    $stmt->bind_param('sssss', $newId, $username, $email, $hashedPassword, $phoneNumber);

    if ($stmt->execute()) {
        // Thiết lập phiên cho người dùng
        session_start();
        $_SESSION['userID'] = $newId; // Lưu UserID vào phiên
        $_SESSION['userName'] = $username; // Lưu tên người dùng vào phiên

        $response['status'] = 'success';
        $response['message'] = 'Đăng ký thành công! Bạn đã được tự động đăng nhập.';
    } else {
        $response['message'] = 'Không thể tạo tài khoản. Vui lòng thử lại.';
    }
    $stmt->close();
}

echo json_encode($response);
?>
