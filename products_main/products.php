<?php
include 'C:\xampp\htdocs\Hoc_PHP\AppPetShop\header.php';
include 'C:\xampp\htdocs\Hoc_PHP\AppPetShop\config\db.php';

// Số sản phẩm trên mỗi trang
$itemsPerPage = 10;

// Trang hiện tại (mặc định là 1)
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;

// Tính vị trí bắt đầu
$offset = ($page - 1) * $itemsPerPage;

// Lấy giá trị từ GET
$filter = isset($_GET['filter']) ? $_GET['filter'] : null;
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'newest';

// Tính tổng số sản phẩm
$totalItemsQuery = "SELECT COUNT(*) AS total FROM products";
if ($filter) {
    $totalItemsQuery .= " WHERE price > $filter";
}
$totalItemsResult = mysqli_query($conn, $totalItemsQuery);
$totalItemsRow = mysqli_fetch_assoc($totalItemsResult);
$totalItems = $totalItemsRow['total'];

// Tính tổng số trang
$totalPages = ceil($totalItems / $itemsPerPage);

// Tạo truy vấn chính
$query = "SELECT productID, name, description, price, imageUrl FROM products";
if ($filter) {
    $query .= " WHERE price > $filter";
}

// Thêm phần sắp xếp vào truy vấn
switch ($sort) {
    case 'high':
        $query .= " ORDER BY price DESC";
        break;
    case 'low':
        $query .= " ORDER BY price ASC";
        break;
    default:
        $query .= " ORDER BY createdAt DESC"; // Mặc định: mới nhất
}

// Thêm LIMIT và OFFSET
$query .= " LIMIT $itemsPerPage OFFSET $offset";
$result = mysqli_query($conn, $query);

// Kiểm tra lỗi
if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh Sách Sản Phẩm</title>
    <link rel="stylesheet" href="products.css">
</head>
<body>
    <div class="container">

    <div class="header">
    <div class="product-breadcrumb">
        <a href="#">Trang Chủ</a> / <span>Sản Phẩm</span>
    </div>

    <div class="price-filter">
        <i class="bi bi-cash"></i>
        <span>Xem sản phẩm theo giá</span>
        <div class="dropdown">
            <button class="dropbtn"></button>
            <div class="dropdown-content">
                <a href="?filter=200">Trên 200k</a>
                <a href="?filter=500">Trên 500k</a>
                <a href="?filter=1000">Trên 1tr</a>
                <a href="?filter=2000">Trên 2tr</a>
            </div>
        </div>
    </div>

    <div>Hiển thị 1-10 của <?php echo mysqli_num_rows($result); ?> kết quả</div>
    <form method="GET" action="">
        <select class="sort-select" name="sort" onchange="this.form.submit()">
            <option value="newest" <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'newest') ? 'selected' : ''; ?>>Mới nhất</option>
            <option value="high" <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'high') ? 'selected' : ''; ?>>Giá cao nhất</option>
            <option value="low" <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'low') ? 'selected' : ''; ?>>Giá thấp nhất</option>
        </select>
    </form>
</div>


        <!-- Danh sách sản phẩm -->
        <div class="product-grid">
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <div class="product-card">
                    <div class="product-image" style="background-image: url('../userManages/productManages/<?php echo $row['imageUrl']; ?>');"></div>
                    <div class="product-info">
                        <div><?php echo $row['name']; ?></div>
                        <div><?php echo number_format($row['price'], 0, ',', '.') . ' VND'; ?></div>

                    </div>
                    <div class="icons">
                        <a href="#" class="icon"><i class="fa-solid fa-eye"></i></i></a>
                        <a href="#" class="icon"><i class="fa-regular fa-heart"></i></i></a>
                        <a href="#" class="icon"><i class="fa-solid fa-cart-shopping"></i></i></a>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>

        <!-- Phân trang -->
        <div class="pagination">
            <?php
            if ($page > 1) {
                echo "<a href='?page=" . ($page - 1) . "&sort=$sort&filter=$filter'>&laquo; Trang trước</a>";
            }

            for ($i = 1; $i <= $totalPages; $i++) {
                $activeClass = ($i == $page) ? 'active-page' : '';
                echo "<a href='?page=$i&sort=$sort&filter=$filter' class='$activeClass'>$i</a>";
            }

            if ($page < $totalPages) {
                echo "<a href='?page=" . ($page + 1) . "&sort=$sort&filter=$filter'>Trang sau &raquo;</a>";
            }
            ?>
        </div>
    </div>

    <?php mysqli_close($conn); // Đóng kết nối ?>
</body>
</html>
