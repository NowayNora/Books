<link rel="stylesheet" href="/www/dist/book/book.css">


<?php if (!empty($books)): ?>
    <table class="book-table">
        <thead>
            <tr>
                <th>Ảnh</th>
                <th>Tên Sách</th>
                <th>Tác Giả</th>
                <th>Giá</th>
                <th>Số Lượng</th>
                <th>Hành Động</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($books as $book): ?>
                <tr>
                    <td>
                        <img src="<?= htmlspecialchars($book['HINHANHSACH']) ?>" alt="<?= htmlspecialchars($book['TENSACH']) ?>"
                            class="book-image" style="max-width: 100px; height: auto;">
                    </td>
                    <td><?= htmlspecialchars($book['TENSACH']) ?></td>
                    <td><?= htmlspecialchars($book['TACGIA']) ?></td>
                    <td><?= number_format(htmlspecialchars($book['GIASACH']), 0, ',', '.') ?> đ</td>
                    <td><?= htmlspecialchars($book['SOLUONG']) ?></td>
                    <td class="action-buttons">
                        <a href="/book/update?ID_SACH=<?= $book['ID_SACH'] ?>" class="edit-button"
                            data-id="<?= $book['ID_SACH'] ?>">
                            Sửa (ID: <?= $book['ID_SACH'] ?>)
                        </a>
                        <form action="/book/deleteBook/<?= $book['ID_SACH'] ?>" method="post" class="delete-form"
                            onsubmit="return confirm('Bạn có chắc chắn muốn xóa sách này?');">
                            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                            <button type="submit" class="delete-button">Xóa</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <div class="no-books-message">
        <p>Không có sách nào được tìm thấy.</p>
        <a href="/book/add" class="add-book-button">Thêm sách mới</a>
    </div>
<?php endif; ?>