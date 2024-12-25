<?php
// Kết nối cơ sở dữ liệu
include 'C:\xampp\htdocs\Hoc_PHP\AppPetShop\header.php';
include 'C:\xampp\htdocs\Hoc_PHP\AppPetShop\config\db.php';

// Kiểm tra quyền truy cập
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'staff')) {
    echo "<div class='alert'>Bạn không có quyền truy cập trang này.</div>";
    exit();
}

// Cập nhật trạng thái của mã giảm giá
$currentDateTime = date('Y-m-d H:i:s'); // Lấy thời gian hiện tại
$updateQuery = "UPDATE discounts SET isActive = 0 WHERE endDate < ?";
$stmt = $conn->prepare($updateQuery);
$stmt->bind_param("s", $currentDateTime);
$stmt->execute();

// Phân trang
$limit = 15; // Số lượng mã giảm giá trên mỗi trang
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Lấy số trang hiện tại
$offset = ($page - 1) * $limit; // Tính toán vị trí bắt đầu

// Truy vấn dữ liệu từ bảng discounts với phân trang
$query = "SELECT * FROM discounts LIMIT ?, ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $offset, $limit);
$stmt->execute();
$result = $stmt->get_result();

// Lấy tổng số mã giảm giá để tính số trang
$totalQuery = "SELECT COUNT(*) as total FROM discounts";
$totalResult = $conn->query($totalQuery);
$totalRow = $totalResult->fetch_assoc();
$totalDiscounts = $totalRow['total'];
$totalPages = ceil($totalDiscounts / $limit);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý mã giảm giá</title>
    <link rel="stylesheet" href="../manage.css">
</head>
<body>
<div class="discount-container">
    <h2 class="discount-header">Quản lý mã giảm giá</h2>

    <!-- Nút Thêm mới -->
    <div class="mb-3 text-end">
        <a href="add_discount.php" class="discount-btn discount-btn-success">Thêm mới</a>
    </div>

    <table class="discount-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Mã giảm giá</th>
                <th>Loại giảm giá</th>
                <th>Giá trị</th>
                <th>Giá trị đơn hàng tối thiểu</th>
                <th>Giảm giá tối đa</th>
                <th>Ngày bắt đầu</th>
                <th>Ngày kết thúc</th>
                <th>Trạng thái</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['discountID'] . "</td>";
                    echo "<td>" . $row['code'] . "</td>";
                    echo "<td>" . ucfirst($row['discountType']) . "</td>";
                    echo "<td>" . number_format($row['value'], 0, ',', '.') . "</td>"; // Hiển thị giá trị với định dạng VND
                    echo "<td>" . number_format($row['minOrderValue'], 0, ',', '.') . " VND</td>"; // Hiển thị giá trị đơn hàng tối thiểu
                    echo "<td>" . number_format($row['maxDiscount'], 0, ',', '.') . " VND</td>"; // Hiển thị giảm giá tối đa
                    echo "<td>" . $row['startDate'] . "</td>";
                    echo "<td>" . $row['endDate'] . "</td>";
                    echo "<td class='" . ($row['isActive'] ? "status-active" : "status-inactive") . "'>" .
                    ($row['isActive'] ? 'Hoạt động' : 'Ngừng hoạt động') . "</td>";

                    echo "<td>
                            <a href='edit_discount.php?id=" . $row['discountID'] . "' class='discount-btn discount-btn-warning'>Sửa</a>
                            <a href='delete_discount.php?id=" . $row['discountID'] . "' class='discount-btn discount-btn-danger' onclick='return confirm(\"Bạn có chắc chắn muốn xóa không?\");'>Xóa</a>
                          </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='10' class='text-center'>Không có mã giảm giá nào</td></tr>"; // Cập nhật số cột
            }
            ?>
        </tbody>
    </table>

    <!-- Phân trang -->
    <div class="pagination">
      <?php
      for ($i = 1; $i <= $totalPages; $i++) {
          $activeClass = ($i == $page) ? 'active-page' : '';
          echo "<a href='manage_discounts.php?page=$i' class='$activeClass'>$i</a> ";
      }
      ?>
    </div>

</div>
</body>
</html>
