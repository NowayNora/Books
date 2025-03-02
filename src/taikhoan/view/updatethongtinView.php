<!-- Modal Sửa tài khoản -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <span class="close" onclick="closeModal('editModal')">&times;</span>
                <h5 class="modal-title" id="editModalLabel">Chỉnh sửa tài khoản</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editAccountForm" method="POST" action="/taikhoan/edit">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    <input type="hidden" name="id_taikhoan" id="editId">
                    <label for="editUsername">Tên tài khoản:</label>
                    <input type="text" name="username" id="editUsername" required>
                    <label for="editPassword">Mật khẩu:</label>
                    <input type="password" name="password" id="editPassword" required>
                    <label for="editNguoiDung">Người dùng:</label>
                    <select name="id_nguoidung" id="editNguoiDung" required>
                        <option value="">Chọn người dùng</option>
                        <?php foreach ($nguoidungs as $nguoidung): ?>
                            <option value="<?= $nguoidung['ID_NGUOIDUNG'] ?>"><?= htmlspecialchars($nguoidung['NAME']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <label for="editQuyen">Quyền hạn:</label>
                    <select name="id_quyen" id="editQuyen" required>
                        <option value="">Chọn quyền hạn</option>
                        <?php foreach ($quyenhans as $quyenhan): ?>
                            <option value="<?= $quyenhan['ID_QUYEN'] ?>"><?= htmlspecialchars($quyenhan['TENQUYEN']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <label for="editTrangthai">Trạng thái:</label>
                    <select name="trangthai" id="editTrangthai" required>
                        <option value="1">Hoạt động</option>
                        <option value="0">Khóa</option>
                    </select>
                    <label for="editName">Tên người dùng:</label>
                    <input type="text" name="name" id="editName">
                    <label for="editEmail">Email:</label>
                    <input type="email" name="email" id="editEmail">
                    <label for="editSdt">Số điện thoại:</label>
                    <input type="text" name="sdt" id="editSdt">
                    <label for="editDiachi">Địa chỉ:</label>
                    <textarea name="diachi" id="editDiachi"></textarea>
                    <button type="submit" id="updateAccount">Cập nhật</button>
                </form>
            </div>
        </div>
    </div>
</div>