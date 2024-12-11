<div id="loginModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Đăng Nhập</h2>
        <form id="loginForm">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="loginEmail" name="email" required>
            </div>
            <div class="form-group">
                <label for="loginPassword">Mật khẩu:</label>
                <div class="password-group">
                    <button type="button" class="show-password" onclick="togglePassword('loginPassword')">👁️</button>
                    <input type="password" id="loginPassword" name="password" required>
                </div>
            </div>
            <div class="remember-forgot">
                <input type="checkbox" id="rememberMe" name="rememberMe">
                <label for="rememberMe">Nhớ tài khoản</label>
                <a href="#" id="forgotPasswordLink">Quên mật khẩu?</a>
            </div>
            <button type="submit" class="btn btn-success">Đăng Nhập</button>
            <p>Bạn chưa có tài khoản? <a href="#" id="registerLink">Đăng ký ngay!</a></p>
        </form>
    </div>
</div>
