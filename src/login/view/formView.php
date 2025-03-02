<form method="post" action="/login/login">
    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
    <input type="text" name="USERNAME" placeholder="Tên đăng nhập" required>
    <input type="password" name="PASSWORD" placeholder="Mật khẩu" required>
    <button type="submit">Đăng nhập</button>
    <?php if (!empty($error)) : ?>
        <p style="color:red"><?= $error ?></p>
    <?php endif; ?>
</form>
<p>Chưa có tài khoản? <a href="/register">Đăng ký</a></p>