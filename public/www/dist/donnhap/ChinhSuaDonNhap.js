let sttdonnhapCS = 0;
function intChinhSuadonnhap() {
  const tabList = document.getElementById("tabList");
  sttdonnhapCS = tabList.querySelectorAll(".nav-item").length + 1;

  const chiTietDonNhapElement = document.getElementById("chiTietDonNhap");
  if (!chiTietDonNhapElement) return;

  const chiTietDonNhap = JSON.parse(chiTietDonNhapElement.dataset.chiTiet);

  chiTietDonNhap.forEach((sach, index) => {
    const selectSach = document.querySelector(`#SACHID-${index}`);
    const inputSoLuong = document.querySelector(`#SOLUONG-${index}`);

    if (selectSach && inputSoLuong) {
      selectSach.addEventListener("change", () => updateTabTotalByPHPCS(index));
      inputSoLuong.addEventListener("input", () =>
        updateTabTotalByPHPCS(index)
      );
    }
  });
  updateTotalPriceAndQuantityCS();
}

function chonsachCS(sachs) {
  // Tạo các ID cho tab và nội dung tab
  const tabId = `tab-${sttdonnhapCS}`;
  const tabPaneId = `content-${sttdonnhapCS}`;

  // Tạo tab và nội dung tab
  createTabCS(tabId, tabPaneId, `Đơn sách ${sttdonnhapCS}`);
  createTabContentCS(tabPaneId, sttdonnhapCS, sachs);
  // Mở tab mới
  activateTabCS(tabId, tabPaneId);

  // Tăng giá trị sttdonCS sau mỗi lần chọn sách
  sttdonnhapCS++;
}

function activateTabCS(tabId, tabPaneId) {
  // Đóng tất cả các tab cũ
  const allTabs = document.querySelectorAll(".nav-link");
  allTabs.forEach((tab) => {
    tab.classList.remove("active");
    tab.setAttribute("aria-selected", "false");
  });

  const allTabPanes = document.querySelectorAll(".tab-pane");
  allTabPanes.forEach((tabPane) => {
    tabPane.classList.remove("show", "active");
  });

  // Mở tab mới
  const newTab = document.querySelector(`#${tabId}`);
  const newTabPane = document.querySelector(`#${tabPaneId}`);

  // Thêm lớp "active" cho tab mới và thiết lập aria-selected="true"
  newTab.classList.add("active");
  newTab.setAttribute("aria-selected", "true");

  // Mở nội dung của tab mới
  newTabPane.classList.add("show", "active");
}

// Hàm tạo tab
function createTabCS(tabId, tabPaneId, bookName) {
  const tabList = document.getElementById("tabList");
  const tab = document.createElement("li");
  tab.classList.add("nav-item");
  tab.innerHTML = `
        <a class="nav-link" id="${tabId}" data-bs-toggle="tab" href="#${tabPaneId}" role="tab" aria-controls="${tabPaneId}" aria-selected="false">
            ${bookName}
        </a>
    `;
  tabList.appendChild(tab);
}

