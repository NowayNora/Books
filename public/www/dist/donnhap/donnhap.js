document.getElementById("donnhap-list").addEventListener("click", function (event) {
    event.preventDefault(); // Ngăn chặn hành động mặc định của liên kết

    fetch("/donnhap") // Gửi yêu cầu GET
        .then((response) => response.text()) // Nhận dữ liệu HTML từ server
        .then((data) => {
            document.querySelector("main").innerHTML = data; // Hiển thị danh sách vào thẻ <main>
        })
        .catch((error) => console.error("Lỗi khi tải danh sách:", erhror));
});
document.querySelector("main").addEventListener("click", function (event) {
    if (event.target.classList.contains("back-donnhap-button")) {
        event.preventDefault();

        fetch("/donnhap") // Gửi yêu cầu GET
            .then((response) => response.text()) // Nhận dữ liệu HTML từ server
            .then((data) => {
                document.querySelector("main").innerHTML = data; // Hiển thị danh sách vào thẻ <main>
            })
            .catch((error) => console.error("Lỗi khi tải danh sách:", erhror));
    }
    if (event.target.classList.contains("add-donnhap-button")) {
        event.preventDefault();

        fetch("/donnhap/viewthem")
            .then((response) => response.text())
            .then((data) => {
                document.querySelector("main").innerHTML = data;
                intdonban();
            })
            .catch((error) => console.error("Lỗi khi tải trang thêm sách:", error));
    }
    if (event.target.classList.contains("edit-donnhap-button")) {
        event.preventDefault();

        const editUrl = new URL(event.target.href);
        const idDonnhap = editUrl.searchParams.get("id"); // Lấy giá trị id

        if (!idDonnhap) {
            console.error("Không tìm thấy ID đơn nhập.");
            return;
        }

        fetch(`/donnhap/viewSua?id=${encodeURIComponent(idDonnhap)}`)
            .then((response) => {
                if (!response.ok) {
                    throw new Error(`Lỗi HTTP: ${response.status}`);
                }
                return response.text();
            })
            .then((data) => {
                document.querySelector("main").innerHTML = data;
                intChinhSuadonnhap(); // Gọi hàm khởi tạo nếu có
            })
            .catch((error) =>
                console.error("Lỗi khi tải trang chỉnh sửa đơn bán:", error)
            );
    }
    // Thanh Toán 
    if (event.target.classList.contains("thanhtoan-donnhap-button")) {
        event.preventDefault();

        const url = new URL(event.target.href);
        const id = url.searchParams.get("id");
        const tinhTrang = url.searchParams.get("TINHTRANG");

        if (!id || tinhTrang === null) {
            console.error("Thiếu dữ liệu đơn nhập");
            return;
        }
        fetch(`/donnhap/thanhtoan?id=${encodeURIComponent(id)}&TINHTRANG=${encodeURIComponent(tinhTrang)}`)
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
    if (event.target.classList.contains("xoa-donnhap-button")) {
        event.preventDefault();
    
        const idDonnhap = event.target.dataset.id;
        if (!idDonnhap) {
            console.error("Thiếu ID đơn nhập");
            return;
        }
    
        const xacNhan = confirm("Bạn có chắc chắn muốn xóa đơn nhập này?");
        if (!xacNhan) return;
    
        // Gửi request xóa bằng fetch
        fetch(`/donnhap/xoa`, {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
            },
            body: `ID_DONNHAP=${encodeURIComponent(idDonnhap)}`,
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Lỗi HTTP! Mã lỗi: ${response.status}`);
                }
                return response.text();
            })
            .then(data => {
                document.querySelector("main").innerHTML = data;
                alert("✅ Đơn nhập đã được xóa thành công!"); // Thông báo thành công
            })
            .catch(error => {
                console.error("Lỗi khi xóa đơn nhập:", error);
                alert("❌ Xóa đơn nhập không thành công!"); // Thông báo thất bại
            });
    }
    // Phân Trang    
    if (event.target.classList.contains("phantrang-donnhap-button")) {
        event.preventDefault(); // Ngừng hành động mặc định của thẻ <a>

        const page = event.target.getAttribute("data-page");

        fetch(`/donnhap?page=${page}`) // Gửi yêu cầu GET
            .then((response) => response.text()) // Nhận dữ liệu HTML từ server
            .then((data) => {
                document.querySelector("main").innerHTML = data; // Hiển thị danh sách vào thẻ <main>
            })
            .catch((error) => console.error("Lỗi khi tải danh sách:", erhror));
    }
    // tiềm kiếm
    if (event.target.classList.contains("tiemkiem-donnhap-button")) {
        event.preventDefault(); // Ngừng hành động mặc định của form (không reload trang)
        const keyword = document.getElementById("keywordDB").value;
        fetch(`/donnhap?keyword=${keyword}`) // Gửi yêu cầu GET với keyword
            .then((response) => response.text())
            .then((data) => {
                document.querySelector("main").innerHTML = data;

            })
            .catch((error) => console.error("Lỗi khi tìm kiếm:", error));
    }
    // Lưu Chỉnh sửa
    if (event.target.classList.contains("saveEdit-donnhap-button")) {
        event.preventDefault();

        let form = event.target.closest("form");
        if (!form) {
            console.error("❌ Không tìm thấy form chứa nút tìm kiếm.");
            return;
        }
        let formData = new FormData(form);

        fetch(form.action, { method: "POST", body: formData, })
            .then((response) => {
                if (!response.ok) {
                    throw new Error(`Lỗi HTTP! Mã lỗi: ${response.status}`);
                }
                return response.text(); // Nếu server trả về HTML
            })
            .then((data) => {
                let mainElement = document.querySelector("main");
                if (mainElement) {
                    mainElement.innerHTML = data;
                } else {
                    console.error("❌ Không tìm thấy thẻ <main> để cập nhật dữ liệu.");
                }
            })
            .catch((error) => console.error("❌ Lỗi khi cập nhật:", error));
    }
     // Lưu đơn bán mới
    if (event.target.classList.contains("save-donnhap-button")) {
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
                    alert("✅ Đơn nhập đã được thêm thành công!");
                } else {
                    alert("❌ Thêm đơn nhập không thành công!");
                    console.error("❌ Không tìm thấy thẻ <main> để cập nhật dữ liệu.");
                }

                form.reset();
            })
            .catch((error) => console.error("❌ Lỗi khi thêm đơn nhập:", error));
    }
    
});

function showAlert(message, type) {
    if (!message || !type) {
        console.log('Không có thông báo hoặc loại thông báo hợp lệ');
        return;
    }

    var alertDiv = document.createElement('div');
    alertDiv.classList.add('alert');
    alertDiv.classList.add('alert-' + type);
    alertDiv.classList.add('alert-dismissible');
    alertDiv.classList.add('fade');
    alertDiv.classList.add('show');

    alertDiv.innerHTML = message;
    var closeButton = document.createElement('button');
    closeButton.type = 'button';
    closeButton.classList.add('btn-close');
    closeButton.setAttribute('data-bs-dismiss', 'alert');
    closeButton.setAttribute('aria-label', 'Close');

    // Thêm nút đóng vào phần tử thông báo
    alertDiv.appendChild(closeButton);

    // Tìm container có id 'alert-container' và thêm thông báo vào đó
    var alertContainer = document.getElementById('alert-container');
    if (alertContainer) {
        alertContainer.appendChild(alertDiv);
    } else {
        console.log('Không tìm thấy container alert!');
        document.body.appendChild(alertDiv);
    }

    setTimeout(function () {
        alertDiv.classList.remove('show');
        alertDiv.classList.add('fade');
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



