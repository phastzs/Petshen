<?php
include 'C:\xampp\htdocs\Hoc_PHP\AppPetShop\header.php';
include 'C:\xampp\htdocs\Hoc_PHP\AppPetShop\config\db.php';

// Kiểm tra quyền truy cập
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'staff')) {
  echo "<div class='alert'>Bạn không có quyền truy cập trang này.</div>";
  exit();
}

// Tạo mã sản phẩm tự động
$query = "SELECT MAX(productID) AS lastID FROM products";
$result = $conn->query($query);
$lastID = $result->fetch_assoc()['lastID'] ?? null;

if ($lastID) {
    $numPart = intval(substr($lastID, 3)) + 1;
} else {
    $numPart = 1;
}
$newProductID = 'PRD' . str_pad($numPart, 5, '0', STR_PAD_LEFT);

// Truy vấn danh mục
$categoriesQuery = "SELECT categoryID, categoryName FROM categories";
$categoriesResult = $conn->query($categoriesQuery);

// Xử lý khi gửi form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $categoryID = $_POST['categoryID'];
    $stockQuantity = $_POST['stockQuantity'];
    $weight = $_POST['weight'];
    $dimensions = $_POST['dimensions'];
    $isFeatured = isset($_POST['isFeatured']) ? 1 : 0;

    // Xử lý upload ảnh
    $imageFile = $_FILES['imageFile'];
    $uploadDir = 'uploads/';
    $uploadFile = $uploadDir . basename($imageFile['name']);
    $imageUrl = '';

    if (move_uploaded_file($imageFile['tmp_name'], $uploadFile)) {
        $imageUrl = $uploadFile;
    } else {
        echo "<div class='alert'>Lỗi: Không thể upload ảnh.</div>";
    }

    // Thêm sản phẩm vào cơ sở dữ liệu
    $query = "INSERT INTO products (productID, name, description, price, categoryID, stockQuantity, weight, dimensions, isFeatured, imageUrl)
              VALUES ('$newProductID', '$name', '$description', $price, '$categoryID', $stockQuantity, $weight, '$dimensions', $isFeatured, '$imageUrl')";

    if ($conn->query($query) === TRUE) {
        echo "<div class='alert alert-success'>Sản phẩm đã được thêm thành công.</div>";
    } else {
        echo "<div class='alert'>Lỗi: " . $conn->error . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Thêm Sản phẩm</title>
  <link rel="stylesheet" href="../manage.css">
</head>
<body>
  <div class="user-management-container">
    <h1 class="user-management-header">Thêm Sản Phẩm</h1>

    <!-- Form thêm sản phẩm -->
    <form method="POST" action="" enctype="multipart/form-data">
      <label for="productID">ID Sản phẩm (Tự động)</label>
      <input type="text" id="productID" name="productID" value="<?= $newProductID ?>" readonly>

      <label for="name">Tên Sản phẩm</label>
      <input type="text" id="name" name="name" placeholder="Nhập tên sản phẩm" required>

      <label for="description">Mô tả</label>
      <textarea id="description" name="description" rows="4" placeholder="Nhập mô tả sản phẩm"></textarea>

      <label for="price">Giá</label>
      <input type="number" id="price" name="price" placeholder="Nhập giá sản phẩm" step="0.01" required>

      <label for="categoryID">Danh mục</label>
      <select id="categoryID" name="categoryID" required>
        <option value="" disabled selected>Chọn danh mục</option>
        <?php
        while ($category = $categoriesResult->fetch_assoc()) {
            echo "<option value='{$category['categoryID']}'>{$category['categoryName']}</option>";
        }
        ?>
      </select>

      <label for="stockQuantity">Số lượng</label>
      <input type="number" id="stockQuantity" name="stockQuantity" placeholder="Nhập số lượng trong kho" required>

      <label for="weight">Trọng lượng (kg)</label>
      <input type="number" id="weight" name="weight" placeholder="Nhập trọng lượng sản phẩm" step="0.01" required>

      <label for="dimensions">Kích thước (Dài x Rộng x Cao)</label>
      <input type="text" id="dimensions" name="dimensions" placeholder="Nhập kích thước sản phẩm">

      <label for="isFeatured">Nổi bật</label>
      <input type="checkbox" id="isFeatured" name="isFeatured">

      <label for="imageFile">Hình ảnh</label>
      <input type="file" id="imageFile" name="imageFile" accept="image/*" required>

      <div class="discount-btn-container">
        <button type="submit" class="discount-btn discount-btn-success">Thêm Sản phẩm</button>
        <a href="manage_products.php" class="discount-btn discount-btn-danger">Hủy</a>
      </div>
    </form>
  </div>
</body>
</html>
