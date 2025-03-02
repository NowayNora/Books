<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<link rel="stylesheet" href="/www/dist/taikhoan/taikhoan.css">
<h1>Danh sách tài khoản</h1>
<!-- Nút mở form thêm -->
<button onclick="openAddTaiKhoanModal()">Thêm tài khoản mới</button>

<table border="1">
    <thead>
        <tr>
            <th>ID</th>
            <th>Tên tài khoản</th>
            <th>Mật khẩu</th>
            <th>Email</th>
            <th>Người dùng</th>
            <th>Quyền hạn</th>
            <th>THời gian tạo</th>
            <th>Trạng thái</th>
            <th>Hành động</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($taikhoans)): ?>
            <?php foreach ($taikhoans as $taikhoan): ?>
                <tr>
                    <td><?= htmlspecialchars($taikhoan['ID_TAIKHOAN']) ?></td>
                    <td><?= htmlspecialchars($taikhoan['USERNAME']) ?></td>
                    <td><?= htmlspecialchars($taikhoan['PASSWORD']) ?></td>
                    <td><?= htmlspecialchars($taikhoan['nguoidung_email'] ?? 'Không có') ?></td>
                    <td>
                        <?= htmlspecialchars($taikhoan['nguoidung_name'] ?? 'Không lấy được data') ?>
                    </td>
                    <td>
                        <?= htmlspecialchars($taikhoan['quyenhan_name'] ?? 'Không lấy được data') ?>
                    </td>
                    <td><?= htmlspecialchars($taikhoan['NGAYTAO']) ?></td>
                    <td><?= htmlspecialchars($taikhoan['TRANGTHAI'] == 1 ? 'Hoạt động' : 'Khóa') ?></td>
                    <td>
                        <a href=""
                            onclick="openEditTaiKhoanModal(event, <?= $taikhoan['ID_TAIKHOAN'] ?>, 
                            '<?= htmlspecialchars($taikhoan['USERNAME'], ENT_QUOTES, 'UTF-8') ?>', 
                            '<?= htmlspecialchars($taikhoan['PASSWORD'], ENT_QUOTES, 'UTF-8') ?>', 
                            <?= $taikhoan['ID_NGUOIDUNG'] ?? 'null' ?>, <?= $taikhoan['ID_QUYEN'] ?? 'null' ?>, 
                            <?= $taikhoan['TRANGTHAI'] ?>, '<?= htmlspecialchars($taikhoan['nguoidung_name'] ?? '', ENT_QUOTES, 'UTF-8') ?>', 
                            '<?= htmlspecialchars($taikhoan['nguoidung_email'] ?? '', ENT_QUOTES, 'UTF-8') ?>', 
                            '<?= htmlspecialchars($taikhoan['nguoidung_sdt'] ?? '', ENT_QUOTES, 'UTF-8') ?>', 
                            '<?= htmlspecialchars($taikhoan['nguoidung_diachi'] ?? '', ENT_QUOTES, 'UTF-8') ?>')">Sửa</a>
                        <form action="/taikhoan/delete" method="POST" style="display:inline;">
                            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                            <input type="hidden" name="id_taikhoan" value="<?= $taikhoan['ID_TAIKHOAN'] ?>" required>
                            <input type="submit" value="Xóa" onclick="return confirm('Bạn có chắc chắn muốn xóa không?');">
                        </form> |
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="7">Không có dữ liệu tài khoản.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<!-- Modal Thêm tài khoản -->
<div class="modal fade" id="addTaiKhoanModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <span class="close" onclick="closeModal('addTaiKhoanModal')">×</span>
            <h2>Thêm tài khoản mới</h2>
            <form id="addAccountForm" method="POST" action="/taikhoan/add">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <label for="username">Tên tài khoản:</label>
                <input type="text" name="username" required><br>
                <label for="password">Mật khẩu:</label>
                <input type="password" name="password" required><br>
                <label for="id_nguoidung">Người dùng:</label>
                <select name="id_nguoidung" required>
                    <option value="">Chọn người dùng</option>
                    <?php foreach ($nguoidungs as $nguoidung): ?>
                        <option value="<?= $nguoidung['ID_NGUOIDUNG'] ?>"><?= htmlspecialchars($nguoidung['NAME']) ?>
                        </option>
                    <?php endforeach; ?>
                </select><br>
                <label for="id_quyen">Quyền hạn:</label>
                <select name="id_quyen" required>
                    <option value="">Chọn quyền hạn</option>
                    <?php foreach ($quyenhans as $quyenhan): ?>
                        <option value="<?= $quyenhan['ID_QUYEN'] ?>"><?= htmlspecialchars($quyenhan['TENQUYEN']) ?></option>
                    <?php endforeach; ?>
                </select><br>
                <button type="submit" id="addAcount">Lưu</button>
            </form>
        </div>
    </div>
