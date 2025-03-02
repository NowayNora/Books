<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách người dùng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="/www/dist/nguoidung/nguoidung.css">
</head>

<body>

    <h1>Danh sách người dùng</h1>
    <!-- Nút mở form thêm -->
    <button onclick="openAddNguoiDungModal()">Thêm người dùng mới</button>

    <table border="1" class="Table_Margin">
        <thead>
            <tr>
                <th>ID</th>
                <th>Họ và tên</th>
                <th>Email</th>
                <th>Số điện thoại</th>
                <th>Địa chỉ</th>
                <th>Hình ảnh</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($nguoidungs)): ?>
                <?php foreach ($nguoidungs as $nguoidung): ?>
                    <tr>
                        <td><?= htmlspecialchars($nguoidung['ID_NGUOIDUNG']) ?></td>
                        <td><?= htmlspecialchars($nguoidung['NAME']) ?></td>
                        <td><?= htmlspecialchars($nguoidung['EMAIL']) ?></td>
                        <td><?= htmlspecialchars($nguoidung['SDT']) ?></td>
                        <td><?= htmlspecialchars($nguoidung['DIACHI']) ?></td>
                        <td>
                            <?php if (!empty($nguoidung['HINHANHND'])): ?>
                                <img src="data:image/jpeg;base64,<?= base64_encode($nguoidung['HINHANHND']) ?>" alt="Hình ảnh"
                                    width="100">
                            <?php else: ?>
                                Không có ảnh
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="" onclick="openEditNguoiDungModal(event, 
                        <?= $nguoidung['ID_NGUOIDUNG'] ?>, 
                        '<?= htmlspecialchars($nguoidung['NAME']) ?>', 
                        '<?= htmlspecialchars($nguoidung['EMAIL']) ?>', 
                        '<?= htmlspecialchars($nguoidung['SDT']) ?>', 
                        '<?= htmlspecialchars($nguoidung['DIACHI']) ?>')">Sửa</a>
                            <form action="/nguoidung/delete" method="POST" style="display:inline;">
                                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                <input type="hidden" name="id_nguoidung" value="<?= $nguoidung['ID_NGUOIDUNG'] ?>" required>
                                <input type="submit" value="Xóa" onclick="return confirm('Bạn có chắc chắn muốn xóa không?');">
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7">Không có dữ liệu người dùng.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Modal Thêm người dùng -->
    <div id="addNguoiDungModal" class="modal">
        <div class="modal-content">
            <!-- <span class="close" onclick="closeModal('addNguoiDungModal')">&times;</span> -->
            <h2>Thêm người dùng mới</h2>
            <form method="POST" action="/nguoidung/add" enctype="multipart/form-data"
                style="display: flex;flex-direction: column;align-content: center;align-items: center;">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <label for="name">Họ và tên:</label>
                <input type="text" name="name" required style="width: 450px;">
                <br>
                <label for="email">Email:</label>
                <input type="email" name="email" required style="width: 450px;">
                <br>
                <label for="sdt">Số điện thoại:</label>
                <input type="text" name="sdt" style="width: 450px;">
                <br>
                <label for="diachi">Địa chỉ:</label>
                <textarea name="diachi" style="width: 450px;"></textarea>
                <br>
                <label for="hinhanhnd">Hình ảnh:</label>
                <input type="file" name="hinhanhnd" accept="image/*">
                <br>
                <button type="submit" name="add_nguoidung">Lưu</button>
            </form>
        </div>
    </div>

    <!-- Modal Sửa người dùng -->
    <div class="modal fade" id="editNguoiDungModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Sửa thông tin người dùng</h5>
                    <!-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> -->
                </div>
                <div class="modal-body">
                    <form method="POST" action="/nguoidung/edit" enctype="multipart/form-data">
                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                        <input type="hidden" name="id_nguoidung" id="editId">
                        <label for="name">Họ và tên:</label>
                        <input type="text" name="name" id="editName" required>
                        <br>
                        <label for="email">Email:</label>
                        <input type="email" name="email" id="editEmail" required>
                        <br>
                        <label for="sdt">Số điện thoại:</label>
                        <input type="text" name="sdt" id="editSdt">
                        <br>
                        <label for="diachi">Địa chỉ:</label>
                        <textarea name="diachi" id="editDiachi"></textarea>
                        <br>
                        <label for="hinhanhnd">Hình ảnh:</label>
                        <input type="file" name="hinhanhnd" accept="image/*">
                        <br>
                        <button type="submit" name="update_nguoidung">Cập nhật</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</body>

</html>