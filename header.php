<?php
date_default_timezone_set('Asia/Ho_Chi_Minh');

session_start();

if (!isset($_SESSION['userID']) && isset($_COOKIE['userID']) && isset($_COOKIE['userName'])) {
  $decodedUserID = base64_decode($_COOKIE['userID']);
  $decodedUserName = base64_decode($_COOKIE['userName']);

  if ($decodedUserID && $decodedUserName) { // Kiểm tra giải mã thành công
      $_SESSION['userID'] = $decodedUserID;
      $_SESSION['userName'] = $decodedUserName;
  }
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>PetShop Online</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
    integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy"
    crossorigin="anonymous"></script>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="http://localhost/Hoc_PHP/AppPetShop/css/header.css">
  </head>

<body>
  <!-- Top header mới -->
  <div class="top-header">
    <div><span>Welcome to Olivia PetShop | nhom7@gmail.com</span></div>
    <div>
      <a href="https://www.facebook.com" target="_blank"><i class="fab fa-facebook"></i></a>
      <a href="https://www.instagram.com" target="_blank"><i class="fab fa-instagram"></i></a>
      <a href="https://www.youtube.com" target="_blank"><i class="fab fa-youtube"></i></a>
      <a href="https://twitter.com" target="_blank"><i class="fab fa-twitter"></i></a>
    </div>
  </div>

  <header>
  <div class="logo">
    <a href="http://localhost/Hoc_PHP/AppPetShop/index.php"><img src="http://localhost/Hoc_PHP/AppPetShop/images/logo.png" alt="PetShop Logo"></a>
</div>
<nav>
    <ul>
        <li><a href="http://localhost/Hoc_PHP/AppPetShop/index.php">Trang chủ</a></li>
        <li class="dropdown22">
    <a href="http://localhost/Hoc_PHP/AppPetShop/products_main/products.php">Sản phẩm +</a>
    <ul class="dropdown-menu22">
        <?php
        // Giả sử kết nối database đã thành công
        include 'config/db.php';
        $query = "SELECT categoryID, categoryName FROM Categories WHERE parentCategoryID IS NULL";
        $result = $conn->query($query);

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<li><a href="http://localhost/Hoc_PHP/AppPetShop/products_main/products.php?category=' . $row['categoryID'] . '">' . htmlspecialchars($row['categoryName']) . '</a></li>';
            }
        }
        ?>
    </ul>
</li>

        <li><a href="about.php">Giới thiệu</a></li>
        <li><a href="contact.php">Liên hệ</a></li>
        <li><button class="cart-btn" id="toggle-cart-btn" ><i class="fa-solid fa-bag-shopping"></i></button></li>    </ul>
    <!-- User Status Dropdown -->
    <div id="userStatus">
    <?php if (isset($_SESSION['userID'])): ?>
        <div class="user-menu">
            <span id="welcomeMessage"><i class="fa-solid fa-user"></i> Xin chào, <?php echo htmlspecialchars($_SESSION['userName']); ?></span>
            <span class="triangle"></span> <!-- Mũi nhọn -->
            <div class="dropdown-content">
                <a href="http://localhost/Hoc_PHP/AppPetShop/settings.php">Cài đặt</a>
                <a href="http://localhost/Hoc_PHP/AppPetShop/users/profile.php">Hồ sơ</a>
                <a href="http://localhost/Hoc_PHP/AppPetShop/payment.php">Thanh toán</a>
                <a onclick="confirmLogout(event);">Đăng Xuất</a>

                <?php if ($_SESSION['role'] === 'staff' || $_SESSION['role'] === 'admin'): ?>
                    <div class="dropdown-divider"></div> <!-- Đường phân cách -->
                    <div class="dropdown-submenu">
                        <span class="dropdown-header">Quản lý</span>
                        <div class="dropdown-submenu-content">
                            <a href="http://localhost/Hoc_PHP/AppPetShop/UserManages/userManages/manage_users.php">Quản lý người dùng</a>
                            <a href="http://localhost/Hoc_PHP/AppPetShop/UserManages/productManages/manage_products.php">Quản lý hàng hóa</a>
                            <a href="http://localhost/Hoc_PHP/AppPetShop/UserManages/Discount/manage_discounts.php">Quản lý mã giảm giá</a>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if ($_SESSION['role'] === 'admin'): ?>
                    <div class="dropdown-divider"></div> <!-- Đường phân cách -->
                    <div class="dropdown-submenu">
                        <span class="dropdown-header">Quản Trị</span>
                        <div class="dropdown-submenu-content">
                            <a href="admin_panel.php">Quản Trị</a>
                            <a href="statistics.php">Thống Kê</a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php else: ?>
        <button class="login-Button" id="loginButton">Đăng Nhập</button>
    <?php endif; ?>
