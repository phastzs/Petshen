<?php
require_once 'config/db.php'; // Kết nối cơ sở dữ liệu
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start(); // Khởi tạo phiên ở đầu file

header('Content-Type: application/json');

$response = ['status' => 'error', 'message' => 'Đã xảy ra lỗi!'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $identifier = trim($_POST['identifier']); // Tên người dùng hoặc email
    $password = trim($_POST['password']);
    $rememberMe = isset($_POST['rememberMe']); // Kiểm tra ô "Nhớ tôi"
    $errors = [];

    // Kiểm tra dữ liệu đầu vào
    if (empty($identifier)) {
        $errors[] = 'Email hoặc tên người dùng không được để trống.';
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
    $stmt = $conn->prepare('SELECT UserID, password, userName, email, role FROM users WHERE email = ? OR userName = ?');
    $stmt->bind_param('ss', $identifier, $identifier);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($userId, $hashedPassword, $userName, $userEmail, $role);
        $stmt->fetch();

        // Kiểm tra mật khẩu
        if (password_verify($password, $hashedPassword)) {
            // Thiết lập phiên cho người dùng
            $_SESSION['userID'] = $userId;
            $_SESSION['userName'] = $userName;
            $_SESSION['role'] = $role;

            // Xử lý "Nhớ tôi" với cookie
            if ($rememberMe) {
                // Lưu thông tin vào cookie, mã hóa UserID để đảm bảo an toàn
                setcookie('userID', base64_encode($userId), time() + (86400 * 30), '/'); // 30 ngày
                setcookie('userName', base64_encode($userName), time() + (86400 * 30), '/');
            } else {
                // Xóa cookie nếu không chọn "Nhớ tôi"
                setcookie('userID', '', time() - 3600, '/');
                setcookie('userName', '', time() - 3600, '/');
            }

            $response['status'] = 'success';
            $response['message'] = 'Đăng nhập thành công!';
            $response['userName'] = $userName;
        } else {
            $response['message'] = 'Mật khẩu không chính xác.';
        }
    } else {
        $response['message'] = 'Email hoặc tên người dùng không tồn tại.';
    }

    $stmt->close();
}

echo json_encode($response);
?>
/
