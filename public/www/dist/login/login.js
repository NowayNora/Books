const container = document.querySelector(".container");
const registerBtn = document.querySelector(".resgister-btn");
const loginBtn = document.querySelector(".login-btn");

registerBtn.addEventListener("click", () => {
  container.classList.add("active");
});
loginBtn.addEventListener("click", () => {
  container.classList.remove("active");
});

// ẩn/hiện thông báo lỗi
document.querySelectorAll("input").forEach((input) => {
  input.addEventListener("input", function () {
    const errorMsg = document.getElementById("errorMsg");
    if (errorMsg) {
      errorMsg.style.display = "none";
    }
  });
});

const registerForm = document.querySelector(".register form");

registerForm.addEventListener("submit", function (event) {
  event.preventDefault(); // Ngăn chặn hành động mặc định (reload trang)

  const formData = new FormData(registerForm);
  fetch("/register", {
    method: "POST",
    body: formData,
  })
    .then((response) => response.json())
    .then((data) => {
      const errorMsg = document.getElementById("errorMsg");
      if (data.success) {
        alert(data.message); // Hiển thị thông báo đăng ký thành công
        document.querySelector(".container").classList.remove("active"); // Chuyển sang form đăng nhập
      } else {
        errorMsg.innerText = data.message;
        errorMsg.style.display = "block"; // Hiển thị lỗi nếu đăng ký thất bại
      }
    })
    .catch((error) => {
      console.error("Lỗi khi gửi dữ liệu:", error);
    });
});
const forgotPasswordForm = document.querySelector(".forgot-password-form");

if (forgotPasswordForm) {
  forgotPasswordForm.addEventListener("submit", function (event) {
    event.preventDefault();

    const formData = new FormData(forgotPasswordForm);
    fetch("/forgot-password", {
      method: "POST",
      body: formData,
    })
      .then((response) => response.json())
      .then((data) => {
        const errorMsg = document.getElementById("errorMsg");
        if (data.success) {
          alert(data.message); // Hoặc hiển thị toast
          window.location.href = "/"; // Chuyển về trang đăng nhập
        } else {
          errorMsg.innerText = data.message;
          errorMsg.style.display = "block";
        }
      })
      .catch((error) => {
        console.error("Lỗi khi gửi dữ liệu:", error);
      });
  });
}
