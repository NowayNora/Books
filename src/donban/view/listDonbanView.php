<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="/www/dist/donban/donban.css">
<style>
    #orderList {
        display: flex;
        flex-wrap: wrap;
        max-height: 350px;
        /* Chiều cao tối đa trước khi cuộn */
        overflow-y: auto;
        /* Tạo thanh cuộn khi danh sách vượt quá 2 dòng */
        gap: 15px;
        padding: 10px;
        justify-content: flex-start;
    }

    .order-card {
        flex: 1 1 calc(33.333% - 10px);
        /* Mỗi hàng có 3 ô */
        max-width: calc(33.333% - 10px);
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 15px;
        transition: all 0.3s ease-in-out;
        cursor: pointer;
    }

    .order-card:hover {
        background: #e9ecef;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        transform: translateY(-3px);
    }

    @media (max-width: 992px) {
        .order-card {
            flex: 1 1 calc(50% - 10px);
            /* 2 ô trên mỗi hàng */
            max-width: calc(50% - 10px);
        }
    }

    @media (max-width: 576px) {
        .order-card {
            flex: 1 1 100%;
            /* 1 ô trên mỗi hàng khi màn hình nhỏ */
            max-width: 100%;
        }
    }

    .book-card .card {
        transition: transform 0.3s ease-in-out;
    }

    .book-card .card:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    .book-author {
        font-style: italic;
        color: #6c757d;
    }

    .book-price {
        font-weight: bold;
        color: #dc3545;
    }
</style>
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

<h3 style="text-align: center;">Quản lý đơn bán</h3>
<hr>
<div class="container mt-4">
    <!-- Thanh tìm kiếm và nút thêm -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <!-- Thanh tìm kiếm và nút thêm -->
        <form method="GET" action="" class="d-flex" style="width: 70%;">
            <input type="text" id="keywordDB" name="keyword" class="form-control me-2"
                placeholder="Nhập ID, tên tài khoản">
            <button class="btn btn-success btn-sm tiemkiem-donban-button" type="submit"
                style="width: 140px; height: 38px; white-space: nowrap;">Tìm kiếm</button>
        </form>
        <!-- Bộ lọc tài khoản -->
        <select id="filterAccount" class="form-select" style="width: 200px;">
            <option value="all">Tất cả tài khoản</option>
            <?php foreach ($taikhoans as $taikhoan): ?>
                <option value="<?= $taikhoan['ID_TAIKHOAN'] ?>"><?= htmlspecialchars($taikhoan['USERNAME']) ?></option>
            <?php endforeach; ?>
        </select>
        <a class="btn btn-success btn-sm add-donban-button" href="/donban/viewthem"
            style="width: 140px; height: 38px; text-align: center; display: flex; align-items: center; justify-content: center;">Thêm
            đơn bán mới</a>
    </div>
    <!-- Bảng dữ liệu -->
    <table class="table table-striped table-bordered table-hover text-center">
        <thead class="table-light">
            <tr>
                <th>Mã số đơn bán</th>
                <th>Tài khoản</th>
                <th>Thời gian lập</th>
                <th>Tổng số sách</th>
                <th>Tổng tiền</th>
                <th>Tình trạng</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($donbans)): ?>
                <?php foreach ($donbans as $donban): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($donban['ID_DONBAN']); ?></td>
                        <td>
                            <?php foreach ($taikhoans as $taikhoan): ?>
                                <?php if ($taikhoan['ID_TAIKHOAN'] == $donban['ID_TAIKHOAN']): ?>
                                    <?php echo $taikhoan['USERNAME']; ?> (<?php echo $taikhoan['NAME']; ?>)
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </td>
                        <td><?php echo htmlspecialchars(date('d-m-Y H:i:s', strtotime($donban['THOIGIANLAPBAN']))); ?></td>
                        <td><?php echo htmlspecialchars($donban['TONGSOSACH']); ?></td>
                        <td><?php echo htmlspecialchars(number_format($donban['TONGTIEN'], 2, ',', '.')); ?> đ</td>
                        <td>
                            <span class="badge <?php echo $donban['TINHTRANG'] == 1 ? 'bg-success' : 'bg-danger'; ?>">
                                <?php echo $donban['TINHTRANG'] == 1 ? 'Thanh toán' : 'Chưa thanh toán'; ?>
                            </span>
                        </td>
                        <td>
                            <a href="/donban/xemDonBan?id=<?php echo urlencode($donban['ID_DONBAN']); ?>"
                                class="btn btn-sm btn-success xem-donban-button">Xem</a>
                            <?php if ($donban['TINHTRANG'] == 0): ?>
                                <a href="/donban/thanhtoan?id=<?php echo urlencode($donban['ID_DONBAN']); ?>&TINHTRANG=<?php echo urlencode($donban['TINHTRANG']); ?>"
                                    class="btn btn-sm btn-primary thanhtoan-donban-button">Thanh toán</a>
                            <?php else: ?>
                                <a href="/donban/thanhtoan?id=<?php echo urlencode($donban['ID_DONBAN']); ?>&TINHTRANG=<?php echo urlencode($donban['TINHTRANG']); ?>"
                                    class="btn btn-sm btn-danger thanhtoan-donban-button">Hủy thanh toán</a>
                            <?php endif; ?>

                            <a href="/donban/viewSua?id=<?php echo urlencode($donban['ID_DONBAN']); ?>"
                                class="btn btn-sm btn-warning edit-donban-button">Chỉnh sửa</a>

                            <form action="/donban/xoa" method="POST" class="d-inline">
                                <input type="hidden" name="ID_DONBAN" value="<?= htmlspecialchars($donban['ID_DONBAN']) ?>">
                                <button type="button" class="btn btn-sm btn-danger xoa-donban-button"
                                    data-id="<?= htmlspecialchars($donban['ID_DONBAN']) ?>">Xóa</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" class="text-muted">Không có đơn bán nào được tìm thấy.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Phân trang -->
<div class="d-flex justify-content-center mt-3">
    <div class="w-80 d-flex justify-content-center bg-white shadow-sm p-2 rounded border"
        style="width: 85%; display: flex; justify-content: center; border: 1px solid #ddd;">
        <nav aria-label="Page navigation">
            <ul class="pagination pagination-sm mb-0">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item mx-2">
                        <a class="page-link phantrang-donban-button" href="javascript:void(0);"
                            data-page="<?php echo $i; ?>" style="background-color: #f8f9fa; color: #333; border: 1px solid #ddd; 
                              font-size: 14px; border-radius: 4px; transition: all 0.3s ease;">
                            <?php echo $i; ?>
                        </a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
    </div>
</div>

<!-- Modal Bootstrap -->
<div class="modal fade" id="hoaDonModal" tabindex="-1" aria-labelledby="hoaDonLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Hóa đơn chi tiết</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="hoaDonContent">
                <p class="text-center">Chưa có nội dung</p>
            </div>
        </div>
    </div>
</div>
<!-- Popup thông báo -->
<div id="notificationPopup" class="notification-popup">
    <div class="popup-content">
        <span class="close-btn" onclick="closePopup()">&times;</span>
        <p id="popupMessage"></p>
    </div>
</div>
<div style="margin-bottom: 50px;">
</div>