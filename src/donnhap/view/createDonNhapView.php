<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="/www/dist/donnhap/donnhap.css">
<h3 style="text-align: center;">Thêm đơn nhập mới</h3>
<form action="/donnhap/them" method="POST" class="p-4 rounded shadow" style="background-color: #f9f9f9;">
    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
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

    <div class="mb-3 d-flex justify-content-between">
        <div class="w-50 pe-2">
            <label for="THOIGIANLAP" class="form-label fw-bold">Thời gian lập đơn</label>
            <input type="datetime-local" id="THOIGIANLAP" name="THOIGIANLAP" class="form-control" required>
        </div>
        <div class="w-50 ps-2">
            <label for="TINHTRANG" class="form-label fw-bold">Tình trạng thanh toán</label>
            <select name="TINHTRANG" id="TINHTRANG" class="form-select" required>
                <option value="0" selected>Chưa thanh toán</option>
                <option value="1">Đã thanh toán</option>
            </select>
        </div>
    </div>
    <div class="mb-3">
        <label for="NOINHAP" class="form-label fw-bold">Nơi nhập</label>
        <input type="text" id="NOINHAP" name="NOINHAP" class="form-control" required>
    </div>
    <!-- Tabs Số lượng và Giá -->
    <div class="mb-3">
        <ul class="nav nav-tabs" id="tabList" role="tablist">
            <!-- Tabs được thêm động -->
        </ul>
        <div class="tab-content" id="tabContent">
            <!-- Nội dung tab được thêm động -->
        </div>
    </div>
    <!-- Chọn sách -->
    <div class="mb-3">
        <div class="mt-2 text-center">
            <a href="#" class="btn btn-primary" id="chooseBookBtn"
                onclick='chonsach(<?php echo json_encode($sachs, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT); ?>)'>
                Thêm sách
            </a>
        </div>
    </div>
    <!-- Khu giao diện tổng kết -->
    <div class="summary-section mt-4 p-4 rounded border" style="background-color: #fff3cd; border-color: #ffeeba;">
        <h3 class="text-center mb-3" style="color: #856404; font-weight: bold;">Tổng kết đơn nhập
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

    <div class="text-center mt-4">
        <button type="submit" name="add_donnhap" class="btn btn-success btn-lg save-donnhap-button">Thêm đơn
            nhập</button>
        <button type="button" class="btn btn-secondary btn-lg mx-2 back-donnhap-button">quay
            lại</button>
    </div>
</form>