</div>

</nav>



      <!--  -->
      <!-- Modal Đăng Nhập -->
      <div id="loginModal" class="modal" action="login.php">
        <div class="modal-content">
          <span class="close">&times;</span>
          <h2>Đăng Nhập</h2>
          <form id="loginForm" method="POST">
            <div class="form-group">
              <label for="loginIdentifier">Email hoặc Tên người dùng:</label>
              <input type="text" id="loginIdentifier" name="identifier" required>
            </div>
            <div class="form-group">
              <label for="loginPassword">Mật khẩu:</label>
              <div class="password-group">
                <button type="button" class="show-password" onclick="togglePassword('loginPassword')">👁️</button>
                <input type="password" id="loginPassword" name="password" required>
              </div>
            </div>
            <div class="form-group">
              <div class="remember-forgot">
                <input type="checkbox" id="rememberMe" name="rememberMe">
                <label for="rememberMe">Nhớ tài khoản</label>
                <a href="#" id="forgotPasswordLink">Quên mật khẩu?</a>
              </div>
            </div>
            <button type="submit" class="btn btn-success">Đăng Nhập</button>
            <p>Bạn chưa có tài khoản? <a href="#" id="registerLink">Đăng ký ngay!</a></p>
          </form>
        </div>
      </div>
      <!-- Modal Đăng Ký -->
      <div id="registerModal" class="modal">
        <div class="modal-content" style="margin-top: 5%;">
          <h2>Đăng Ký</h2>
          <form id="registerForm">
            <div class="form-group">
              <label for="userName">Tên người dùng:</label>
              <input type="text" id="userName" name="userName" placeholder="Nhập tên người dùng" required>
            </div>
            <div class="form-group">
              <label for="registerEmail">Email:</label>
              <input type="email" id="registerEmail" name="email" placeholder="Nhập email" required>
            </div>
            <div class="form-group">
              <label for="registerPassword">Mật khẩu:</label>
              <div class="password-group">
                <button type="button" class="show-password" onclick="togglePassword('registerPassword')">👁️</button>
                <input type="password" id="registerPassword" name="password" placeholder="Nhập mật khẩu" required>
              </div>
            </div>
            <div class="form-group">
              <label for="confirmPassword">Xác nhận mật khẩu:</label>
              <div class="password-group">
                <button type="button" class="show-password" onclick="togglePassword('confirmPassword')">👁️</button>
                <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Xác nhận mật khẩu"
                  required>
              </div>
            </div>
            <div class="form-group">
              <label for="phoneNumber">Số điện thoại:</label>
              <input type="tel" id="phoneNumber" name="phoneNumber" placeholder="Nhập số điện thoại" required>
            </div>
            <input type="hidden" name="action" value="register">
            <button type="submit" class="btn btn-success">Đăng Ký</button>
            <p>Đã có tài khoản? <a href="#" id="loginLink">Đăng Nhập ngay!</a></p>
          </form>
        </div>
      </div>
      <div class="phone-number">
        <i class="fas fa-phone-alt"></i>
        <span>Call us: (123) 456-7890</span>
      </div>
    </nav>
  </header>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="http://localhost/Hoc_PHP/AppPetShop/scripts/header.js"></script>
</body>

</html>
