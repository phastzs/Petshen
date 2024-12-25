<?php
ob_start(); // Bắt đầu output buffering

include 'C:\xampp\htdocs\Hoc_PHP\AppPetShop\header.php';
include 'C:\xampp\htdocs\Hoc_PHP\AppPetShop\config\db.php';

// Kiểm tra quyền truy cập
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'staff')) {
    echo "<div class='alert'>Bạn không có quyền truy cập trang này.</div>";
    exit();
}

// Xử lý dữ liệu khi biểu mẫu được gửi
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code = $_POST['code'];
    $discountType = $_POST['discountType'];
    $value = $_POST['value'];
    $minOrderValue = $_POST['minOrderValue'];
    $maxDiscount = $_POST['maxDiscount'];
    $startDate = $_POST['startDate'];
    $endDate = $_POST['endDate'];
    $isActive = isset($_POST['isActive']) ? 1 : 0;

    // Kiểm tra mã giảm giá đã tồn tại chưa
    $checkQuery = "SELECT * FROM discounts WHERE code = ?";
    $checkStmt = $conn->prepare($checkQuery);
    $checkStmt->bind_param("s", $code);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();

    if ($checkResult->num_rows > 0) {
        echo "<div class='alert'>Mã này đã tồn tại!</div>";
    } else {
        $query = "INSERT INTO discounts (code, discountType, value, minOrderValue, maxDiscount, startDate, endDate, isActive)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        // Tạo discountID mới
        $discountID = "DISC" . str_pad($conn->insert_id + 1, 5, "0", STR_PAD_LEFT);

        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssddssss", $code, $discountType, $value, $minOrderValue, $maxDiscount, $startDate, $endDate, $isActive);

        if ($stmt->execute()) {
            echo "<div class='alert alert-success'>Mã giảm giá đã được thêm thành công!</div>";
            header("Location: manage_discounts.php");
            exit();
        } else {
            echo "<div class='alert'>Đã xảy ra lỗi: " . $conn->error . "</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm mã giảm giá</title>
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

        function toggleMaxDiscount() {
            const discountType = document.getElementById('discountType').value;
            const maxDiscountContainer = document.getElementById('maxDiscountContainer');
            const maxDiscountInput = document.getElementById('maxDiscount');

            if (discountType === 'fixed') {
                maxDiscountContainer.style.display = 'none';
                maxDiscountInput.value = document.getElementById('value').value; // Gán giá trị tối đa
            } else {
                maxDiscountContainer.style.display = 'block';
            }
        }

        window.onload = toggleMaxDiscount; // Gọi hàm ngay khi trang được tải
    </script>
</head>
<body>
<div class="discount-container">
    <h2 class="discount-header">Thêm mã giảm giá</h2>

    <form method="POST" onsubmit="convertToNumber(document.getElementById('value')); convertToNumber(document.getElementById('minOrderValue')); convertToNumber(document.getElementById('maxDiscount'));">
        <label for="code">Mã giảm giá:</label>
        <input type="text" name="code" id="code" required>

        <label for="discountType">Loại giảm giá:</label>
        <select name="discountType" id="discountType" required onchange="toggleMaxDiscount()">
            <option value="percentage">Phần trăm</option>
            <option value="fixed">Giá cố định</option>
        </select>

        <label for="value">Giá trị:</label>
        <input type="text" name="value" id="value" oninput="formatCurrency(this)" required>

        <label for="minOrderValue">Giá trị đơn hàng tối thiểu:</label>
        <input type="text" name="minOrderValue" id="minOrderValue" oninput="formatCurrency(this)" value="0">

        <div id="maxDiscountContainer">
            <label for="maxDiscount">Giảm giá tối đa:</label>
            <input type="text" name="maxDiscount" id="maxDiscount" oninput="formatCurrency(this)">
        </div>

        <label for="startDate">Ngày bắt đầu:</label>
        <input type="datetime-local" name="startDate" id="startDate" required>

        <label for="endDate">Ngày kết thúc:</label>
        <input type="datetime-local" name="endDate" id="endDate" required>

        <div class="discount-checkbox">
            <label for="isActive">
                <input type="checkbox" name="isActive" id="isActive" checked>
                Hoạt động
            </label>
        </div>

        <div class="discount-btn-container">
            <button type="submit" class="discount-btn discount-btn-success">Thêm mã giảm giá</button>
            <a href="manage_discounts.php" class="discount-btn discount-btn-danger">Hủy</a>
        </div>
    </form>
</div>
</body>
</html>
