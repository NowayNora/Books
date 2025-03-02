<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="/www/dist/donnhap/donnhap.css">
<h3 style="text-align: center;">Chỉnh sửa đơn nhập</h3>
<div id="chiTietDonNhap" data-chi-tiet='<?= json_encode($data['chiTietDonNhap']); ?>'></div>
<form action="/donnhap/sua?id=<?php echo $data['donnhap']['ID_DONNHAP']; ?>" method="POST" class="p-4 rounded shadow"
    style="background-color: #f9f9f9;">
    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
    <!-- Ẩn ID tài khoản -->
    <?php if (isset($data['error_message']) && !empty($data['error_message'])): ?>
        <div class="alert alert-danger">
            <?php echo $data['error_message']; ?>
        </div>
    <?php endif; ?>
    <div class="mb-3 d-flex justify-content-between">

        <div class="w-50 pe-2">
            <label for="ID_TAIKHOAN" class="form-label fw-bold">Chọn tài khoản:</label>
            <select name="ID_TAIKHOAN" id="ID_TAIKHOAN" class="form-select" required>
                <?php foreach ($taikhoans as $taikhoan): ?>
                    <option value="<?php echo $taikhoan['ID_TAIKHOAN']; ?>"
                        <?php echo ($taikhoansHoaDon['ID_TAIKHOAN'] === $taikhoan['ID_TAIKHOAN']) ? 'selected' : ''; ?>>
                        <?php echo $taikhoan['USERNAME']; ?> (<?php echo $taikhoan['NAME']; ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="w-50 ps-2">
            <label for="THOIGIANLAP" class="form-label fw-bold">Thời gian lập đơn</label>
            <!-- Đảm bảo định dạng đúng cho datetime-local -->
            <input type="datetime-local" id="THOIGIANLAP" name="THOIGIANLAP" class="form-control"
                value="<?php echo date('Y-m-d\TH:i', strtotime($data['donnhap']['THOIGIANLAP'])); ?>" required>
        </div>
    </div>

    <div class="mb-3">
        <label for="NOINHAP" class="form-label fw-bold">Nơi nhập</label>
        <input type="text" id="NOINHAP" name="NOINHAP" class="form-control"
            value="<?php echo htmlspecialchars($data['donnhap']['NOINHAP']); ?>" required>
    </div>


    <!-- Tabs Số lượng và Giá -->
    <div class="mb-3">
        <ul class="nav nav-tabs" id="tabList">
            <?php foreach ($data['chiTietDonNhap'] as $index => $sach): ?>
                <li class="nav-item">
                    <a class="nav-link <?= $index === 0 ? 'active' : '' ?>" id="tab-<?= $index ?>" data-bs-toggle="tab"
                        href="#tab-pane-<?= $index ?>">
                        Đơn sách <?= $index + 1 ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>

        <div class="tab-content" id="tabContent">
            <?php foreach ($data['chiTietDonNhap'] as $index => $sach): ?>
                <div class="tab-pane fade <?= $index === 0 ? 'show active' : '' ?>" id="tab-pane-<?= $index ?>">
                    <div class="mb-3">
                        <label for="SACHID-<?= $index ?>" class="form-label fw-bold">Chọn sách:</label>
                        <select name="sachs[<?= $index ?>][id]" id="SACHID-<?= $index ?>" class="form-select custom-select"
                            required>
                            <option value="<?= $sach['ID_SACH'] ?>" data-price="<?= $sach['GIASACH'] ?>">
                                <?= $sach['TENSACH'] . " (" . number_format($sach['GIASACH'], 0, ',', '.') . " VNĐ)" ?>
                            </option>
                        </select>
                    </div>
                    <div class="mb-3 d-flex justify-content-between">
                        <div class="w-50 pe-2">
                            <label for="SOLUONG-<?= $index ?>" class="form-label fw-bold">Số lượng:</label>
                            <input type="number" name="sachs[<?= $index ?>][so_luong]" id="SOLUONG-<?= $index ?>"
                                class="form-control" min="1" value="<?= $sach['SOLUONG'] ?>" required>
                        </div>
                        <div class="w-50 ps-2">
                            <label for="THANHTIEN-<?= $index ?>" class="form-label fw-bold">Thành tiền:</label>
                            <input type="number" name="sachs[<?= $index ?>][thanh_tien]" id="THANHTIEN-<?= $index ?>"
                                class="form-control" value="<?= $sach['THANHTIEN'] ?>" readonly>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <!-- Chọn sách -->
    <div class="mb-3">
        <div class="mt-2 text-center">
            <a href="#" class="btn btn-primary" id="chooseBookBtn"
                onclick='chonsachCS(<?php echo json_encode($sachs, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT); ?>)'>Thêm
                sách</a>
        </div>
    </div>
    <!-- Khu giao diện tổng kết -->
    <div class="summary-section mt-4 p-4 rounded border" style="background-color: #fff3cd; border-color: #ffeeba;">
        <h3 class="text-center mb-3" style="color: #856404; font-weight: bold;">Tổng kết đơn bán</h3>
        <div class="row">
            <div class="col-6 mb-2 d-flex justify-content-center align-items-center">
                <strong>Số lượng sách:</strong>
                <span id="summary-so-luong" style="font-weight: bold; color: #856404;">
                    <?php echo $data['donnhap']['TONGSOSACH']; ?>
                </span> Sách
                <input type="hidden" name="TONGSOLUONG" id="TONGSOLUONGHD"
                    value="<?php echo $data['donnhap']['TONGSOSACH']; ?>">
            </div>
            <div class="col-6 mb-2 d-flex justify-content-center align-items-center">
                <strong>Tổng tiền:</strong>
                <span id="summary-tong-tien" style="font-weight: bold; color: #856404;">
                    <?php echo number_format($data['donnhap']['TONGTIEN'], 0, ',', '.'); ?>
                </span> <span> đ</span>
                <input type="hidden" name="TONGTIEN" id="TONGTIENHD"
                    value="<?php echo $data['donnhap']['TONGTIEN']; ?>">
            </div>
        </div>
    </div>
    <!-- Nút lưu cập nhật & bỏ quay lại trên cùng một hàng -->
    <div class="text-center mt-4">
        <button type="submit" name="add_donnhap" class="btn btn-success btn-lg mx-2 saveEdit-donnhap-button">Lưu cập
            nhật</button>
        <button type="button" class="btn btn-secondary btn-lg mx-2 back-donnhap-button">quay
            lại</button>
    </div>
</form>