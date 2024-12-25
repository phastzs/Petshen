<?php
include 'C:\xampp\htdocs\Hoc_PHP\AppPetShop\config\db.php';

// Kiểm tra quyền truy cập
session_start();
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'staff')) {
  echo "<div class='alert'>Bạn không có quyền truy cập trang này.</div>";
  exit();
}

// Lấy ID sản phẩm từ URL
if (isset($_GET['id'])) {
    $productID = $_GET['id'];

    // Truy vấn để xóa sản phẩm
    $query = "DELETE FROM products WHERE productID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $productID);

    if ($stmt->execute()) {
        // Chuyển hướng về trang quản lý sản phẩm với thông báo thành công
        header("Location: manage_products.php?message=Sản phẩm đã được xóa thành công.");
        exit();
    } else {
        // Nếu có lỗi, hiển thị thông báo
        echo "<div class='alert'>Lỗi khi xóa sản phẩm: " . $conn->error . "</div>";
    }

    $stmt->close();
} else {
    echo "<div class='alert'>Không tìm thấy ID sản phẩm.</div>";
}

$conn->close();
?>
