<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt lại mật khẩu</title>
    <link rel="stylesheet" href="/www/dist/forgotpass/forgot.css">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
</head>

<body>
    <div class="wrapper-auth">
        <h4>Đặt lại mật khẩu</h4>
        <form method="post" action="/forgot-password" class="forgot-password-form" id="resetPasswordForm">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <h1>Reset Password</h1>
            <div class="input-box">
                <input type="text" name="USERNAME" placeholder="Username"
                    value="<?php echo htmlspecialchars($username ?? ''); ?>" required>
                <i class='bx bxs-user'></i>
            </div>
            <div class="input-box">
                <input type="password" name="newPassword" placeholder="New Password" required>
                <i class='bx bxs-lock-alt'></i>
            </div>
            <button type="submit" class="btn">Reset Password</button>
            <?php if (!empty($error)): ?>
            <p id="errorMsg" style="color:red;"><?php echo htmlspecialchars($error); ?></p>
            <?php elseif (isset($_GET['success'])): ?>
            <p id="successMsg" style="color:green;"><?php echo htmlspecialchars(urldecode($_GET['success'])); ?></p>
            <?php else: ?>
            <p id="errorMsg" style="display:none;"></p>
            <?php endif; ?>
            <div class="forgot-link">
                <a href="/">Back to Login</a>
            </div>
        </form>
    </div>
    <script src="/www/dist/forgotpass/forgot.js" defer></script>
</body>

</html>