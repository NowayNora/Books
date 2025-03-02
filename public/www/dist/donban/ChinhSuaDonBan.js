let sttdonCS = 0;
function intChinhSuadonban() {
  // Lấy các phần tử DOM cần thiết
  const tabList = document.getElementById("tabList");
  sttdonCS = tabList.querySelectorAll(".nav-item").length + 1; // Tính số thứ tự tiếp theo của đơn bán
  // Lấy dữ liệu từ thẻ div
  const chiTietDonBanElement = document.getElementById("chiTietDonBan");
  if (!chiTietDonBanElement) return;

  // Parse JSON từ thuộc tính data-chi-tiet
  // Parse JSON từ thuộc tính data-chi-tiet
  const chiTietDonBan = JSON.parse(chiTietDonBanElement.dataset.chiTiet);

  chiTietDonBan.forEach((sach) => {
    const selectSach = document.querySelector(`#SACHID-${sach.ID_SACH}`);
    const inputSoLuong = document.querySelector(`#SOLUONG-${sach.ID_SACH}`);

    if (selectSach && inputSoLuong) {
      selectSach.addEventListener("change", () =>
        updateTabTotalByPHPCS(sach.ID_SACH)
      );
      inputSoLuong.addEventListener("input", () =>
        updateTabTotalByPHPCS(sach.ID_SACH)
      );
    }
  });

  // Tính toán tổng lần đầu
  updateTotalPriceAndQuantityCS();
}

function chonsachCS(sach, checkbox) {
  const tabId = `tab-${sach.ID_SACH}`;
  const tabPaneId = `content-${sach.ID_SACH}`;

  if (!checkbox.checked) {
    huysachs(sach.ID_SACH);
    return;
  }

  if (!document.getElementById(tabId)) {
    createTabCS(tabId, tabPaneId, sach.TENSACH);
    createTabContentCS(tabPaneId, sach);
  }
  activateTabCS(tabId, tabPaneId);
}

function createTabCS(tabId, tabPaneId, bookName) {
  const tabList = document.getElementById("tabList");
  const tab = document.createElement("li");
  tab.classList.add("nav-item");
  tab.innerHTML = `<a class="nav-link" id="${tabId}" data-bs-toggle="tab" href="#${tabPaneId}" aria-controls="${tabPaneId}">${bookName}</a>`;
  tabList.appendChild(tab);
}

function createTabContentCS(tabPaneId, sach) {
  const tabContent = document.getElementById("tabContent");
  const tabPane = document.createElement("div");
  tabPane.classList.add("tab-pane", "fade");
  tabPane.id = tabPaneId;
  tabPane.innerHTML = `
        <div class="mb-3">
            <label class="form-label fw-bold">Tên sách:</label>
            <select class="form-select" name="sachs[${
              sach.ID_SACH
            }][id]" id="SACHID-${sach.ID_SACH}" required>
                <option value="${sach.ID_SACH}" data-price="${sach.GIASACH}">
                    ${sach.TENSACH} (${new Intl.NumberFormat("vi-VN").format(
    sach.GIASACH
  )} VNĐ)
                </option>
            </select>
        </div>
        <div class="mb-3 d-flex justify-content-between">
            <div class="w-50 pe-2">
                <label for="SOLUONG-${
                  sach.ID_SACH
                }" class="form-label fw-bold">Số lượng:</label>
                <input type="number" name="sachs[${
                  sach.ID_SACH
                }][so_luong]" id="SOLUONG-${
    sach.ID_SACH
  }" class="form-control" min="1" value="0" required>
            </div>
            <div class="w-50 ps-2">
                <label for="THANHTIEN-${
                  sach.ID_SACH
                }" class="form-label fw-bold">Thành tiền:</label>
                <input type="number" name="sachs[${
                  sach.ID_SACH
                }][thanh_tien]" id="THANHTIEN-${
    sach.ID_SACH
  }" class="form-control" step="0.01" value="0.00" readonly>
            </div>
        </div>`;
  tabContent.appendChild(tabPane);

  document
    .querySelector(`#SOLUONG-${sach.ID_SACH}`)
    .addEventListener("input", () => updateTabTotalByPHPCS(sach.ID_SACH));
  document
    .querySelector(`#SACHID-${sach.ID_SACH}`)
    .addEventListener("change", () => updateTabTotalByPHPCS(sach.ID_SACH));
  updateTotalPriceAndQuantityCS();
}

function activateTabCS(tabId, tabPaneId) {
  document.querySelectorAll(".nav-link").forEach((tab) => {
    tab.classList.remove("active");
    tab.setAttribute("aria-selected", "false");
  });
  document.querySelectorAll(".tab-pane").forEach((tabPane) => {
    tabPane.classList.remove("show", "active");
  });

  document.querySelector(`#${tabId}`).classList.add("active");
  document.querySelector(`#${tabId}`).setAttribute("aria-selected", "true");
  document.querySelector(`#${tabPaneId}`).classList.add("show", "active");
}
function huysachs(bookId) {
  const tabId = `tab-${bookId}`;
  const tabPaneId = `content-${bookId}`;

  const tabElement = document.getElementById(tabId);
  const tabPaneElement = document.getElementById(tabPaneId);

  // Kiểm tra xem tab bị xóa có đang hiển thị không
  const isActive = tabElement && tabElement.classList.contains("active");

  if (tabElement) tabElement.remove();
  if (tabPaneElement) tabPaneElement.remove();

  // Bỏ chọn checkbox tương ứng
  const bookCheckbox = document.querySelector(
    `.book-checkbox[value="${bookId}"]`
  );
  if (bookCheckbox) bookCheckbox.checked = false;

  updateTotalPriceAndQuantityCS(); // Cập nhật tổng giá trị đơn hàng

  const remainingTabs = document.querySelectorAll(".nav-link");

  if (remainingTabs.length > 0) {
    if (isActive) {
      // Chuyển sang tab cuối cùng còn lại
      const lastTab = remainingTabs[remainingTabs.length - 1];
      const lastTabPaneId = lastTab.getAttribute("href")?.replace("#", "");

      if (lastTabPaneId) {
        activateTabCS(lastTab.id, lastTabPaneId);
      }
    }
  }
}

function updateTabTotalByPHPCS(index) {
  const selectSach = document.querySelector(`#SACHID-${index}`);
  const inputSoLuong = document.querySelector(`#SOLUONG-${index}`);
  const inputThanhTien = document.querySelector(`#THANHTIEN-${index}`);

  if (!selectSach || !inputSoLuong || !inputThanhTien) return;

  const selectedOption = selectSach.options[selectSach.selectedIndex];
  const price = parseFloat(selectedOption.getAttribute("data-price")) || 0;
  const quantity = parseInt(inputSoLuong.value) || 0;

  inputThanhTien.value = (price * quantity).toFixed(2);
  updateTotalPriceAndQuantityCS();
}

function updateTotalPriceAndQuantityCS() {
  let totalPrice = 0;
  let totalQuantity = 0;

  document.querySelectorAll('[id^="THANHTIEN-"]').forEach((input) => {
    totalPrice += parseFloat(input.value) || 0;
  });
  document.querySelectorAll('[id^="SOLUONG-"]').forEach((input) => {
    totalQuantity += parseInt(input.value) || 0;
  });

  document.getElementById("TONGTIENHD").value = totalPrice.toFixed(2);
  document.getElementById("TONGSOLUONGHD").value = totalQuantity;
  document.getElementById("summary-tong-tien").textContent =
    totalPrice.toFixed(2);
  document.getElementById("summary-so-luong").textContent = totalQuantity;
}
