<?php
include 'C:\xampp\htdocs\Hoc_PHP\AppPetShop\header.php';
include 'C:\xampp\htdocs\Hoc_PHP\AppPetShop\config\db.php';

// Kiểm tra quyền truy cập
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'staff')) {
    echo "<div class='alert'>Bạn không có quyền truy cập trang này.</div>";
    exit();
}

// Thiết lập số dòng trên mỗi trang
$rowsPerPage = 15;

// Lấy trang hiện tại từ URL, nếu không có mặc định là trang 1
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $rowsPerPage;

// Lấy giá trị tìm kiếm từ biểu mẫu
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Truy vấn tổng số dòng với điều kiện tìm kiếm
$totalQuery = "SELECT COUNT(*) as total FROM products WHERE name LIKE ? OR productID LIKE ?";
$searchWildcard = "%$searchTerm%";
$stmtTotal = $conn->prepare($totalQuery);
$stmtTotal->bind_param("ss", $searchWildcard, $searchWildcard);
$stmtTotal->execute();
$totalResult = $stmtTotal->get_result();
$totalRow = $totalResult->fetch_assoc();
$totalRows = $totalRow['total'];
$totalPages = ceil($totalRows / $rowsPerPage);

// Truy vấn danh sách sản phẩm với giới hạn và điều kiện tìm kiếm
$query = "SELECT p.productID, p.name, p.description, p.price, p.categoryID, p.stockQuantity, p.weight, p.dimensions, p.isFeatured, p.imageUrl, c.categoryName
          FROM products p
          LEFT JOIN categories c ON p.categoryID = c.categoryID
          WHERE p.name LIKE ? OR p.productID LIKE ?
          LIMIT ?, ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ssii", $searchWildcard, $searchWildcard, $offset, $rowsPerPage);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Quản lý Sản phẩm</title>
  <link rel="stylesheet" href="../manage.css">
</head>
<body>
  <div class="user-management-container">
    <h1 class="user-management-header">Quản lý Sản Phẩm</h1>

    <!-- Thanh tìm kiếm -->
    <form action="manage_products.php" method="GET" class="search-form mb-3">
      <input type="text" name="search" value="<?php echo htmlspecialchars($searchTerm); ?>" placeholder="Tìm sản phẩm...">
      <button type="submit" class="discount-btn discount-btn-success">Tìm kiếm</button>
    </form>

    <!-- Nút Thêm Sản phẩm -->
    <div class="text-end mb-3">
      <a href="add_product.php" class="discount-btn discount-btn-success">Thêm Sản Phẩm</a>
    </div>

    <!-- Bảng sản phẩm -->
    <table class="discount-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Tên Sản phẩm</th>
          <th>Mô tả</th>
          <th>Giá</th>
          <th>Danh mục</th>
          <th>Số lượng</th>
          <th>Trọng lượng</th>
          <th>Kích thước</th>
          <th>Nổi bật</th>
          <th>Hình ảnh</th>
          <th>Hành động</th>
        </tr>
      </thead>
      <tbody>
        <?php
        while ($row = $result->fetch_assoc()) {
          $isFeaturedText = $row['isFeatured'] ? 'Có' : 'Không';
          echo "<tr>
                  <td>{$row['productID']}</td>
                  <td>{$row['name']}</td>
                  <td>" . nl2br($row['description']) . "</td>
                  <td>" . number_format($row['price'], 2) . " VND</td>
                  <td>{$row['categoryName']}</td>
                  <td>{$row['stockQuantity']}</td>
                  <td>{$row['weight']} kg</td>
                  <td>{$row['dimensions']}</td>
                  <td>{$isFeaturedText}</td>
                  <td><img class='product-image' src='{$row['imageUrl']}' alt='Hình ảnh sản phẩm'></td>
                  <td>
                    <a href='edit_product.php?id={$row['productID']}' class='discount-btn discount-btn-warning'>Sửa</a>
                    <a href='delete_product.php?id={$row['productID']}' class='discount-btn discount-btn-danger' onclick='return confirm(\"Bạn có chắc chắn muốn xóa sản phẩm này không?\")'>Xóa</a>
                  </td>
                </tr>";
        }
        ?>
      </tbody>
    </table>

    <!-- Phân trang -->
    <div class="pagination">
      <?php
      for ($i = 1; $i <= $totalPages; $i++) {
          $activeClass = ($i == $page) ? 'active-page' : '';
          echo "<a href='manage_products.php?page=$i&search=" . urlencode($searchTerm) . "' class='$activeClass'>$i</a> ";
      }
      ?>
    </div>
  </div>
</body>
</html>
