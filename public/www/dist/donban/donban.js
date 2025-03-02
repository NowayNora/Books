// Đơn bán
document
  .getElementById("donban-list")
  .addEventListener("click", function (event) {
    event.preventDefault();
    fetch("/donban")
      .then((response) => response.text())
      .then((data) => {
        document.querySelector("main").innerHTML = data;
      })
      .catch((error) => console.error("Lỗi khi tải danh sách:", error));
  });

document.querySelector("main").addEventListener("click", function (event) {
  // Quay lại
  if (event.target.classList.contains("back-donban-button")) {
    event.preventDefault();
    fetch("/donban")
      .then((response) => response.text())
      .then((data) => {
        document.querySelector("main").innerHTML = data;
      })
      .catch((error) => console.error("Lỗi khi tải danh sách:", error));
  }
  // Khi nhấn vào nút "Thêm đơn bán Mới"
  if (event.target.classList.contains("add-donban-button")) {
    event.preventDefault();
    fetch("/donban/viewthem")
      .then((response) => response.text())
      .then((data) => {
        document.querySelector("main").innerHTML = data;
        intdonban();
      })
      .catch((error) => console.error("Lỗi khi tải trang thêm sách:", error));
  }
  // Khi nhấn vào nút "chỉnh sửa đơn bán"
  if (event.target.classList.contains("edit-donban-button")) {
    event.preventDefault();
    const editUrl = new URL(event.target.href);
    const idDonban = editUrl.searchParams.get("id");
    if (!idDonban) {
      console.error("Không tìm thấy ID đơn bán.");
      return;
    }
    fetch(`/donban/viewSua?id=${encodeURIComponent(idDonban)}`)
      .then((response) => {
        if (!response.ok) {
          throw new Error(`Lỗi HTTP: ${response.status}`);
        }
        return response.text();
      })
      .then((data) => {
        document.querySelector("main").innerHTML = data;
        intChinhSuadonban();
      })
      .catch((error) =>
        console.error("Lỗi khi tải trang chỉnh sửa đơn bán:", error)
      );
  }
  // Thanh Toán
  if (event.target.classList.contains("thanhtoan-donban-button")) {
    event.preventDefault();
    const url = new URL(event.target.href);
    const id = url.searchParams.get("id");
    const tinhTrang = url.searchParams.get("TINHTRANG");
    if (!id || tinhTrang === null) {
      console.error("Thiếu dữ liệu đơn bán");
      return;
    }
    fetch(
      `/donban/thanhtoan?id=${encodeURIComponent(
        id
      )}&TINHTRANG=${encodeURIComponent(tinhTrang)}`
    )
      .then((response) => response.text())
      .then((data) => {
        document.querySelector("main").innerHTML = data;
        alert("✅ Thay đổi trạng thái thanh toán thành công!");
      })
      .catch((error) => {
        console.error("❌ Lỗi khi thực hiện thanh toán:", error);
        alert("❌ Thay đổi trạng thái thanh toán không thành công!");
      });
  }
  // Xóa đơn bán
  if (event.target.classList.contains("xoa-donban-button")) {
    event.preventDefault();
    const idDonban = event.target.dataset.id;
    if (!idDonban) {
      console.error("Thiếu ID đơn bán");
      return;
    }
    const xacNhan = confirm("Bạn có chắc chắn muốn xóa đơn bán này?");
    if (!xacNhan) return;
    fetch(`/donban/xoa`, {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body: `ID_DONBAN=${encodeURIComponent(idDonban)}`,
    })
      .then((response) => {
        if (!response.ok) {
          throw new Error(`Lỗi HTTP! Mã lỗi: ${response.status}`);
        }
        return response.text();
      })
      .then((data) => {
        document.querySelector("main").innerHTML = data;
        alert("✅ Đơn bán đã được xóa thành công!");
      })
      .catch((error) => {
        console.error("Lỗi khi xóa đơn bán:", error);
        alert("❌ Xóa đơn bán không thành công!");
      });
  }
  // Sau khi phân trang
  if (event.target.classList.contains("phantrang-donban-button")) {
    event.preventDefault();
    const page = event.target.getAttribute("data-page");
    fetch(`/donban?page=${page}`)
      .then((response) => response.text())
      .then((data) => {
        document.querySelector("main").innerHTML = data;
      })
      .catch((error) => console.error("Lỗi khi tải danh sách:", error));
  }
  // Sau khi tìm kiếm
  if (event.target.classList.contains("tiemkiem-donban-button")) {
    event.preventDefault();
    const keyword = document.getElementById("keywordDB").value;
    fetch(`/donban?keyword=${keyword}`)
      .then((response) => response.text())
      .then((data) => {
        document.querySelector("main").innerHTML = data;
      })
      .catch((error) => console.error("Lỗi khi tìm kiếm:", error));
  }
  // Lưu Chỉnh sửa
  if (event.target.classList.contains("saveEdit-donban-button")) {
    event.preventDefault();
    let form = event.target.closest("form");
    if (!form) {
      console.error("❌ Không tìm thấy form chứa nút tìm kiếm.");
      return;
    }
    let formData = new FormData(form);
    fetch(form.action, { method: "POST", body: formData })
      .then((response) => {
        if (!response.ok) {
          throw new Error(`Lỗi HTTP! Mã lỗi: ${response.status}`);
        }
        return response.text();
      })
      .then((data) => {
        let mainElement = document.querySelector("main");
        if (mainElement) {
          mainElement.innerHTML = data;
          alert("Cập nhật đơn bán thành công");
        } else {
          alert("Cập nhật đơn bán không thành công");
          console.error("❌ Không tìm thấy thẻ <main> để cập nhật dữ liệu.");
        }
      })
      .catch((error) => console.error("❌ Lỗi khi cập nhật:", error));
  }
  // Lưu đơn bán mới
  if (event.target.classList.contains("save-donban-button")) {
    event.preventDefault();
    let form = event.target.closest("form");
    if (!form) {
      console.error("❌ Không tìm thấy form chứa nút bấm.");
      return;
    }
    let formData = new FormData(form);
    fetch(form.action, {
      method: "POST",
      body: formData,
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.error_message) {
          alert(data.error_message);
        } else if (data.success_message) {
          alert(data.success_message);
          fetch("/donban")
            .then((response) => response.text())
            .then((html) => {
              let mainElement = document.querySelector("main");
              if (mainElement) {
                mainElement.innerHTML = html;
              }
            })
            .catch((error) => {
              alert("❌ Lỗi khi tải lại nội dung!");
              console.error("❌ Lỗi khi tải lại nội dung:", error);
            });
        }
      })
      .catch((error) => {
        alert("❌ Lỗi khi gửi yêu cầu!");
        console.error("❌ Lỗi khi thêm đơn bán:", error);
      });
  }
  // Chọn xuất đơn bán
  if (event.target.classList.contains("xem-donban-button")) {
    event.preventDefault();
    const editUrl = new URL(event.target.href);
    let donbanId = editUrl.searchParams.get("id");
    if (!donbanId) {
      alert("Lỗi: Không tìm thấy ID đơn bán.");
      return;
    }
    let modalBody = document.getElementById("hoaDonContent");
    if (modalBody) {
      modalBody.innerHTML = "<p class='text-center'>Đang tải dữ liệu...</p>";
    }
    fetch(`/donban/xemDonBan?id=${donbanId}`)
      .then((response) => {
        if (!response.ok) {
          throw new Error("Lỗi khi tải dữ liệu hóa đơn");
        }
        return response.text();
      })
      .then((data) => {
        if (modalBody) {
          modalBody.innerHTML = data;
        } else {
          alert("Lỗi: Không tìm thấy vùng hiển thị hóa đơn.");
          return;
        }
        let hoaDonModal = new bootstrap.Modal(
          document.getElementById("hoaDonModal")
        );
        hoaDonModal.show();
      })
      .catch((error) => {
        console.error("Lỗi:", error);
        if (modalBody) {
          modalBody.innerHTML =
            "<p class='text-center text-danger'>Không thể tải hóa đơn. Vui lòng thử lại!</p>";
        }
      });
  }
});

