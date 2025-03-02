let sttdon = 1; // Số thứ tự đơn bán
function intdonban() {
  sttdon = 1;
  const currentDate = new Date().toISOString().slice(0, 16); // Format 'YYYY-MM-DDTHH:MM'
  document.getElementById("THOIGIANLAPBAN").value = currentDate;
  const sachSelect = document.getElementById("SACHID");
  const tabList = document.getElementById("tabList");
  const tabContent = document.getElementById("tabContent");
  const btnChonSach = document.getElementById("chooseBookBtn");
  const tongSoLuongInput = document.getElementById("TONGSOLUONGHD");
  const tongTienInput = document.getElementById("TONGTIENHD");
  const tongSoLuong = document.getElementById("summary-so-luong");
  const tongTien = document.getElementById("summary-tong-tien");
  
}
function chonsach(sachs, checkbox) {
  if (!checkbox.checked) {
    huysachs(sachs.ID_SACH); // Nếu checkbox bị bỏ chọn, xóa tab
    return;
  }
  // Tạo các ID cho tab và nội dung tab
  const tabId = `tab-${sachs.ID_SACH}`;
  const tabPaneId = `tab-pane-${sachs.ID_SACH}`;

  // Tạo tab và nội dung tab
  createTab(tabId, tabPaneId, sachs.TENSACH); // Tên tab là tên sách
  createTabContent(tabPaneId, sachs); // Truyền thông tin sách vào đây

  // Cập nhật tổng tiền và tổng số lượng sách
  updateTotalPriceAndQuantity();

  // Active the first tab by default
  activateTab();
}
// Hàm hủy sách (bỏ tab tương ứng)
function huysachs(bookId) {
  const tabId = `tab-${bookId}`;
  const tabPaneId = `tab-pane-${bookId}`;

  // Xóa tab khỏi danh sách
  const tabElement = document.getElementById(tabId);
  if (tabElement) tabElement.parentNode.removeChild(tabElement);

  // Xóa nội dung tab
  const tabPaneElement = document.getElementById(tabPaneId);
  if (tabPaneElement) tabPaneElement.parentNode.removeChild(tabPaneElement);

  // Cập nhật lại tổng tiền và số lượng sách
  updateTotalPriceAndQuantity();

  // Nếu vẫn còn tab khác, chuyển đến tab cuối cùng
  activateTab();
}

// Hàm reset các tab và nội dung (Loại bỏ khi không cần tạo lại nội dung mỗi lần)
function resetTabsAndContent() {
  const tabList = document.getElementById("tabList");
  const tabContent = document.getElementById("tabContent");
  tabList.innerHTML = "";
  tabContent.innerHTML = "";
}

// Hàm tạo tab
function createTab(tabId, tabPaneId, bookName) {
  const tabList = document.getElementById("tabList");
  const tab = document.createElement("li");
  tab.classList.add("nav-item");
  tab.innerHTML = `
            <a class="nav-link" id="${tabId}" data-bs-toggle="tab" href="#${tabPaneId}">
                ${bookName}
            </a>
        `;
  tabList.appendChild(tab);
}

