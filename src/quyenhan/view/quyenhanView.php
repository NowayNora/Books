<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<link rel="stylesheet" href="/www/dist/quyenhan/quyenhan.css">

<h1>Danh sách quyền</h1>
<!-- Nút mở form thêm -->
<button onclick="openAddQuyenHanModal()">Thêm quyền mới</button>

<table border="1">
    <thead>
        <tr>
            <th>ID</th>
            <th>Tên quyền</th>
            <th>Mô tả</th>
            <th>Hành động</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($quyenhans as $quyen): ?>
        <tr>
            <td><?= $quyen['ID_QUYEN'] ?></td>
            <td><?= $quyen['TENQUYEN'] ?></td>
            <td><?= $quyen['MOTAQUYEN'] ?></td>
            <td>
                <a href="" onclick="openEditQuyenModal(event, 
                    <?= $quyen['ID_QUYEN'] ?>, 
                    '<?= htmlspecialchars($quyen['TENQUYEN'], ENT_QUOTES, 'UTF-8') ?>', 
                    '<?= htmlspecialchars($quyen['MOTAQUYEN'], ENT_QUOTES, 'UTF-8') ?>')">Sửa</a>

                <form action="/quyenhan/delete" method="POST" style="display:inline;">
                    <input type="hidden" name="id_quyen" value="<?= $quyen['ID_QUYEN'] ?>" required>
                    <input type="submit" value="Xóa" onclick="return confirm('Bạncó chắc chắn muốn xóa không?');">
                    <!-- <a href="/quyenhan/delete" onclick="return confirm('Bạn có chắc chắn không?')">Xóa</a> -->
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<!-- Modal Thêm quyền -->
<div id="addQuyenHanModal" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <span class="close" onclick="closeModal('addQuyenHanModal')">×</span>
            <h2>Thêm quyền mới</h2>
            <form method="POST" action="/quyenhan/add">
                <label for="tenQuyen">Tên quyền:</label>
                <input type="text" name="tenquyen" required><br>
                <label for="moTaQuyen">Mô tả:</label>
                <textarea name="motaquyen" required></textarea><br>
                <button type="submit" name="add_quyenhan">Lưu</button>
            </form>
        </div>
    </div>
</div>

<!-- Modal Sửa quyền -->
<div class="modal fade" id="editQuyenModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Sửa quyền</h5>
                <span class="close" onclick="closeModal('editQuyenModal')">&times;</span>
            </div>
            <div class="modal-body">
                <form method="POST" action="/quyenhan/edit">
                    <input type="hidden" name="id_quyen" id="editIdQuyen" required>
                    <label for="editTenQuyen">Tên quyền:</label>
                    <input type="text" name="tenquyen" id="editTenQuyen" required>
                    <label for="editMoTaQuyen">Mô tả:</label>
                    <textarea name="motaquyen" id="editMoTaQuyen" required></textarea>
                    <button type="submit" name="update_quyenhan">Cập nhật</button>
                </form>
            </div>
        </div>
    </div>
</div>