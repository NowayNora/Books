<!-- Modal Sửa tài khoản -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('editModal')">&times;</span>
        <h2>Sửa thông tin tài khoản</h2>
        <form method="POST" action="/taikhoan/edit">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <input type="hidden" name="id_taikhoan" id="editId">

            <!-- Tên tài khoản -->
            <label for="username">Tên tài khoản:</label>
            <input type="text" name="username" id="editUsername" required>
            <br>

            <!-- Email -->
            <label for="password">Email:</label>
            <input type="password" name="password" id="editPassword" required>
            <br>

            <!-- Người dùng -->
            <label for="id_nguoidung">Người dùng1 :</label>
            <select name="id_nguoidung" id="editNguoiDung" required>
                <option value="">Chọn người dùng</option>
                <?php foreach ($nguoidungs as $nguoidung): ?>
                    <option value="<?= $nguoidung['ID_NGUOIDUNG']; ?>">
                        <?= htmlspecialchars($nguoidung['NAME']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <br>

            <!-- Quyền hạn -->
            <label for="id_quyen">Quyền hạn:</label>
            <select name="id_quyen" id="editQuyen" required>
                <option value="">Chọn quyền hạn</option>
                <?php foreach ($quyenhans as $quyenhan): ?>
                    <option value="<?= $quyenhan['ID_QUYEN']; ?>">
                        <?= htmlspecialchars($quyenhan['TENQUYEN']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <br>

            <!-- trangthai -->
            <label for="trangthai">Trạng thái:</label>
            <select name="trangthai" id="editTrangthai" required>
                <option value="1" <?= $taikhoan['TRANGTHAI'] == 1 ? 'selected' : '' ?>>Hoạt động</option>
                <option value="0" <?= $taikhoan['TRANGTHAI'] == 0 ? 'selected' : '' ?>>Khóa</option>
            </select>
            <br>
            <button type="submit">Cập nhật</button>
        </form>
    </div>
</div>