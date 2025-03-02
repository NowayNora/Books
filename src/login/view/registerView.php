<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Ký</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
    }

    .container {
        max-width: 400px;
        margin: 50px auto;
        background: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-group label {
        display: block;
        font-weight: bold;
    }

    .form-group input {
        width: 100%;
        padding: 8px;
    }

    .error {
        color: red;
        margin-bottom: 15px;
    }

    .button {
        background-color: #28a745;
        color: white;
        border: none;
        padding: 10px 15px;
        cursor: pointer;
        border-radius: 4px;
        width: 100%;
    }

    .button:hover {
        background-color: #218838;
    }
    </style>
</head>

<body>

    <div class="container">
        <h2>Đăng Ký</h2>
        <?php if (!empty($error)) : ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form method="post" action="/login/register">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <input type="text" name="NAME" placeholder="Họ tên" required>
            <input type="email" name="EMAIL" placeholder="Email" required>
            <input type="text" name="SDT" placeholder="Số điện thoại">
            <input type="text" name="DIACHI" placeholder="Địa chỉ">
            <input type="text" name="USERNAME" placeholder="Tên đăng nhập" required>
            <input type="password" name="PASSWORD" placeholder="Mật khẩu" required>
            <input type="password" name="CONFIRM_PASSWORD" placeholder="Xác nhận mật khẩu" required>
            <button type="submit">Đăng ký</button>
            <?php if (!empty($error)) : ?>
            <p style="color:red"><?= $error ?></p>
            <?php endif; ?>
        </form>

        <p>Đã có tài khoản? <a href="/login">Đăng nhập</a></p>
    </div>

</body>

</html>