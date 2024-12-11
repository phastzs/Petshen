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
            <button type="submit" class="btn btn-success">Đăng Ký</button>
            <p>Đã có tài khoản? <a href="#" id="loginLink"> Đăng Nhập ngay!</a></p>
        </form>
    </div>
</div>
