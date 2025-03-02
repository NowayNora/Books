<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/www/dist/login/login.css">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">

    <title>Đăng nhập</title>
</head>

<body>
    <div class="container" <?php echo isset($_GET['success']) ? '' : 'active'; ?>>
        <div class="form-box-login login">
            <form method="post" action="/" class="login-form">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <h1>Login</h1>
                <div class="input-box">
                    <input type="text" name="USERNAME" placeholder="Username" required>
                    <i class='bx bxs-user'></i>
                </div>
                <div class="input-box">
                    <input type="password" name="PASSWORD" placeholder="Password" required>
                    <i class='bx bxs-lock-alt'></i>
                </div>
                <!-- Quên mật khẩu -->
                <div class="forgot-link">
                    <a href="/forgot-password">Forgot Password?</a>
                </div>
                <button type="submit" class="btn">Login</button>
                <!-- Hiển thị lỗi nếu có -->
                <?php if (!empty($error)): ?>
                    <p id="errorMsg" style="color:red;"><?= htmlspecialchars($error) ?></p>
                <?php else: ?>
                    <p id="errorMsg" style="display:none;"></p>
                <?php endif; ?>
                <p>or login with social platforms</p>
                <!-- Đăng nhập bằng nền tảng xã hội -->
                <div class="social-icon">
                    <a href="#"><i class='bx bxl-google'></i></a>
                    <a href="#"><i class='bx bxl-facebook'></i></a>
                    <a href="#"><i class='bx bxl-github'></i></a>
                    <a href="#"><i class='bx bxl-linkedin'></i></a>
                </div>
            </form>
        </div>

        <div class="form-box register">
            <form method="post" action="/register">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <h1>Registration</h1>
                <div class="input-box">
                    <input type="text" name="NAME" placeholder="Họ tên" required>
                    <i class='bx bxs-user'></i>
                </div>
                <div class="input-box">
                    <input type="email" name="EMAIL" placeholder="Email" required>
                    <i class='bx bxs-envelope'></i>
                </div>
                <div class="input-box">
                    <input type="text" name="SDT" placeholder="Số điện thoại">
                    <i class='bx bxs-phone'></i>
                </div>
                <div class="input-box">
                    <input type="text" name="DIACHI" placeholder="Địa chỉ">
                    <i class='bx bxs-map'></i>
                </div>
                <div class="input-box">
                    <input type="text" name="USERNAME" placeholder="Tên đăng nhập" required>
                    <i class='bx bxs-user-circle'></i>
                </div>
                <div class="input-box">
                    <input type="password" name="PASSWORD" placeholder="Mật khẩu" required>
                    <i class='bx bxs-lock'></i>
                </div>
                <div class="input-box">
                    <input type="password" name="CONFIRM_PASSWORD" placeholder="Xác nhận mật khẩu" required>
                    <i class='bx bxs-check-shield'></i>
                </div>
                <button type="submit" class="btn">Register</button>
                <?php if (!empty($error)): ?>
                    <p id="errorMsg" style="color:red;"><?= htmlspecialchars($error) ?></p>
                <?php elseif (isset($_GET['success'])): ?>
                    <p id="successMsg" style="color:green;"><?= htmlspecialchars(urldecode($_GET['success'])) ?></p>
                <?php else: ?>
                    <p id="errorMsg" style="display:none;"></p>
                <?php endif; ?>
            </form>

        </div>
        <div class="toggle-box">
            <div class="toggle-panel toggle-left">
                <h1>Hello, Welcome!</h1>
                <p>Don't have an account?</p>
                <button class="btn resgister-btn">Register</button>
            </div>
            <div class="toggle-panel toggle-right">
                <h1>Welcome Back!</h1>
                <p>Already have an account?</p>
                <button class="btn login-btn">Login</button>
            </div>
        </div>
    </div>
    <p id="errorMsg" style="color:red; display:none;"></p>


    <!-- Toast thông báo lỗi -->
    <div id="toast" class="toast"></div>
    <script src="/www/dist/login/login.js"></script>
    <noscript>
        <p>Vui lòng bật JavaScript để sử dụng đầy đủ chức năng!</p>
    </noscript>
</body>

</html>