// Hàm tạo nội dung tab
function createTabContentCS(tabPaneId, tabIndex, sachs) {
  const tabContent = document.getElementById("tabContent");
  const tabPane = document.createElement("div");
  tabPane.classList.add("tab-pane", "fade");
  tabPane.id = tabPaneId;

  // Tạo thẻ select và các option từ mảng sách
  let optionsHTML = "";
  sachs.forEach((sach) => {
    optionsHTML += `
        <option value="${sach.ID_SACH}" data-price="${sach.GIASACH}">
          ${sach.TENSACH} (${new Intl.NumberFormat("vi-VN").format(
      sach.GIASACH
    )} VNĐ)
        </option>
      `;
  });
  tabPane.innerHTML = `
        <div class="mb-3">
            <label for="SACHID-${tabIndex}" class="form-label fw-bold">Chọn sách:</label>
            <select name="sachs[${tabIndex}][id]" id="SACHID-${tabIndex}" class="form-select" required>
            ${optionsHTML}
            </select>
        </div>
        <div class="mb-3 d-flex justify-content-between">
            <div class="w-50 pe-2">
                <label for="SOLUONG-${tabIndex}" class="form-label fw-bold">Số lượng:</label>
                <input type="number" name="sachs[${tabIndex}][so_luong]" id="SOLUONG-${tabIndex}" class="form-control" min="1" value="0" required>
            </div>
            <div class="w-50 ps-2">
                <label for="THANHTIEN-${tabIndex}" class="form-label fw-bold">Thành tiền:</label>
                <input type="number" name="sachs[${tabIndex}][thanh_tien]" id="THANHTIEN-${tabIndex}" class="form-control" step="0.01" value="0.00" readonly>
            </div>
            <div class="w-50 ps-2">
                <label for="NOINHAP-${tabIndex}" class="form-label fw-bold">Nơi nhập</label>
                <input type="number" name="NOINHAP[${tabIndex}]" id="NOINHAP-${tabIndex}" class="form-control" step="0.01" readonly>
            </div>
        </div>
    `;
  tabContent.appendChild(tabPane);

  // Lắng nghe sự kiện thay đổi số lượng và cập nhật thành tiền
  tabPane
    .querySelector(`#SOLUONG-${tabIndex}`)
    .addEventListener("input", function () {
      updateTabTotalByPHPCS(tabIndex);
    });

  // Lắng nghe sự kiện thay đổi chọn sách và cập nhật thành tiền
  tabPane
    .querySelector(`#SACHID-${tabIndex}`)
    .addEventListener("change", function () {
      updateTabTotalByPHPCS(tabIndex);
    });

  // Cập nhật tổng giá trị và số lượng sách sau khi thêm sách
  updateTotalPriceAndQuantityCS();
}

// Hàm tính tổng tiền và số lượng của từng tab
function updateTabTotalByPHPCS(index) {
  const selectSach = document.querySelector(`#SACHID-${index}`);
  const inputSoLuong = document.querySelector(`#SOLUONG-${index}`);
  const inputThanhTien = document.querySelector(`#THANHTIEN-${index}`);

  if (!selectSach || !inputSoLuong || !inputThanhTien) return;

  const selectedOption = selectSach.options[selectSach.selectedIndex];
  const price = parseFloat(selectedOption.getAttribute("data-price")) || 0;
  const quantity = parseInt(inputSoLuong.value) || 0;
  // Tính thành tiền
  const total = price * quantity;
  inputThanhTien.value = total.toFixed(2);

  // Cập nhật tổng toàn bộ
  updateTotalPriceAndQuantityCS();
}

// Hàm tính tổng tiền và tổng số lượng toàn bộ
function updateTotalPriceAndQuantityCS() {
  let totalPrice = 0;
  let totalQuantity = 0;

  // Get all 'thanh_tien' and 'so_luong' inputs dynamically
  const thanhTienInputs = document.querySelectorAll('[id^="THANHTIEN-"]');
  const quantityInputs = document.querySelectorAll('[id^="SOLUONG-"]');

  // Loop through each of the 'thanh_tien' inputs and sum the total price
  thanhTienInputs.forEach((input) => {
    totalPrice += parseFloat(input.value) || 0; // Convert value to float and sum
  });

  // Loop through each of the 'so_luong' inputs and sum the total quantity
  quantityInputs.forEach((input) => {
    totalQuantity += parseInt(input.value) || 0; // Convert value to int and sum
  });

  // Update the total price and quantity fields
  document.getElementById("TONGTIENHD").value = totalPrice.toFixed(2);
  document.getElementById("TONGSOLUONGHD").value = totalQuantity;
  document.getElementById("summary-tong-tien").textContent =
    totalPrice.toFixed(2);
  document.getElementById("summary-so-luong").textContent = totalQuantity;
}