// Hàm tạo nội dung tab
function createTabContent(tabPaneId, sach) {
  const tabPane = document.createElement("div");
  tabPane.classList.add("tab-pane", "fade");
  const tabContent = document.getElementById("tabContent");
  tabPane.id = tabPaneId;
  tabPane.innerHTML = `
            <div class="mb-3">
                <label class="form-label fw-bold">Tên sách:</label>
                <select class="form-select custom-select" name="SACHID[${sach.ID_SACH}][]" id="SACHID-${sach.ID_SACH}" required>
                    <option value="${sach.ID_SACH}" data-price="${sach.GIASACH}">
                        ${sach.TENSACH} (${new Intl.NumberFormat("vi-VN").format(sach.GIASACH)} VNĐ)
                    </option>
                </select>
            </div>
            <div class="mb-3 d-flex justify-content-between">
                <div class="w-50 pe-2">
                    <label class="form-label fw-bold">Số lượng</label>
                    <input type="number" name="SOLUONG[${sach.ID_SACH}]" id="SOLUONG-${sach.ID_SACH}"  class="form-control" min="1" required>
                </div>
                <div class="w-50 ps-2">
                    <label class="form-label fw-bold">Thành tiền</label>
                    <input type="number" name="THANHTIEN[${sach.ID_SACH}]" id="THANHTIEN-${sach.ID_SACH}"  class="form-control" step="0.01" readonly>
                </div>
            </div>
        `;
  tabContent.appendChild(tabPane);

  // Lắng nghe sự kiện thay đổi số lượng và cập nhật thành tiền
  tabPane
    .querySelector("input[type='number']")
    .addEventListener("input", function () {
      updateTabTotal(tabPane);
    });
}

// Hàm tính tổng tiền và số lượng cho từng tab
function updateTabTotal(tabPane) {
  const selectElement = tabPane.querySelector("select");
  const selectedOption = selectElement.options[selectElement.selectedIndex];
  const price = parseFloat(selectedOption.getAttribute("data-price")) || 0;
  const quantity =
    parseInt(tabPane.querySelector("input[type='number']").value) || 0;
  const total = price * quantity;

  // Cập nhật giá trị Thành tiền
  tabPane.querySelector("input[readonly]").value = total.toFixed(2);

  // Cập nhật tổng tiền và tổng số lượng sách
  updateTotalPriceAndQuantity();
}

// Cập nhật tổng tiền và tổng số lượng sách
function updateTotalPriceAndQuantity() {
  const tongSoLuongInput = document.getElementById("TONGSOLUONGHD");
  const tongTienInput = document.getElementById("TONGTIENHD");
  const tongSoLuong = document.getElementById("summary-so-luong");
  const tongTien = document.getElementById("summary-tong-tien");

  let totalPrice = 0;
  let totalQuantity = 0;

  document.querySelectorAll("#tabContent .tab-pane").forEach((tabPane) => {
    const quantityInput = tabPane.querySelector("input[type='number']");
    const totalPriceInput = tabPane.querySelector("input[readonly]");

    const quantity = parseInt(quantityInput.value) || 0;
    const total = parseFloat(totalPriceInput.value) || 0;

    totalQuantity += quantity;
    totalPrice += total;
  });

  // Cập nhật vào các input hidden và span
  tongTienInput.value = totalPrice.toFixed(2);
  tongSoLuongInput.value = totalQuantity;
  tongTien.textContent = totalPrice.toFixed(2);
  tongSoLuong.textContent = totalQuantity;
}

// Kích hoạt tab mặc định
function activateTab() {
  const allTabs = document.querySelectorAll(".nav-link");
  const allTabPanes = document.querySelectorAll(".tab-pane");

  // Xóa trạng thái active của tất cả các tab và nội dung tab
  allTabs.forEach((tab) => {
    tab.classList.remove("active");
    tab.setAttribute("aria-selected", "false");
  });

  allTabPanes.forEach((tabPane) => {
    tabPane.classList.remove("show", "active");
  });

  // Chọn tab mới nhất (tab cuối cùng được thêm vào)
  const lastTab = allTabs[allTabs.length - 1];
  const lastTabPane = allTabPanes[allTabPanes.length - 1];

  if (lastTab && lastTabPane) {
    lastTab.classList.add("active");
    lastTab.setAttribute("aria-selected", "true");

    lastTabPane.classList.add("show", "active");
  }
}

function filterBooks() {
  let input = document.getElementById("searchBook").value.toLowerCase();
  let books = document.querySelectorAll(".book-item");

  books.forEach((book) => {
    let title = book.getAttribute("data-title");
    book.style.display = title.startsWith(input) ? "block" : "none";
  });
}
