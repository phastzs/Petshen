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

// Truy vấn tổng số dòng
$totalQuery = "SELECT COUNT(*) as total FROM users";
$totalResult = $conn->query($totalQuery);
$totalRow = $totalResult->fetch_assoc();
$totalRows = $totalRow['total'];
$totalPages = ceil($totalRows / $rowsPerPage);

// Truy vấn danh sách người dùng với giới hạn
$query = "SELECT userID, userName, email, phoneNumber, role, status FROM users LIMIT $offset, $rowsPerPage";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Quản lý người dùng</title>
  <link rel="stylesheet" href="../manage.css">
</head>
<body>
  <div class="user-management-container">
    <h1 class="user-management-header">Quản lý Người Dùng</h1>

    <!-- Bảng người dùng -->
    <table class="discount-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Tên người dùng</th>
          <th>Email</th>
          <th>Số điện thoại</th>
          <th>Vai trò</th>
          <th>Trạng thái</th>
          <th>Hành động</th>
        </tr>
      </thead>
      <tbody>
        <?php
        while ($row = $result->fetch_assoc()) {
          $statusClass = $row['status'] === 'active' ? 'status-active' : 'status-inactive';
          echo "<tr>
                  <td>{$row['userID']}</td>
                  <td>{$row['userName']}</td>
                  <td>{$row['email']}</td>
                  <td>{$row['phoneNumber']}</td>
                  <td>{$row['role']}</td>
                  <td class='{$statusClass}'>" . ($row['status'] === 'active' ? 'Hoạt động' : 'Ngừng hoạt động') . "</td>
                  <td>";
          if ($_SESSION['role'] === 'admin') { // Chỉ admin có quyền active/inactive
            $actionText = $row['status'] === 'active' ? 'Inactive' : 'Active';
            $btnClass = $row['status'] === 'active' ? 'discount-btn-warning' : 'discount-btn-success';
            echo "<a href='toggle_user_status.php?id={$row['userID']}' class='discount-btn {$btnClass}'>{$actionText}</a>";
          }
          echo "</td></tr>";
        }
        ?>
      </tbody>
    </table>

    <!-- Phân trang -->
    <div class="pagination">
      <?php
      for ($i = 1; $i <= $totalPages; $i++) {
          $activeClass = ($i == $page) ? 'active-page' : '';
          echo "<a href='manage_users.php?page=$i' class='$activeClass'>$i</a> ";
      }
      ?>
    </div>
  </div>
</body>
</html>
