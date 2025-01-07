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
    $price = str_replace(',', '', $_POST['price']);
    $categoryID = $_POST['categoryID'];
    $stockQuantity = $_POST['stockQuantity'];
    $weight = $_POST['weight'];
    $dimensions = $_POST['dimensions'];
    $isFeatured = isset($_POST['isFeatured']) ? 1 : 0;

    // Xử lý upload ảnh
    $imageUpload = $_FILES['imageUpload']; // Thay đổi từ imageFile thành imageUpload
    $uploadDir = 'uploads/';
    $uploadFile = $uploadDir . basename($imageUpload['name']);
    $imageUrl = '';

    if (move_uploaded_file($imageUpload['tmp_name'], $uploadFile)) { // Thay đổi từ imageFile thành imageUpload
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
  <script>
    function formatCurrency(input) {
        let value = input.value.replace(/\D/g, '');
        input.value = value.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }

    function convertToNumber(input) {
        let value = input.value.replace(/,/g, '');
        input.value = value;
    }
    function previewImage(event) {
            const file = event.target.files[0];
            const reader = new FileReader();

            reader.onload = function() {
                const imagePreview = document.getElementById('imagePreview');
                imagePreview.src = reader.result; // Gán URL hình ảnh xem trước
                imagePreview.style.display = 'block'; // Hiển thị hình ảnh xem trước
                document.getElementById('noImage').style.display = 'none'; // Ẩn thông báo "Chưa có ảnh"
            }

            if (file) {
                reader.readAsDataURL(file); // Đọc hình ảnh như một URL
            }
        }
  </script>
</head>
<body>
  <div class="user-management-container">
    <h1 class="user-management-header">Thêm Sản Phẩm</h1>

    <!-- Form thêm sản phẩm -->
    <form method="POST" action="" enctype="multipart/form-data" onsubmit="convertToNumber(document.getElementById('price'));">
      <label for="productID">ID Sản phẩm (Tự động)</label>
      <input type="text" id="productID" name="productID" value="<?= $newProductID ?>" readonly>

      <label for="name">Tên Sản phẩm</label>
      <input type="text" id="name" name="name" placeholder="Nhập tên sản phẩm" required>

      <label for="description">Mô tả</label>
      <textarea id="description" name="description" rows="4" placeholder="Nhập mô tả sản phẩm"></textarea>

      <label for="price">Giá</label>
      <input type="text" id="price" name="price" placeholder="Nhập giá sản phẩm" oninput="formatCurrency(this)" required>

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

      <label for="imageUpload">Hình Ảnh</label>
<input type="file" id="imageUpload" name="imageUpload" accept="image/*" onchange="previewImage(event)">

 <!-- Phần hiển thị hình ảnh xem trước -->
 <div id="imagePreviewContainer">
                <img id="imagePreview" src="#" alt="Hình ảnh sản phẩm xem trước" width="150" height="150" style="display: none; margin-top: 10px;">
                <p id="noImage" style="display: block; margin-top: 10px;">Chưa có ảnh</p>
            </div>

      <div class="discount-btn-container">
        <button type="submit" class="discount-btn discount-btn-success">Thêm Sản phẩm</button>
        <a href="manage_products.php" class="discount-btn discount-btn-danger">Hủy</a>
      </div>
    </form>
  </div>
</body>
</html>
