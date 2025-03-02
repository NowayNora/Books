<?php
// Xử lý ảnh BLOB thành base64 (nếu cần)
if (!empty($book['HINHANHSACH'])) {
    $book['HINHANHSACH'] = 'data:image/jpeg;base64,' . base64_encode($book['HINHANHSACH']);
}
?>
<link rel="stylesheet" href="/www/dist/book/update.css">

<div class="container">
    <h1>Chỉnh sửa thông tin sách</h1>
    <form id="update-form" action="/book/update" method="post" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
        <input type="hidden" name="ID_SACH" value="<?= htmlspecialchars($book['ID_SACH'] ?? '') ?>">

        <label>Tên Sách:</label>
        <input type="text" name="TENSACH" value="<?= htmlspecialchars($book['TENSACH'] ?? '') ?>" required>

        <label>Loại Sách:</label>
        <select name="ID_LOAI" required>
            <option value="">-- Chọn loại sách --</option>
            <?php foreach ($loaiSachs as $loai) : ?>
                <option value="<?= htmlspecialchars($loai['ID_LOAI']) ?>"
                    <?= ($loai['ID_LOAI'] == ($book['ID_LOAI'] ?? '')) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($loai['TENLOAI']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>Tác Giả:</label>
        <input type="text" name="TACGIA" value="<?= htmlspecialchars($book['TACGIA'] ?? '') ?>" required>

        <label>Mô Tả:</label>
        <textarea name="MOTASACH" required><?= htmlspecialchars($book['MOTASACH'] ?? '') ?></textarea>

        <label>Nhà Xuất Bản:</label>
        <input type="text" name="NHAUXUATBAN" value="<?= htmlspecialchars($book['NHAUXUATBAN'] ?? '') ?>" required>

        <label>Giá Sách:</label>
        <input type="number" name="GIASACH" value="<?= htmlspecialchars($book['GIASACH'] ?? 0) ?>" required>

        <label>Số Lượng:</label>
        <input type="number" name="SOLUONG" value="<?= htmlspecialchars($book['SOLUONG'] ?? 0) ?>" required>

        <label>Tình Trạng:</label>
        <select name="TINHTRANG" required>
            <option value="1" <?= ($book['TINHTRANG'] ?? '') == 1 ? 'selected' : '' ?>>Còn hàng</option>
            <option value="0" <?= ($book['TINHTRANG'] ?? '') == 0 ? 'selected' : '' ?>>Hết hàng</option>
        </select>

        <div class="image-preview">
            <?php if (!empty($book['HINHANHSACH'])) : ?>
                <img src="<?= $book['HINHANHSACH'] ?>" alt="Ảnh sách">
            <?php endif; ?>
        </div>

        <button id="update-button" type="submit">Cập Nhật</button>
    </form>
</div>