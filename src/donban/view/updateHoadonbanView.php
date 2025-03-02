<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="/www/dist/donban/donban.css">
<h3 style="text-align: center;">Chỉnh sửa đơn bán</h3>
<div id="chiTietDonBan" data-chi-tiet='<?= json_encode($data['chiTietDonBan']); ?>'></div>
<form action="/donban/sua?id=<?php echo $data['donban']['ID_DONBAN']; ?>" method="POST" class="p-4 rounded shadow"
    style="background-color: #f9f9f9;">
    <!-- Ẩn ID tài khoản -->
    <?php if (isset($data['error_message']) && !empty($data['error_message'])): ?>
    <div class="alert alert-danger">
        <?php echo $data['error_message']; ?>
    </div>
    <?php endif; ?>
    <!-- Thời gian lập đơn và Tình trạng đơn bán nằm cùng 1 hàng -->
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
        <!-- Thời gian lập đơn -->
        <!-- Hiển thị lên giao diện -->
        <div class="w-50 ps-2">
            <label for="THOIGIANLAPBAN" class="form-label fw-bold">Thời gian lập đơn</label>
            <!-- Đảm bảo định dạng đúng cho datetime-local -->
            <input type="datetime-local" id="THOIGIANLAPBAN" name="THOIGIANLAPBAN" class="form-control"
                value="<?php echo date('Y-m-d\TH:i', strtotime($data['donban']['THOIGIANLAPBAN'])); ?>" required>
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
                            <input type="text" id="searchBook" class="form-control form-control-sm w-auto"
                                placeholder="Tìm kiếm sách..." onkeyup="filterBooks()">
                        </div>
                        <div class="card-body overflow-auto" style="max-height: 250px;">
                            <div class="row g-2" id="bookList">
                                <?php foreach ($sachs as $sach1): ?>
                                <?php $isChecked = in_array($sach1['ID_SACH'], array_column($data['chiTietDonBan'], 'ID_SACH')); ?>
                                <div class="col-12 book-item" data-title="<?= strtolower($sach1['TENSACH']) ?>">
                                    <div class="card p-2 d-flex flex-column">
                                        <div class="form-check">
                                            <input class="form-check-input book-checkbox" type="checkbox" name="books[]"
                                                value="<?= $sach1['ID_SACH'] ?>" <?= $isChecked ? 'checked' : '' ?>
                                                onclick='chonsachCS(<?= json_encode($sach1, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT); ?>, this)'>
                                            <label class="form-check-label fw-bold" style="font-size: 0.8rem;">
                                                #<?= $sach1['TENSACH'] ?> -
                                                <span class="text-danger fw-bold">
                                                    <?= number_format($sach1['GIASACH'], 0, ',', '.') ?> VND
                                                </span>
                                            </label>
                                            <small class="text-muted small text-truncate" style="font-size: 0.75rem;">
                                                Tác giả: <strong><?= htmlspecialchars($sach1['TACGIA']) ?></strong>
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
                            <ul class="nav nav-tabs" id="tabList">
                                <?php foreach ($data['chiTietDonBan'] as $index => $sach): ?>
                                <li class="nav-item">
                                    <a class="nav-link <?= $index === 0 ? 'active' : '' ?>"
                                        id="tab-<?= $sach['ID_SACH'] ?>" data-bs-toggle="tab"
                                        href="#content-<?= $sach['ID_SACH'] ?>"
                                        aria-controls="content-<?= $sach['ID_SACH'] ?>"
                                        aria-selected="<?= $index === 0 ? 'true' : 'false' ?>">
                                        <?= htmlspecialchars($sach['TENSACH']) ?>
                                    </a>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                            <div class="tab-content" id="tabContent">
                                <?php foreach ($data['chiTietDonBan'] as $index => $sach): ?>
                                <div class="tab-pane fade <?= $index === 0 ? 'show active' : '' ?>"
                                    id="content-<?= $sach['ID_SACH'] ?>">
                                    <div class="mb-3">
                                        <label for="SACHID-<?= $sach['ID_SACH'] ?>" class="form-label fw-bold">Chọn
                                            sách:</label>
                                        <select name="sachs[<?= $sach['ID_SACH'] ?>][id]"
                                            id="SACHID-<?= $sach['ID_SACH'] ?>" class="form-select custom-select"
                                            required>
                                            <option value="<?= $sach['ID_SACH'] ?>"
                                                data-price="<?= $sach['GIASACH'] ?>">
                                                <?= $sach['TENSACH']. " (" . number_format($sach['GIASACH'], 0, ',', '.') . " VNĐ)" ?>
                                            </option>
                                        </select>
                                    </div>
                                    <div class="mb-3 d-flex justify-content-between">
                                        <div class="w-50 pe-2">
                                            <label for="SOLUONG-<?= $sach['ID_SACH'] ?>" class="form-label fw-bold">Số
                                                lượng:</label>
                                            <input type="number" name="sachs[<?= $sach['ID_SACH'] ?>][so_luong]"
                                                id="SOLUONG-<?= $sach['ID_SACH'] ?>" class="form-control" min="1"
                                                value="<?= $sach['SOLUONG'] ?>" required>
                                        </div>
                                        <div class="w-50 ps-2">
                                            <label for="THANHTIEN-<?= $sach['ID_SACH'] ?>"
                                                class="form-label fw-bold">Thành tiền:</label>
                                            <input type="number" name="sachs[<?= $sach['ID_SACH'] ?>][thanh_tien]"
                                                id="THANHTIEN-<?= $sach['ID_SACH'] ?>" class="form-control"
                                                value="<?= $sach['THANHTIEN'] ?>" readonly>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Khu giao diện tổng kết -->
    <div class="summary-section mt-4 p-4 rounded border" style="background-color: #fff3cd; border-color: #ffeeba;">
        <h3 class="text-center mb-3" style="color: #856404; font-weight: bold;">Tổng kết đơn bán</h3>
        <div class="row">
            <div class="col-6 mb-2 d-flex justify-content-center align-items-center">
                <strong>Số lượng sách:</strong>
                <span id="summary-so-luong" style="font-weight: bold; color: #856404;">
                    <?php echo $data['donban']['TONGSOSACH']; ?>
                </span> Sách
                <input type="hidden" name="TONGSOLUONG" id="TONGSOLUONGHD"
                    value="<?php echo $data['donban']['TONGSOSACH']; ?>">
            </div>
            <div class="col-6 mb-2 d-flex justify-content-center align-items-center">
                <strong>Tổng tiền:</strong>
                <span id="summary-tong-tien" style="font-weight: bold; color: #856404;">
                    <?php echo number_format($data['donban']['TONGTIEN'], 0, ',', '.'); ?>
                </span> <span> đ</span>
                <input type="hidden" name="TONGTIEN" id="TONGTIENHD" value="<?php echo $data['donban']['TONGTIEN']; ?>">
            </div>
        </div>
    </div>
    <!-- Nút lưu cập nhật & bỏ quay lại trên cùng một hàng -->
    <div class="text-center mt-4">
        <button type="submit" name="add_donban" class="btn btn-success btn-lg mx-2 saveEdit-donban-button">Lưu cập
            nhật</button>
        <button type="button" class="btn btn-secondary btn-lg mx-2 back-donban-button">Quay
            lại</button>
    </div>
</form>
<div style="margin-bottom: 50px;">
</div>