// Các hàm hỗ trợ khác
function showAlert(message, type) {
  if (!message || !type) {
    console.log("Không có thông báo hoặc loại thông báo hợp lệ");
    return;
  }
  var alertDiv = document.createElement("div");
  alertDiv.classList.add(
    "alert",
    "alert-" + type,
    "alert-dismissible",
    "fade",
    "show"
  );
  alertDiv.innerHTML = message;
  var closeButton = document.createElement("button");
  closeButton.type = "button";
  closeButton.classList.add("btn-close");
  closeButton.setAttribute("data-bs-dismiss", "alert");
  closeButton.setAttribute("aria-label", "Close");
  alertDiv.appendChild(closeButton);
  var alertContainer = document.getElementById("alert-container");
  if (alertContainer) {
    alertContainer.appendChild(alertDiv);
  } else {
    console.log("Không tìm thấy container alert!");
    document.body.appendChild(alertDiv);
  }
  setTimeout(function () {
    alertDiv.classList.remove("show");
    alertDiv.classList.add("fade");
    setTimeout(function () {
      alertDiv.remove();
    }, 500);
  }, 2000);
}

function showPopup(message) {
  document.getElementById("popupMessage").textContent = message;
  document.getElementById("notificationPopup").style.display = "flex";
}

function closePopup() {
  document.getElementById("notificationPopup").style.display = "none";
}

function printInvoice() {
  window.print();
  alert("In đơn bán thành công!");
}
