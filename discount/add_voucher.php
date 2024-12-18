<?php
// Kết nối cơ sở dữ liệu
include('../config/db.php');

// Kiểm tra xem người dùng có quyền admin hoặc staff không
session_start();
if (!isset($_SESSION['role']) || ($_SESSION['role'] != 'admin' && $_SESSION['role'] != 'staff')) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $code = $_POST['code'];
    $description = $_POST['description'];
    $discountType = $_POST['discountType'];
    $value = $_POST['value'];
    $minOrderValue = $_POST['minOrderValue'];
    $maxDiscount = $_POST['maxDiscount'];
    $startDate = $_POST['startDate'];
    $endDate = $_POST['endDate'];
    $discountPercent = $_POST['discountPercent'];
    $orderID = $_POST['orderID'];

    // Insert discount vào cơ sở dữ liệu
    $sql = "INSERT INTO Discount (Code, Description, DiscountType, Value, MinOrderValue, MaxDiscount, StartDate, EndDate, DiscountPercent, OrderID)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssdidddsd", $code, $description, $discountType, $value, $minOrderValue, $maxDiscount, $startDate, $endDate, $discountPercent, $orderID);

    if ($stmt->execute()) {
        echo "Thêm mã giảm giá thành công!";
    } else {
        echo "Lỗi: " . $stmt->error;
    }
}
?>

<!-- Form thêm mã giảm giá -->
<form action="" method="POST">
    <label for="code">Mã giảm giá:</label>
    <input type="text" id="code" name="code" required><br>

    <label for="description">Mô tả:</label>
    <textarea id="description" name="description"></textarea><br>

    <label for="discountType">Loại giảm giá:</label>
    <select id="discountType" name="discountType">
        <option value="Percentage">Phần trăm</option>
        <option value="Fixed">Cố định</option>
    </select><br>

    <label for="value">Giá trị giảm giá:</label>
    <input type="number" id="value" name="value" step="0.01" required><br>

    <label for="minOrderValue">Giá trị tối thiểu để áp dụng:</label>
    <input type="number" id="minOrderValue" name="minOrderValue" step="0.01"><br>

    <label for="maxDiscount">Mức giảm giá tối đa:</label>
    <input type="number" id="maxDiscount" name="maxDiscount" step="0.01"><br>

    <label for="startDate">Ngày bắt đầu:</label>
    <input type="date" id="startDate" name="startDate" required><br>

    <label for="endDate">Ngày kết thúc:</label>
    <input type="date" id="endDate" name="endDate" required><br>

    <label for="discountPercent">Phần trăm giảm giá:</label>
    <input type="number" id="discountPercent" name="discountPercent" step="0.01" required><br>

    <label for="orderID">Mã đơn hàng (nếu có):</label>
    <input type="text" id="orderID" name="orderID"><br>

    <input type="submit" value="Thêm giảm giá">
</form>
