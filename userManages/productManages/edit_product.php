<?php
include 'C:\xampp\htdocs\Hoc_PHP\AppPetShop\header.php';
include 'C:\xampp\htdocs\Hoc_PHP\AppPetShop\config\db.php';

// Kiểm tra quyền truy cập
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'staff')) {
  echo "<div class='alert'>Bạn không có quyền truy cập trang này.</div>";
  exit();
}

// Lấy ID sản phẩm từ URL
if (isset($_GET['id'])) {
    $productID = $_GET['id'];

    // Truy vấn thông tin sản phẩm
    $query = "SELECT * FROM products WHERE productID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $productID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
    } else {
        echo "<div class='alert'>Không tìm thấy sản phẩm.</div>";
        exit();
    }
} else {
    echo "<div class='alert'>Không tìm thấy ID sản phẩm.</div>";
    exit();
}

// Lấy danh sách danh mục từ bảng categories
$categoryQuery = "SELECT categoryID, categoryName FROM categories";
$categoryResult = $conn->query($categoryQuery);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa Sản Phẩm</title>
    <link rel="stylesheet" href="../manage.css">
    <style>
        /* Đặt kích thước cố định cho trường mô tả */
        #description {
            border-radius: 20px;
            padding: 10px;
            width: 400px;
            height: 100px; /* Chiều cao cố định */
        }
    </style>
</head>
<body>
    <div class="user-management-container">
        <h1 class="user-management-header">Sửa Sản Phẩm</h1>
        <form action="update_product.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="productID" value="<?php echo $product['productID']; ?>">

            <label for="name">Tên sản phẩm:</label>
            <input type="text" id="name" name="name" value="<?php echo $product['name']; ?>" required>

            <label for="description">Mô tả:</label>
            <textarea id="description" name="description" required><?php echo $product['description']; ?></textarea>

            <label for="price">Giá:</label>
            <input type="number" id="price" name="price" value="<?php echo $product['price']; ?>" required step="0.01">

            <label for="categoryID">Danh mục:</label>
            <select id="categoryID" name="categoryID" required>
                <?php
                while ($category = $categoryResult->fetch_assoc()) {
                    $selected = ($category['categoryID'] === $product['categoryID']) ? 'selected' : '';
                    echo "<option value='{$category['categoryID']}' $selected>{$category['categoryName']}</option>";
                }
                ?>
            </select>

            <label for="stockQuantity">Số lượng:</label>
            <input type="number" id="stockQuantity" name="stockQuantity" value="<?php echo $product['stockQuantity']; ?>" required>

            <label for="weight">Trọng lượng:</label>
            <input type="number" id="weight" name="weight" value="<?php echo $product['weight']; ?>" step="0.01" required>

            <label for="dimensions">Kích thước:</label>
            <input type="text" id="dimensions" name="dimensions" value="<?php echo $product['dimensions']; ?>" required>

            <label for="isFeatured">Nổi bật:</label>
            <input type="checkbox" id="isFeatured" name="isFeatured" value="1" <?php echo $product['isFeatured'] ? 'checked' : ''; ?>>

            <label for="imageUpload">Tải lên hình ảnh:</label>
            <input type="file" id="imageUpload" name="imageUpload" accept="image/*">

            <button type="submit" class="discount-btn discount-btn-success">Cập nhật sản phẩm</button>
        </form>
    </div>
</body>
</html>
