  // Khai báo biến từ ID HTML
  const loginModal = document.getElementById("loginModal");
  const loginButton = document.getElementById("loginButton");
  const closeButton = document.querySelector(".close");
  const registerModal = document.getElementById("registerModal");

  // Hiện modal đăng nhập
  loginButton.onclick = () => {
      loginModal.style.display = "block";
  };

  // Đóng modal đăng nhập
  closeButton.onclick = () => {
      loginModal.style.display = "none";
  };

  // Nhấn ra ngoài sẽ ẩn modal
  window.onclick = (event) => {
      if (event.target == loginModal) {
          loginModal.style.display = "none";
      } else if (event.target == registerModal) {
          registerModal.style.display = "none"; // Đóng modal đăng ký khi nhấn ra ngoài
      }
  };

  // Chuyển đổi giữa modal đăng nhập và đăng ký
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

  // Xử lý đăng nhập
  document.getElementById("loginForm").onsubmit = async function (event) {
      event.preventDefault(); // Ngăn chặn gửi biểu mẫu mặc định
      const formData = new FormData(this);

      const response = await fetch("login.php", {
          method: "POST",
          body: formData,
      });
      const result = await response.json();

      if (result.status === "success") {
          // Thông báo đăng nhập thành công
          alert(result.message);

          // Cập nhật giao diện
          loginModal.style.display = "none"; // Đóng modal
          this.reset(); // Reset biểu mẫu
          loginButton.style.display = "none"; // Ẩn nút Đăng Nhập

          // Tải lại trang để cập nhật trạng thái
          window.location.reload(); // Tải lại trang
      } else {
          // Xử lý lỗi nếu có
          if (result.errors) {
              alert(result.errors.join("\n"));
          } else {
              alert(result.message);
          }
      }
  };

  // Xử lý đăng ký (giả sử form đăng ký có id là "registerForm")
  document.getElementById("registerForm").onsubmit = async function (event) {
      event.preventDefault(); // Ngăn chặn gửi biểu mẫu mặc định
      const formData = new FormData(this);

      const response = await fetch("register.php", {
          method: "POST",
          body: formData,
      });
      const result = await response.json();

      if (result.status === "success") {
          // Thông báo đăng ký thành công
          alert(result.message);

          // Tự động đăng nhập sau khi đăng ký thành công
          const loginResponse = await fetch("login.php", {
              method: "POST",
              body: new URLSearchParams({
                  identifier: formData.get("email"), // Hoặc tên người dùng
                  password: formData.get("password"),
              }),
          });

          const loginResult = await loginResponse.json();
          if (loginResult.status === "success") {
              loginModal.style.display = "none"; // Đóng modal đăng ký
              window.location.reload(); // Tải lại trang để cập nhật trạng thái
          }
      } else {
          // Xử lý lỗi nếu có
          if (result.errors) {
              alert(result.errors.join("\n"));
          } else {
              alert(result.message);
          }
      }
  };

  // Hiện mật khẩu
  function togglePassword(inputId) {
      const input = document.getElementById(inputId);
      const type = input.getAttribute("type") === "password" ? "text" : "password";
      input.setAttribute("type", type);
  }

  // Xử lý nút đăng xuất
  document.getElementById("logoutButton").onclick = async function () {
      const response = await fetch("logout.php"); // Tạo tệp logout.php để xử lý đăng xuất
      const result = await response.json();

      if (result.status === "success") {
          alert(result.message);
          // Cập nhật UI
          document.getElementById("welcomeMessage").style.display = "none"; // Ẩn thông báo chào mừng
          loginButton.style.display = "inline"; // Hiển thị lại nút Đăng Nhập
          document.getElementById("logoutButton").style.display = "none"; // Ẩn nút Đăng Xuất

          // Cập nhật thông tin người dùng
          document.getElementById("usernameDisplay").innerText = ""; // Xóa tên người dùng hiển thị
      }

  };
  //thoat dang nhap
function confirmLogout(event) {
    event.preventDefault(); // Ngăn chặn chuyển hướng mặc định

    Swal.fire({
        title: 'Bạn có chắc chắn muốn đăng xuất?',
        text: "Hành động này sẽ kết thúc phiên đăng nhập của bạn.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Đăng Xuất',
        cancelButtonText: 'Hủy'
    }).then((result) => {
        if (result.isConfirmed) {
            // Chuyển hướng đến logout.php nếu xác nhận
            window.location.href = '/Hoc_PHP/AppPetShop/logout.php';
        }
    });
}
