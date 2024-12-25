<?php
ob_start(); // Bắt đầu output buffering

include 'C:\xampp\htdocs\Hoc_PHP\AppPetShop\header.php';
include 'C:\xampp\htdocs\Hoc_PHP\AppPetShop\config\db.php';

// Kiểm tra quyền truy cập
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'staff')) {
    echo "<div class='alert'>Bạn không có quyền truy cập trang này.</div>";
    exit();
}

// Lấy discountID từ URL
if (isset($_GET['id'])) {
    $discountID = $_GET['id'];

    // Xóa mã giảm giá
    $query = "DELETE FROM discounts WHERE discountID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $discountID);

    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Mã giảm giá đã được xóa thành công!</div>";
        header("Location: manage_discounts.php");
        exit();
    } else {
        echo "<div class='alert'>Đã xảy ra lỗi: " . $conn->error . "</div>";
    }
} else {
    echo "<div class='alert'>Không có mã giảm giá nào được chọn.</div>";
    exit();
}
?>
