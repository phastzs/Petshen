<?php
session_start();
require '../config/db.php'; // Kết nối database

// Kiểm tra người dùng đã đăng nhập chưa
if (!isset($_SESSION['userID'])) {
    header("Location: login.php");
    exit;
}

$userID = $_SESSION['userID'];
$errors = [];
$success = "";

// Lấy thông tin người dùng từ bảng `users`
$sql = "SELECT * FROM users WHERE userID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $userID);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Xử lý cập nhật thông tin
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Xử lý cập nhật email và số điện thoại
    $email = $_POST['email'];
    $phoneNumber = $_POST['phoneNumber'];

    // Cập nhật email và số điện thoại mà không thay đổi địa chỉ
    $update_sql = "UPDATE users SET email = ?, phoneNumber = ? WHERE userID = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("sss", $email, $phoneNumber, $userID);

    if ($update_stmt->execute()) {
        $success = "Thông tin đã được cập nhật thành công!";
        $user['email'] = $email;
        $user['phoneNumber'] = $phoneNumber;
    } else {
        $errors[] = "Có lỗi xảy ra khi cập nhật thông tin!";
    }

    // Xử lý cập nhật địa chỉ
    if (!empty($_POST['specificAddress']) && !empty($_POST['province']) && !empty($_POST['district']) && !empty($_POST['ward'])) {
        $specificAddress = $_POST['specificAddress'];
        $ward = $_POST['ward'];
        $district = $_POST['district'];
        $province = $_POST['province'];

        $address = "$specificAddress, $ward, $district, $province";

        $address_sql = "UPDATE users SET address = ? WHERE userID = ?";
        $address_stmt = $conn->prepare($address_sql);
        $address_stmt->bind_param("ss", $address, $userID);

        if ($address_stmt->execute()) {
            $success = "Địa chỉ đã được cập nhật thành công!";
            $user['address'] = $address;
        } else {
            $errors[] = "Có lỗi xảy ra khi cập nhật địa chỉ!";
        }
    }

    // Xử lý đổi mật khẩu
    if (!empty($_POST['oldPassword']) && !empty($_POST['newPassword']) && !empty($_POST['confirmPassword'])) {
        $oldPassword = $_POST['oldPassword'];
        $newPassword = $_POST['newPassword'];
        $confirmPassword = $_POST['confirmPassword'];

        // Kiểm tra mật khẩu cũ
        if (!password_verify($oldPassword, $user['password'])) {
            $errors[] = "Mật khẩu cũ không chính xác!";
        } elseif ($newPassword !== $confirmPassword) {
            $errors[] = "Mật khẩu mới và xác nhận mật khẩu không khớp!";
        } else {
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $password_sql = "UPDATE users SET password = ? WHERE userID = ?";
            $password_stmt = $conn->prepare($password_sql);
            $password_stmt->bind_param("ss", $hashedPassword, $userID);

            if ($password_stmt->execute()) {
                $success = "Mật khẩu đã được cập nhật thành công!";
            } else {
                $errors[] = "Có lỗi xảy ra khi đổi mật khẩu!";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hồ Sơ Cá Nhân</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="profile.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <h1>Hồ Sơ Cá Nhân</h1>

    <?php if ($success): ?>
        <p style="color: green;"><?= $success ?></p>
    <?php endif; ?>

    <?php if ($errors): ?>
        <ul style="color: red;">
            <?php foreach ($errors as $error): ?>
                <li><?= $error ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <form method="POST" action="profile.php">
        <label>Tên người dùng:</label>
        <input type="text" value="<?= htmlspecialchars($user['userName']) ?>" disabled><br>

        <label>Email:</label>
        <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required><br>

        <label>Số điện thoại:</label>
        <input type="text" name="phoneNumber" value="<?= htmlspecialchars($user['phoneNumber']) ?>" required><br>

        <label>Ngày tạo tài khoản:</label>
        <input type="text" value="<?= htmlspecialchars($user['createdAt']) ?>" disabled><br>

        <label>Địa chỉ giao hàng:</label>
        <input type="text" id="fullAddress" value="<?= htmlspecialchars($user['address']) ?>" disabled><br>

        <button type="button" id="setupAddressButton">Thiết lập địa chỉ giao hàng</button>

        <div id="addressSetup" style="display:none;">
            <label>Địa chỉ cụ thể:</label>
            <input type="text" name="specificAddress"><br>

            <label>Tỉnh:</label>
            <select id="province" name="province">
                <option value="">Chọn tỉnh</option>
            </select><br>

            <label>Huyện:</label>
            <select id="district" name="district">
                <option value="">Chọn huyện</option>
            </select><br>

            <label>Xã:</label>
            <select id="ward" name="ward">
                <option value="">Chọn xã</option>
            </select><br>
        </div>

        <h3>Đổi mật khẩu</h3>
        <button type="button" id="changePasswordButton">Thay đổi mật khẩu</button>

        <div id="changePasswordSection" style="display:none;">
            <label>Mật khẩu cũ:</label>
            <input type="password" name="oldPassword"><br>

            <label>Mật khẩu mới:</label>
            <input type="password" name="newPassword"><br>

            <label>Xác nhận mật khẩu mới:</label>
            <input type="password" name="confirmPassword"><br>
        </div>

        <button type="submit">Cập nhật</button>
    </form>

    <script>
        $(document).ready(function () {
            // API lấy danh sách tỉnh/huyện/xã
            $.getJSON("https://provinces.open-api.vn/api/p/", function (data) {
                data.forEach(province => {
                    $("#province").append(new Option(province.name, province.name));
                });
            });

            $("#province").change(function () {
                const provinceName = $(this).val();
                $("#district").empty().append(new Option("Chọn huyện", ""));
                $.getJSON("https://provinces.open-api.vn/api/p/", function (data) {
                    const selected = data.find(p => p.name === provinceName);
                    if (selected) {
                        $.getJSON(`https://provinces.open-api.vn/api/p/${selected.code}?depth=2`, function (res) {
                            res.districts.forEach(d => $("#district").append(new Option(d.name, d.name)));
                        });
                    }
                });
            });

            $("#district").change(function () {
                const districtName = $(this).val();
                $("#ward").empty().append(new Option("Chọn xã", ""));
                $.getJSON("https://provinces.open-api.vn/api/d/", function (data) {
                    const selected = data.find(d => d.name === districtName);
                    if (selected) {
                        $.getJSON(`https://provinces.open-api.vn/api/d/${selected.code}?depth=2`, function (res) {
                            res.wards.forEach(w => $("#ward").append(new Option(w.name, w.name)));
                        });
                    }
                });
            });

            $("#setupAddressButton").click(function () {
                $("#addressSetup").toggle();
            });

            $("#changePasswordButton").click(function () {
                $("#changePasswordSection").toggle();
            });
        });
    </script>
</body>
</html>
