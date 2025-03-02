<!-- Thêm jQuery từ CDN -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="/www/dist/book/book.css">

<div class="book-management">
    <h1>Quản Lý Sách</h1>

    <!-- Form tìm kiếm -->
    <form class="search-form" id="searchForm" action="/book" method="get">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
        <input type="text" id="keyword" name="keyword" placeholder="Tìm kiếm sách..." aria-label="Tìm kiếm"
            value="<?= htmlspecialchars($keyword ?? '') ?>">
        <button type="submit" class="search-button">Tìm Kiếm</button>
        <a href="/book/add" id="add-book-button">Thêm Sách Mới</a>
    </form>

    <!-- Hiển thị danh sách sách -->
    <div id="book-list">
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
                                <?php if (!empty($book['HINHANHSACH'])): ?>
                                    <img src="<?= htmlspecialchars($book['HINHANHSACH']) ?>"
                                        style="max-width: 100px; height: auto;">
                                <?php else: ?>
                                    <img src="/uploads/book.png" alt="Ảnh mặc định" class="book-image"
                                        style="max-width: 100px; height: auto;">
                                <?php endif; ?>

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
                                <form action="/book/delete" method="post" class="delete-form"
                                    onsubmit="return confirm('Bạn có chắc chắn muốn xóa sách này?');">
                                    <input type="hidden" name="ID_SACH" value="<?= $book['ID_SACH'] ?>">
                                    <button type="submit" class="delete-button">Xóa</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <!-- Phân trang -->
            <div class="pagination">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a href="/book?page=<?= $i ?>&keyword=<?= urlencode($keyword ?? '') ?>"
                        class="page-number pagination-link <?= ($i == $currentPage) ? 'active' : '' ?>" data-page="<?= $i ?>">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>
            </div>

        <?php else: ?>
            <div class="no-books-message">
                <p>Không có sách nào được tìm thấy.</p>
                <a href="/book/add" class="add-book-button">Thêm sách mới</a>
            </div>
        <?php endif; ?>
    </div>
</div>