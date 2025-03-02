document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("resetPasswordForm");
  const messageBox = document.createElement("div");
  messageBox.classList.add("message-box");
  form.appendChild(messageBox);

  form.addEventListener("submit", function (event) {
    event.preventDefault(); // Ngăn reload trang

    let formData = new FormData(form);
    let button = form.querySelector("button");

    // Ẩn thông báo cũ trước khi gửi yêu cầu
    messageBox.style.display = "none";
    messageBox.textContent = "";
    messageBox.className = "message-box";

    // Vô hiệu hóa nút trong khi gửi request
    button.disabled = true;
    button.textContent = "Đang xử lý...";

    fetch("/forgot-password", {
      method: "POST",
      body: formData,
    })
      .then((response) => response.json())
      .then((data) => {
        messageBox.textContent = data.message;
        messageBox.classList.add(data.success ? "success" : "error");
        messageBox.style.display = "block";

        if (data.success) {
          setTimeout(() => {
            window.location.href = "/login";
          }, 2000); // Chuyển hướng sau 2 giây
        } else {
          button.disabled = false;
          button.textContent = "Xác nhận đặt lại mật khẩu";
        }
      })
      .catch((error) => {
        console.error("Lỗi:", error);
        messageBox.textContent = "Có lỗi xảy ra, vui lòng thử lại.";
        messageBox.classList.add("error");
        messageBox.style.display = "block";

        button.disabled = false;
        button.textContent = "Xác nhận đặt lại mật khẩu";
      });
  });
});
