<?php
session_start();
if (!isset($_SESSION['user_logged_in'])) {
    header("Location: /");
    exit();
}

?>


<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang Chủ</title>
    <link rel="icon" type="image/x-icon" href="/www/dist/img_home/logo.png">
    <link rel="stylesheet" href="/www/dist/home/main.css">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
    <!-- Thêm jQuery từ CDN -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <header>
        <img src="/www/dist/img_home/logo.png" alt="logo">
        <nav>
            <ul>
                <li><a href="#" id="home-link">Trang Chủ</a></li>
                <li><a href="/book" id="book-list-link">Danh Sách Sách</a></li>
                <li><a href="/taikhoan" id="taikhoan-list">Danh sách tài khoản</a></li>
                <li><a href="/nguoidung" id="nguoidung-list">Danh sách người dùng</a></li>
                <li><a href="/quyenhan" id="quyenhan-list">Quyền hạn</a></li>
                <li><a href="/donban" id="donban-list">Đơn bán</a></li>
                <li><a href="/donnhap" id="donnhap-list">Đơn nhập</a></li>
                <li><a href="/thongke" id="thongke-list">Thống kê</a></li>

            </ul>
            <div class="auth-buttons">
                <?php if (isset($_SESSION['user_logged_in']) == true): ?>
                <button onclick="window.location.href='/login/logout'">Đăng Xuất</button>
                <?php else: ?>
                <button onclick="window.location.href='/login'">Đăng Nhập</button>
                <?php endif; ?>
            </div>
        </nav>

    </header>

    <main>

    </main>
    <footer>
        <p>© 2024 Cửa Hàng Online. All rights reserved.</p>
    </footer>


    <script src="/www/dist/home/main.js"></script>
    <!-- <script src="/www/dist/warning/notify.js"></script> -->
    <script src="/www/dist/taikhoan/taikhoan.js"></script>
    <script src="/www/dist/quyenhan/quyenhan.js"></script>
    <script src="/www/dist/donban/donban.js"></script>
    <script src="/www/dist/donnhap/donnhap.js"></script>
    <script src="/www/dist/donban/ThemDonBan.js"></script>
    <script src="/www/dist/donban/ChinhSuaDonBan.js"></script>
    <script src="/www/dist/donnhap/ThemDonNhap.js"></script>
    <script src="/www/dist/donnhap/ChinhSuaDonNhap.js"></script>
    <script src="/www/dist/thongke/thongke.js"></script>
    <script src="/www/dist/book/book.js"></script>
</body>

</html>