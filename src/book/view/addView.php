<link rel="stylesheet" href="/www/dist/book/add.css">
<div class="container">
    <h1>Thêm sách mới</h1>
    <form action="/book/add" id="addBookForm" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
        <input type="text" name="ID_LOAI" placeholder="ID Loại" required>
        <input type="text" name="TENSACH" placeholder="Tên sách" required>
        <input type="text" name="TACGIA" placeholder="Tác giả" required>
        <textarea name="MOTASACH" placeholder="Mô tả sách" required></textarea>
        <input type="text" name="NHAUXUATBAN" placeholder="Nhà xuất bản" required>
        <input type="number" name="GIASACH" placeholder="Giá sách" required>
        <input type="number" name="SOLUONG" placeholder="Số lượng" required>
        <input type="number" name="TINHTRANG" placeholder="Tình trạng" required>
        <input type="file" name="HINHANHSACH" required>

        <button type="submit" id="addBook">Thêm sách</button>
    </form>
</div>
<!-- Thêm jQuery trước khi dùng AJAX -->
<script src="/www/dist/jquery.min.js"></script>
<script>
// Xử lý sự kiện submit form thêm sách
$(document).on("submit", "#addBookForm", function(event) {
    event.preventDefault(); // Ngăn chặn reload trang

    let formData = new FormData(this); // Lấy dữ liệu từ form

    $.ajax({
        url: "/book/add",
        type: "POST",
        data: formData,
        processData: false, // Không xử lý dữ liệu
        contentType: false, // Không đặt kiểu content
        success: function(response) {
            alert("Thêm sách thành công!");
            // Tải lại danh sách sách vào <main>
            $.ajax({
                url: "/book",
                type: "GET",
                success: function(data) {
                    $("main").html(data);
                },
                error: function() {
                    alert("Không thể tải danh sách sách!");
                },
            });
        },
        error: function() {
            alert("Có lỗi xảy ra khi thêm sách!");
        },
    });
});
</script>