</div>

<!-- updatemodal -->
<div class="modal fade" id="editTaiKhoanModal" tabindex="-1" aria-labelledby="editTaiKhoanModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <!-- <span class="close" onclick="closeModal('editTaiKhoanModal')">&times;</span> -->
            <div class="modal-header">
                <h2 class="modal-title" id="editTaiKhoanModalLabel">Sửa thông tin tài khoản</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="/taikhoan/updateTaiKhoanVaNguoiDung" enctype="multipart/form-data">
                    <input type="hidden" name="id_taikhoan" id="editId">
                    <div class="row g-3">
                        <div class="col-md-4 col-12">
                            <label for="editUsername" class="form-label">Tên tài khoản:</label>
                        </div>
                        <div class="col-md-8 col-12">
                            <input type="text" name="username" id="editUsername" class="form-control" required>
                        </div>
                        <div class="col-md-4 col-12">
                            <label for="editPassword" class="form-label">Mật khẩu:</label>
                        </div>
                        <div class="col-md-8 col-12">
                            <input type="password" name="password" id="editPassword" class="form-control" required>
                        </div>
                        <div class="col-md-4 col-12">
                            <label for="editQuyen" class="form-label">Quyền hạn:</label>
                        </div>
                        <div class="col-md-8 col-12">
                            <select name="id_quyen" id="editQuyen" class="form-select" required>
                                <option value="">Chọn quyền hạn</option>
                                <?php foreach ($quyenhans as $quyenhan): ?>
                                    <option value="<?= $quyenhan['ID_QUYEN'] ?>">
                                        <?= htmlspecialchars($quyenhan['TENQUYEN']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4 col-12">
                            <label for="editNguoiDung" class="form-label">Người dùng:</label>
                        </div>
                        <div class="col-md-8 col-12">
                            <select name="id_nguoidung" id="editNguoiDung" class="form-select" required>
                                <option value="">Chọn người dùng</option>
                                <?php foreach ($nguoidungs as $nguoidung): ?>
                                    <option value="<?= $nguoidung['ID_NGUOIDUNG'] ?>">
                                        <?= htmlspecialchars($nguoidung['NAME']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4 col-12">
                            <label for="editTrangthai" class="form-label">Trạng thái:</label>
                        </div>
                        <div class="col-md-8 col-12">
                            <select name="trangthai" id="editTrangthai" class="form-select" required>
                                <option value="1">Hoạt động</option>
                                <option value="0">Khóa</option>
                            </select>
                        </div>
                        <div class="col-md-4 col-12">
                            <label for="editName" class="form-label">Tên người dùng:</label>
                        </div>
                        <div class="col-md-8 col-12">
                            <input type="text" name="name" id="editName" class="form-control" required>
                        </div>
                        <div class="col-md-4 col-12">
                            <label for="editEmail" class="form-label">Email:</label>
                        </div>
                        <div class="col-md-8 col-12">
                            <input type="email" name="email" id="editEmail" class="form-control" required>
                        </div>
                        <div class="col-md-4 col-12">
                            <label for="editSdt" class="form-label">Số điện thoại:</label>
                        </div>
                        <div class="col-md-8 col-12">
                            <input type="text" name="sdt" id="editSdt" class="form-control" required>
                        </div>
                        <div class="col-md-4 col-12">
                            <label for="editDiachi" class="form-label">Địa chỉ:</label>
                        </div>
                        <div class="col-md-8 col-12">
                            <input type="text" name="diachi" id="editDiachi" class="form-control" required>
                        </div>
                        <div class="col-md-4 col-12">
                            <label for="editHinhanh" class="form-label">Hình ảnh:</label>
                        </div>
                        <div class="col-md-8 col-12">
                            <input type="file" name="hinhanhnd" id="editHinhanh" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer mt-3">
                        <button type="submit" class="btn btn-primary">Cập nhật</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>