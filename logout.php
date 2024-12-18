<?php
session_start(); // Bắt đầu session

// Xóa tất cả các biến session
session_unset();

// Hủy session
session_destroy();

// Xóa cookie "Nhớ tôi" nếu có
if (isset($_COOKIE['userID'])) {
    setcookie('userID', '', time() - 3600, '/'); // Xóa cookie userID
}
if (isset($_COOKIE['userName'])) {
    setcookie('userName', '', time() - 3600, '/'); // Xóa cookie userName
}

// Chuyển hướng về trang chính sau khi đăng xuất
header('Location: index.php');
exit;
?>
