$(document).ready(function () {
  // Xử lý phân trang
  $(document).on("click", ".pagination-link", function (event) {
    event.preventDefault();
    const page = $(this).data("page") || 1;
    const keyword = $("#keyword").val() || "";

    $.ajax({
      url: `/book?page=${page}&keyword=${encodeURIComponent(keyword)}`,
      type: "GET",
      success: function (data) {
        $("main").html(data);
      },
      error: function (xhr, status, error) {
        console.error("Lỗi khi tải trang:", status, error);
      },
    });
  });

  // Xử lý sự kiện thêm sách
  $(document).on("click", "#add-book-button", function (event) {
    event.preventDefault();
    $.ajax({
      url: "/book/add",
      type: "GET",
      success: function (data) {
        $("main").html(data);
      },
      error: function () {
        alert("Không thể tải trang thêm sách!");
      },
    });
  });

  // Xử lý sự kiện chỉnh sửa sách
  $(document).on("click", ".edit-button", function (event) {
    event.preventDefault();
    const bookId = $(this).data("id");

    $.ajax({
      url: `/book/update?ID_SACH=${bookId}&t=${new Date().getTime()}`, // Thêm timestamp để tránh cache
      type: "GET",
      success: function (data) {
        $("main").html(data);
      },
      error: function () {
        alert("Không thể tải trang chỉnh sửa sách!");
      },
    });
  });
});
