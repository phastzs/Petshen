<?php
ob_start(); // Bắt đầu output buffering

include 'C:\xampp\htdocs\Hoc_PHP\AppPetShop\header.php';
include 'C:\xampp\htdocs\Hoc_PHP\AppPetShop\config\db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo "Không có quyền truy cập.";
    exit;
}

if (isset($_GET['id'])) {
    $userID = $_GET['id'];

    // Kiểm tra userID có tồn tại không
    $query = "SELECT status FROM users WHERE userID = '$userID'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $newStatus = $row['status'] === 'active' ? 'inactive' : 'active';

        // Cập nhật trạng thái
        $updateQuery = "UPDATE users SET status = '$newStatus' WHERE userID = '$userID'";
        if (mysqli_query($conn, $updateQuery)) {
            header("Location: manage_users.php");
            exit;
        } else {
            echo "Không thể cập nhật trạng thái.";
        }
    } else {
        echo "Người dùng không tồn tại.";
    }
} else {
    echo "Không có ID người dùng.";
}
?>
