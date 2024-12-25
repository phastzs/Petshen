<?php
ob_start(); // Bắt đầu output buffering

include 'C:\xampp\htdocs\Hoc_PHP\AppPetShop\header.php';
include 'C:\xampp\htdocs\Hoc_PHP\AppPetShop\config\db.php';

// Kiểm tra quyền truy cập
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'staff')) {
    echo "<div class='alert'>Bạn không có quyền truy cập trang này.</div>";
    exit();
}

// Lấy discountID từ URL
if (isset($_GET['id'])) {
    $discountID = $_GET['id'];

    // Truy vấn dữ liệu mã giảm giá
    $query = "SELECT * FROM discounts WHERE discountID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $discountID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo "<div class='alert'>Không tìm thấy mã giảm giá.</div>";
        exit();
    }

    $discount = $result->fetch_assoc();
} else {
    echo "<div class='alert'>Không có mã giảm giá nào được chọn.</div>";
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

    $updateQuery = "UPDATE discounts SET code=?, discountType=?, value=?, minOrderValue=?, maxDiscount=?, startDate=?, endDate=?, isActive=? WHERE discountID=?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("ssddssssi", $code, $discountType, $value, $minOrderValue, $maxDiscount, $startDate, $endDate, $isActive, $discountID);

    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Mã giảm giá đã được cập nhật thành công!</div>";
        header("Location: manage_discounts.php");
        exit();
    } else {
        echo "<div class='alert'>Đã xảy ra lỗi: " . $conn->error . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa mã giảm giá</title>
    <link rel="stylesheet" href="../manage.css">
</head>
<body>
<div class="discount-container">
    <h2 class="discount-header">Sửa mã giảm giá</h2>

    <form method="POST">
        <label for="code">Mã giảm giá:</label>
        <input type="text" name="code" id="code" value="<?php echo $discount['code']; ?>" required>

        <label for="discountType">Loại giảm giá:</label>
<select name="discountType" id="discountType" required onchange="syncFixedDiscount()">
    <option value="percentage">Phần trăm</option>
    <option value="fixed">Giá cố định</option>
</select>


        <label for="value">Giá trị:</label>
        <input type="text" name="value" id="value" oninput="formatCurrency(this)" value="<?php echo number_format($discount['value'], 0, ',', '.'); ?>" required>

        <label for="minOrderValue">Giá trị đơn hàng tối thiểu:</label>
        <input type="text" name="minOrderValue" id="minOrderValue" oninput="formatCurrency(this)" value="<?php echo number_format($discount['minOrderValue'], 0, ',', '.'); ?>" value="0">

        <label for="maxDiscount">Giảm giá tối đa:</label>
<input type="text" name="maxDiscount" id="maxDiscount" oninput="formatCurrency(this); syncFixedDiscount()">


        <label for="startDate">Ngày bắt đầu:</label>
        <input type="datetime-local" name="startDate" id="startDate" value="<?php echo date('Y-m-d\TH:i', strtotime($discount['startDate'])); ?>" required>

        <label for="endDate">Ngày kết thúc:</label>
        <input type="datetime-local" name="endDate" id="endDate" value="<?php echo date('Y-m-d\TH:i', strtotime($discount['endDate'])); ?>" required>

        <div class="discount-checkbox">
            <label for="isActive">
                <input type="checkbox" name="isActive" id="isActive" <?php echo $discount['isActive'] ? 'checked' : ''; ?>>
                Hoạt động
            </label>
        </div>

        <div class="discount-btn-container">
            <button type="submit" class="discount-btn discount-btn-success">Cập nhật mã giảm giá</button>
            <a href="manage_discounts.php" class="discount-btn discount-btn-danger">Hủy</a>
        </div>
    </form>
</div>

<script>
    function formatCurrency(input) {
        let value = input.value.replace(/\D/g, '');
        input.value = value.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }

    function convertToNumber(input) {
        let value = input.value.replace(/,/g, '');
        input.value = value;
    }

    function syncFixedDiscount() {
        const discountType = document.getElementById('discountType').value;
        const fixedValueInput = document.getElementById('value');
        const maxDiscountInput = document.getElementById('maxDiscount');

        if (discountType === 'fixed') {
            // Gán giá trị của maxDiscount cho fixedValue
            fixedValueInput.value = maxDiscountInput.value;
        }
    }
</script>
</body>
</html>
