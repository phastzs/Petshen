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
        }

        if (file) {
            reader.readAsDataURL(file); // Đọc hình ảnh như một URL
        }
    }
    </script>
</head>
<body>
    <div class="user-management-container">
        <h1 class="user-management-header">Sửa Sản Phẩm</h1>

        <!-- Form sửa sản phẩm -->
        <form action="update_product.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="productID" value="<?= $product['productID']; ?>">

            <label for="name">Tên sản phẩm</label>
            <input type="text" id="name" name="name" value="<?= $product['name']; ?>" required>

            <label for="description">Mô tả</label>
            <textarea id="description" name="description" rows="4" required><?= $product['description']; ?></textarea>

            <label for="price">Giá</label>
            <input type="text" id="price" name="price" placeholder="Nhập giá sản phẩm" oninput="formatCurrency(this)" required>

            <label for="categoryID">Danh mục</label>
            <select id="categoryID" name="categoryID" required>
                <?php
                while ($category = $categoryResult->fetch_assoc()) {
                    $selected = ($category['categoryID'] === $product['categoryID']) ? 'selected' : '';
                    echo "<option value='{$category['categoryID']}' $selected>{$category['categoryName']}</option>";
                }
                ?>
            </select>

            <label for="stockQuantity">Số lượng</label>
            <input type="number" id="stockQuantity" name="stockQuantity" value="<?= $product['stockQuantity']; ?>" required>

            <label for="weight">Trọng lượng (kg)</label>
            <input type="number" id="weight" name="weight" value="<?= $product['weight']; ?>" step="0.01" required>

            <label for="dimensions">Kích thước (Dài x Rộng x Cao)</label>
            <input type="text" id="dimensions" name="dimensions" value="<?= $product['dimensions']; ?>">

            <label for="isFeatured">Nổi bật</label>
            <input type="checkbox" id="isFeatured" name="isFeatured" value="1" <?= $product['isFeatured'] ? 'checked' : ''; ?>>


            <label for="imageUpload">Tải lên hình ảnh mới</label>
<input type="file" id="imageUpload" name="imageUpload" accept="image/*" onchange="previewImage(event)">

<!-- Phần hiển thị hình ảnh xem trước -->
<img id="imagePreview" src="<?= $product['imageUrl']; ?>" alt="Hình ảnh sản phẩm" width="250" height="250" style="display: block; margin-top: 10px;">

            <div class="discount-btn-container">
                <button type="submit" class="discount-btn discount-btn-success">Cập nhật sản phẩm</button>
                <a href="manage_products.php" class="discount-btn discount-btn-danger">Hủy</a>
            </div>
        </form>
    </div>

</body>
</html>
