<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PetShop Online</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/header.css">
    <style>
        /* Tổng thể Modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.6);
        }

        /* Nội dung Modal */
        .modal-content {
            background: linear-gradient(135deg, #7b2cbf, #9d4edd);
            margin: 10% auto;
            padding: 20px;
            width: 400px;
            border-radius: 15px;
            color: white;
            text-align: center;
            box-shadow: 0px 8px 15px rgba(0, 0, 0, 0.3);
        }

        /* Tiêu đề */
        .modal-content h2 {
            margin-bottom: 20px;
            font-size: 24px;
            font-weight: bold;
            color: #f1eaff;
        }

        /* Nhóm Form */
        .form-group {
            margin-bottom: 15px;
            text-align: left;
        }
        .form-group label {
            font-size: 14px;
            color: #e0c3fc;
            margin-bottom: 5px;
            display: block;
        }
        .form-group input {
            width: 95%;
            padding: 12px;
            border: none;
            border-radius: 8px;
            background-color: #f3e9ff;
            color: #5a189a;
            font-size: 14px;
            outline: none;
            transition: box-shadow 0.3s ease;
        }
        .form-group input:focus {
            box-shadow: 0px 0px 5px #d8b4fe;
        }

        /* Nút hành động */
        .btn-success {
            background-color: #5a189a;
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }
        .btn-success:hover {
            background-color: #4c1180;
            transform: scale(1.05);
        }

        /* Nút đóng */
        .close {
            position: absolute;
            top: 15px;
            right: 20px;
            color: #e0c3fc;
            font-size: 28px; /* Tăng kích thước */
            cursor: pointer;
        }
        .close:hover {
            color: white;
        }

        /* Gợi ý đăng ký */
        p {
            margin-top: 20px;
            font-size: 14px;
        }
        p a {
            color: #ffd6ff;
            text-decoration: none;
            font-weight: bold;
            transition: color 0.3s ease;
        }
        p a:hover {
            color: #ffffff;
        }
        .password-group {
    position: relative; /* Đặt vị trí cho nhóm mật khẩu */
}

.password-group .show-password {
    position: absolute; /* Đặt vị trí tuyệt đối */
    top: 5px; /* Điều chỉnh khoảng cách với ô input */
    right: 0px; /* Đặt nút hiển thị ở phía bên phải */
    background: none; /* Không có nền */
    border: none; /* Không có viền */
    cursor: pointer; /* Hiển thị con trỏ khi di chuột qua */
    color: #5a189a; /* Màu chữ cho nút */
    font-size: 20px; /* Kích thước chữ cho nút */
}
.form-group {
    margin-bottom: 15px;
    text-align: left;
}

.form-group input[type="checkbox"] {
    width: auto; /* Đặt độ rộng của ô tích */
    margin-right: 5px; /* Khoảng cách bên phải ô tích */
}

.form-group a {
    color: #5a189a; /* Màu chữ cho liên kết */
    text-decoration: none; /* Bỏ gạch chân cho liên kết */
}

.form-group a:hover {
    text-decoration: underline; /* Gạch chân liên kết khi hover */
}
.remember-forgot {
    display: flex; /* Sử dụng Flexbox để căn chỉnh các phần tử */
    align-items: center; /* Căn giữa theo chiều dọc */
    justify-content: space-between; /* Đặt khoảng cách đều giữa các phần tử */
    margin-top: 10px; /* Khoảng cách trên nhóm */
}

.remember-forgot input[type="checkbox"] {
    margin-right: 5px; /* Khoảng cách bên phải ô tích */
    margin-top: -3px;
}

.remember-forgot label {
  font-size: 17px;
  margin-right: auto; /* Đẩy nhãn gần ô tích hơn */
}

.remember-forgot a {
    margin-bottom: 8px;
    color: #5a189a; /* Màu chữ cho liên kết */
    text-decoration: none; /* Bỏ gạch chân cho liên kết */
}

.remember-forgot a:hover {
    text-decoration: underline; /* Gạch chân liên kết khi hover */
}


    </style>
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
            <a href="index.php"><img src="images/logo.png" alt="PetShop Logo"></a>
        </div>
        <nav>
            <ul>
                <li><a href="index.php">Trang chủ</a></li>
                <li><a href="products.php">Sản phẩm +</a></li>
                <li><a href="about.php">Giới thiệu</a></li>
                <li><a href="contact.php">Liên hệ</a></li>
                <li><a href="cart.php"><i class="fas fa-shopping-bag shopping-bag"></i></a></li>

            </ul>
            <button class="login-Button" id="loginButton">Đăng Nhập</button>
      <!-- Modal Đăng Nhập -->
<div id="loginModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Đăng Nhập</h2>
        <form id="loginForm">
            <div class="form-group">
                <label for="loginEmail">Email:</label> <!-- Đảm bảo for trỏ đến loginEmail -->
                <input type="email" id="loginEmail" name="email" required>
            </div>
            <div class="form-group">
                <label for="loginPassword">Mật khẩu:</label> <!-- Đảm bảo for trỏ đến loginPassword -->
                <div class="password-group">
                    <button type="button" class="show-password" onclick="togglePassword('loginPassword')">👁️</button>
                    <input type="password" id="loginPassword" name="password" required>
                </div>
            </div>
            <div class="form-group">
                <div class="remember-forgot">
                    <input type="checkbox" id="rememberMe" name="rememberMe">
                    <label for="rememberMe">Nhớ tài khoản</label> <!-- Đảm bảo for trỏ đến rememberMe -->
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
                    <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Xác nhận mật khẩu" required>
                </div>
            </div>
            <div class="form-group">
                <label for="phoneNumber">Số điện thoại:</label>
                <input type="tel" id="phoneNumber" name="phoneNumber" placeholder="Nhập số điện thoại" required>
            </div>
            <input type="hidden" name="action" value="register">
            <button type="submit" class="btn btn-success">Đăng Ký</button>
            <p>Đã có tài khoản? <a href="#" id="loginLink"> Đăng Nhập ngay!</a></p>
        </form>
    </div>
</div>



            <div class="phone-number">
                <i class="fas fa-phone-alt"></i>
                <span>Call us: (123) 456-7890</span>
            </div>
        </nav>
    </header>

<script>
  const loginModal = document.getElementById("loginModal");
  const loginButton = document.getElementById("loginButton");
  const closeButton = document.querySelector(".close");
  const registerModal = document.getElementById("registerModal");

    loginButton.onclick = () => {
        loginModal.style.display = "block";
    };

    closeButton.onclick = () => {
        loginModal.style.display = "none";
    };

    window.onclick = (event) => {
        if (event.target == loginModal) {
            loginModal.style.display = "none";
        } else if (event.target == registerModal) {
            registerModal.style.display = "none"; // Đóng modal đăng ký khi nhấn ra ngoài
        }
    };

    const registerLink = document.getElementById("registerLink");

    registerLink.onclick = (e) => {
        e.preventDefault();
        loginModal.style.display = "none";
        registerModal.style.display = "block";
    };

    const loginLink = document.getElementById("loginLink");
    loginLink.onclick = (e) => {
        e.preventDefault();
        registerModal.style.display = "none";
        loginModal.style.display = "block";
    };
// Xu lý đăng ký
document.getElementById("registerForm").onsubmit = async function (event) {
    event.preventDefault();
    const formData = new FormData(this);
    const response = await fetch("register.php", {
        method: "POST",
        body: formData,
    });

    const result = await response.json();

    if (result.status === "success") {
        alert(result.message);
        document.getElementById("registerModal").style.display = "none";
        document.getElementById("registerForm").reset();
    } else {
        if (result.errors) {
            // Hiển thị danh sách lỗi chi tiết
            alert(result.errors.join("\n"));
        } else {
            alert(result.message);
        }
    }
};



    //hien mat khau
    function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    const type = input.getAttribute("type") === "password" ? "text" : "password";
    input.setAttribute("type", type);
}



    </script>
</body>
</html>
