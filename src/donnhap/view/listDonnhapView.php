<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="/www/dist/donnhap/donnhap.css">

<?php if (isset($_SESSION['message'])): ?>
    <div id="message" data-type="success" data-text="<?php echo $_SESSION['message']; ?>"></div>
    <?php unset($_SESSION['message']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['error_message'])): ?>
    <div id="message" data-type="danger" data-text="<?php echo $_SESSION['error_message']; ?>"></div>
    <?php unset($_SESSION['error_message']); ?>
<?php endif; ?>

<!-- Thêm phần hiển thị popup thông báo -->
<div id="alert-container"></div>


<h3 style="text-align: center;">Quản lý đơn nhập</h3>
<table style="width: 100%; border-collapse: collapse; margin: 20px auto; text-align: center; border: 1px solid #ddd;">
    <!-- Tìm kiếm và Nút thêm mới trên cùng một hàng, căn chỉnh trái và phải -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <!-- Tìm kiếm (Căn góc trái) -->
        <div style="flex-grow: 1;">
            <form method="GET" action="" style="display: flex; justify-content: flex-start; width: 100%;">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <!-- Chỉnh sửa chiều cao input -->
                <input type="text" id="keywordDB" name="keyword" placeholder="Nhập id, tên tài khoản"
                    style="padding: 5px; margin-right: 10px; width: 30%; height: 30px;">
                <button class="tiemkiem-donnhap-button" type="submit" style="padding: 6px 12px;">Tìm kiếm</button>
            </form>
        </div>

        <!-- Nút thêm mới (Căn góc phải) -->
        <div class="add-product mb-3" style="margin-left: 20px;">
            <a class="btn btn-success btn-sm add-donnhap-button" href="/donnhap/viewthem" style="padding: 6px 12px;">
                Thêm đơn nhập mới
            </a>
        </div>
    </div>
    <thead>
        <tr style="background-color: #f2f2f2; color: #333;">
            <th>ID Đơn nhập</th>
            <th>Tài khoản</th>
            <th>Thời gian lập</th>
            <th>Nơi nhập</th>
            <th>Tổng số sách</th>
            <th>Tổng tiền</th>
            <th>Tình trạng</th>
            <th>Thao tác</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($donnhaps)): ?>
            <?php foreach ($donnhaps as $donnhap): ?>
                <tr>
                    <td><?php echo htmlspecialchars($donnhap['ID_DONNHAP']); ?></td>
                    <td>
                        <?php foreach ($taikhoans as $taikhoan): ?>
                            <?php if ($taikhoan['ID_TAIKHOAN'] == $donnhap['ID_TAIKHOAN']): ?>
                                <?php echo $taikhoan['USERNAME']; ?> (<?php echo $taikhoan['NAME']; ?>)
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </td>

                    <td><?php echo htmlspecialchars(date('d-m-Y H:i:s', strtotime($donnhap['THOIGIANLAP']))); ?></td>
                    <td><?php echo htmlspecialchars($donnhap['NOINHAP']); ?></td>
                    <td><?php echo htmlspecialchars($donnhap['TONGSOSACH']); ?></td>
                    <td><?php echo htmlspecialchars(number_format($donnhap['TONGTIEN'], 2, ',', '.')); ?> đ</td>
                    <td><?php echo $donnhap['TINHTRANG'] == 1 ? 'Thanh toán' : 'Chưa thanh toán'; ?></td>

                    <td>
                        <?php if ($donnhap['TINHTRANG'] == 0): ?>
                            <!-- Nếu TINHTRANG == 0, hiển thị nút thanh toán -->
                            <a class="thanhtoan-donnhap-button"
                                href="/donnhap/thanhtoan?id=<?php echo urlencode($donnhap['ID_DONNHAP']); ?>&TINHTRANG=<?php echo urlencode($donnhap['TINHTRANG']); ?>"
                                style="padding: 5px 10px; background-color: #2196F3; color: white; text-decoration: none; border-radius: 5px; font-weight: bold; margin-right: 5px;">
                                Thanh toán
                            </a>
                        <?php elseif ($donnhap['TINHTRANG'] == 1): ?>
                            <!-- Nếu TINHTRANG == 1, hiển thị nút hủy thanh toán -->
                            <a class="thanhtoan-donnhap-button"
                                href="/donnhap/thanhtoan?id=<?php echo urlencode($donnhap['ID_DONNHAP']); ?>&TINHTRANG=<?php echo urlencode($donnhap['TINHTRANG']); ?>"
                                style="padding: 5px 10px; background-color: #f44336; color: white; text-decoration: none; border-radius: 5px; font-weight: bold; margin-right: 5px;">
                                Hủy thanh toán
                            </a>
                        <?php endif; ?>
                        <a href="/donnhap/viewSua?id=<?php echo urlencode($donnhap['ID_DONNHAP']); ?>"
                            class="edit-donnhap-button"
                            style="padding: 5px 10px; background-color: #FFC107; color: white; text-decoration: none; border-radius: 5px; font-weight: bold; margin-right: 5px;">
                            Chỉnh sửa
                        </a>
                        <form action="/donnhap/xoa" method="POST" style="display: inline;">
                            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                            <input type="hidden" name="ID_DONNHAP" value="<?= htmlspecialchars($donnhap['ID_DONNHAP']) ?>">
                            <button type="button" class="xoa-donnhap-button"
                                data-id="<?= htmlspecialchars($donnhap['ID_DONNHAP']) ?>"
                                style="border: none; background-color: #f44336; color: white; cursor: pointer; padding: 5px 10px; border-radius: 5px; font-weight: bold;">
                                Xóa
                            </button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="7" style="color: #888; text-align: center;">Không có đơn nhập nào được tìm thấy.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
<!-- Phân trang -->
<nav aria-label="Page navigation" style="background-color:white">
    <ul class="pagination justify-content-center" style="list-style-type: none; padding: 0; border-radius: 4px;">
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <li class="page-item" style="display: inline-block; margin: 0 5px;">
                <a class="page-link phantrang-donnhap-button" href="javascript:void(0);" data-page="<?php echo $i; ?>"
                    style="background-color:black; color: white; border: 1px solid; 
                      padding: 4px 8px; font-size: 14px; text-decoration: none; border-radius: 4px; 
                      height: 30px; line-height: 22px;">
                    <?php echo $i; ?>
                </a>
            </li>
        <?php endfor; ?>
    </ul>
</nav>

<!-- Popup thông báo -->
<div id="notificationPopup" class="notification-popup">
    <div class="popup-content">
        <span class="close-btn" onclick="closePopup()">&times;</span>
        <p id="popupMessage"></p>
    </div>
</div>