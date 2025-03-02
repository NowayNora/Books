document.addEventListener("DOMContentLoaded", function () {
  // Lắng nghe sự kiện submit của form xóa
  const deleteForms = document.querySelectorAll(".delete-form");
  deleteForms.forEach((form) => {
    form.addEventListener("submit", function (e) {
      e.preventDefault(); // Ngừng hành động mặc định (reload trang)

      // Hiển thị thông báo loading
      showNotification("Đang xóa...", "info");

      // Gửi request xóa
      fetch(form.action, {
        method: "POST",
        body: new FormData(form),
      })
        .then((response) => response.json())
        .then((data) => {
          // Xử lý thông báo dựa trên phản hồi
          if (data.status === "success") {
            // Thông báo thành công
            showNotification(data.message, "success");
            // Sau đó reload trang hoặc xóa sách khỏi DOM
            location.reload(); // Hoặc có thể loại bỏ sách khỏi danh sách mà không cần reload
          } else {
            // Thông báo thất bại
            showNotification(data.message, "error");
          }
        })
        .catch((error) => {
          // Nếu có lỗi kết nối
          showNotification("Có lỗi xảy ra. Vui lòng thử lại.", "error");
        });
    });
  });

  // Hàm hiển thị thông báo
  function showNotification(message, type) {
    const notification = document.createElement("div");
    notification.className = `notification ${type}`;
    notification.innerText = message;
    document.body.appendChild(notification);

    // Tự động ẩn thông báo sau 3 giây
    setTimeout(() => {
      notification.remove();
    }, 3000);
  }
});
