<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="/www/dist/donban/donban.css">
<h3 style="text-align: center;">Thêm đơn bán mới</h3>
<form action="/donban/them" method="POST" class="p-4 rounded shadow" style="background-color: #f9f9f9;">
    <!-- Ẩn ID tài khoản -->
    <?php if (isset($data['error_message']) && !empty($data['error_message'])): ?>
    <div class="alert alert-danger">
        <?php echo $data['error_message']; ?>
    </div>
    <?php endif; ?>
    <div class="mb-3">
        <label for="ID_TAIKHOAN" class="form-label fw-bold">Chọn tài khoản:</label>
        <select name="ID_TAIKHOAN" id="ID_TAIKHOAN" class="form-select" required>
            <?php foreach ($taikhoans as $taikhoan): ?>
            <option value="<?php echo $taikhoan['ID_TAIKHOAN']; ?>">
                <?php echo $taikhoan['USERNAME']; ?> (<?php echo $taikhoan['NAME']; ?>)
            </option>
            <?php endforeach; ?>
        </select>
    </div>
    <!-- Thời gian lập đơn và Tình trạng đơn bán nằm cùng 1 hàng -->
    <div class="mb-3 d-flex justify-content-between">
        <!-- Thời gian lập đơn -->
        <div class="w-50 pe-2">
            <label for="THOIGIANLAPBAN" class="form-label fw-bold">Thời gian lập đơn</label>
            <input type="datetime-local" id="THOIGIANLAPBAN" name="THOIGIANLAPBAN" class="form-control" required>
        </div>
        <!-- Tình trạng đơn bán -->
        <div class="w-50 ps-2">
            <label for="TINHTRANG" class="form-label fw-bold">Tình trạng thanh toán</label>
            <select name="TINHTRANG" id="TINHTRANG" class="form-select" required>
                <option value="0" selected>Chưa thanh toán</option>
                <option value="1">Đã thanh toán</option>
            </select>
        </div>
    </div>
    <div class="mt-2">
        <div class="card p-4">
            <div class="row">
                <!-- Phần chọn sách bên trái (30%) -->
                <div class="col-md-4 col-sm-12">
                    <div class="card h-100 d-flex flex-column">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0" style="font-size: 1rem;">Chọn Sách</h5>
                            <!-- Ô tìm kiếm sách -->
                            <input type="text" id="searchBook" class="form-control form-control-sm w-auto"
                                placeholder="Tìm kiếm sách..." onkeyup="filterBooks()">
                        </div>
                        <div class="card-body overflow-auto" style="max-height: 250px;">
                            <div class="row g-2" id="bookList">
                                <?php foreach ($sachs as $sach): ?>
                                <div class="col-12 book-item" data-title="<?= strtolower($sach['TENSACH']) ?>">
                                    <div class="card p-2 d-flex flex-column">
                                        <div class="form-check">
                                            <input class="form-check-input book-checkbox" type="checkbox" name="books[]"
                                                value="<?= $sach['ID_SACH'] ?>"
                                                onclick='chonsach(<?= json_encode($sach, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT); ?>, this)'>
                                            <label class="form-check-label fw-bold" style="font-size: 0.8rem;">
                                                #<?= $sach['TENSACH'] ?> -
                                                <span class="text-danger fw-bold">
                                                    <?= number_format($sach['GIASACH'], 0, ',', '.') ?> VND
                                                </span>
                                            </label>
                                            <small class="text-muted small text-truncate" style="font-size: 0.75rem;">
                                                Tác giả: <strong><?= htmlspecialchars($sach['TACGIA']) ?></strong>
                                            </small>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Phần bên phải (70%) -->
                <div class="col-md-8 col-sm-12">
                    <div class="card h-100 d-flex flex-column">
                        <div class="card-header text-center">
                            <h5 class="mb-0" style="font-size: 1rem;">Chi tiết đơn bán</h5>
                        </div>
                        <div class="card-body">
                            <ul class="nav nav-tabs" id="tabList" role="tablist">
                                <!-- Tabs được thêm động -->
                            </ul>
                            <div class="tab-content" id="tabContent">
                                <!-- Nội dung tab được thêm động -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Khu giao diện tổng kết -->
    <div class="summary-section mt-4 p-4 rounded border" style="background-color: #fff3cd; border-color: #ffeeba;">
        <h3 class="text-center mb-3" style="color: #856404; font-weight: bold;">Tổng kết đơn bán
        </h3>
        <div class="row">
            <div class="col-6 mb-2 d-flex justify-content-center align-items-center">
                <strong>Số lượng sách:</strong>
                <span id="summary-so-luong" style="font-weight: bold; color: #856404;">0</span>
                <input type="hidden" name="TONGSOLUONG" id="TONGSOLUONGHD" value="">
            </div>
            <div class="col-6 mb-2 d-flex justify-content-center align-items-center">
                <strong>Tổng tiền:</strong>
                <span id="summary-tong-tien" style="font-weight: bold; color: #856404;">0</span>
                đ
                <input type="hidden" name="TONGTIEN" id="TONGTIENHD" value="">
            </div>
        </div>
    </div>
    <!-- Nút thêm đơn bán -->
    <div class="text-center mt-4">
        <button type="submit" name="add_donban" class="btn btn-success btn-lg save-donban-button">Thêm đơn
            bán</button>
        <button type="button" class="btn btn-secondary btn-lg mx-2 back-donban-button">Quay
            lại</button>
    </div>
</form>
<div style="margin-bottom: 50px;">
</div>