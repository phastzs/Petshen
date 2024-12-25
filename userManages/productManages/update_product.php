<?php
include 'C:\xampp\htdocs\Hoc_PHP\AppPetShop\config\db.php';

// Kiểm tra quyền truy cập
session_start();
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'staff')) {
  echo "<div class='alert'>Bạn không có quyền truy cập trang này.</div>";
  exit();
}

// Lấy thông tin từ biểu mẫu
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productID = $_POST['productID'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $categoryID = $_POST['categoryID'];
    $stockQuantity = $_POST['stockQuantity'];
    $weight = $_POST['weight'];
    $dimensions = $_POST['dimensions'];
    $isFeatured = isset($_POST['isFeatured']) ? 1 : 0;

    // Kiểm tra xem có tải lên hình ảnh không
    if (isset($_FILES['imageUpload']) && $_FILES['imageUpload']['error'] === UPLOAD_ERR_OK) {
        $imageUrl = 'uploads/' . basename($_FILES['imageUpload']['name']);
        move_uploaded_file($_FILES['imageUpload']['tmp_name'], $imageUrl);

        // Cập nhật thông tin sản phẩm với hình ảnh
        $query = "UPDATE products SET name=?, description=?, price=?, categoryID=?, stockQuantity=?, weight=?, dimensions=?, isFeatured=?, imageUrl=? WHERE productID=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssdsississ", $name, $description, $price, $categoryID, $stockQuantity, $weight, $dimensions, $isFeatured, $imageUrl, $productID);
    } else {
        // Nếu không tải lên hình ảnh, cập nhật mà không thay đổi imageUrl
        $query = "UPDATE products SET name=?, description=?, price=?, categoryID=?, stockQuantity=?, weight=?, dimensions=?, isFeatured=? WHERE productID=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssdsisss", $name, $description, $price, $categoryID, $stockQuantity, $weight, $dimensions, $isFeatured, $productID);
    }

    if ($stmt->execute()) {
        // Chuyển hướng về trang quản lý sản phẩm với thông báo thành công
        header("Location: manage_products.php?message=Sản phẩm đã được cập nhật thành công.");
        exit();
    } else {
        echo "<div class='alert'>Lỗi khi cập nhật sản phẩm: " . $conn->error . "</div>";
    }

    $stmt->close();
} else {
    echo "<div class='alert'>Yêu cầu không hợp lệ.</div>";
}

$conn->close();
?>
