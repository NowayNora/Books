function intdonnhap() {
  const currentDate = new Date().toISOString().slice(0, 16); // Format 'YYYY-MM-DDTHH:MM'
  document.getElementById("THOIGIANLAP").value = currentDate;
  const sachSelect = document.getElementById("SACHID");
  const tabListDN = document.getElementById("tabList");
  const tabContent = document.getElementById("tabContent");
  const btnChonSach = document.getElementById("chooseBookBtn");
  const tongSoLuongInput = document.getElementById("TONGSOLUONGHD");
  const tongTienInput = document.getElementById("TONGTIENHD");
  const tongSoLuong = document.getElementById("summary-so-luong");
  const tongTien = document.getElementById("summary-tong-tien");
}
let sttdonnhap = 1;
function chonsachDN(sachs) {
  const tabId = `tab-${sttdonnhap}`;
  const tabPaneId = `tab-pane-${sttdonnhap}`;

  createTab(tabId, tabPaneId, "Đơn sách " + sttdonnhap);
  createTabContent(tabPaneId, sttdonnhap, sachs);
  updateTotalPriceAndQuantity();
  setActiveFirstTab();
  sttdonnhap++;
}

function resetTabsAndContent() {
  const tabList = document.getElementById("tabList");
  const tabContent = document.getElementById("tabContent");
  tabList.innerHTML = "";
  tabContent.innerHTML = "";
}

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

function createTabContent(tabPaneId, tabIndex, sachs) {
  const tabPane = document.createElement("div");
  tabPane.classList.add("tab-pane", "fade");
  const tabContent = document.getElementById("tabContent");
  tabPane.id = tabPaneId;
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
                  <select name="SACHID[${tabIndex}][]" id="SACHID-${tabIndex}" class="form-select custom-select" required>
                   ${optionsHTML}
                    </select>
              </div>
              <div class="mb-3 d-flex justify-content-between">
                  <div class="w-50 pe-2">
                      <label for="SOLUONG-${tabIndex}" class="form-label fw-bold">Số lượng</label>
                      <input type="number" name="SOLUONG[${tabIndex}]" id="SOLUONG-${tabIndex}" class="form-control" min="1" required>
                  </div>
                  <div class="w-50 ps-2">
                      <label for="THANHTIEN-${tabIndex}" class="form-label fw-bold">Thành tiền</label>
                      <input type="number" name="THANHTIEN[${tabIndex}]" id="THANHTIEN-${tabIndex}" class="form-control" step="0.01" readonly>
                  </div>
              </div>
          `;
  tabContent.appendChild(tabPane);

  tabPane
    .querySelector(`#SOLUONG-${tabIndex}`)
    .addEventListener("input", function () {
      updateTabTotal(tabPane, tabIndex);
    });

  tabPane
    .querySelector(`#SACHID-${tabIndex}`)
    .addEventListener("change", function () {
      updateTabTotal(tabPane, tabIndex);
    });
}

function updateTabTotal(tabPane, tabIndex) {
  const selectElement = tabPane.querySelector(`#SACHID-${tabIndex}`);
  const selectedOption = selectElement.options[selectElement.selectedIndex];
  const price = parseFloat(selectedOption.getAttribute("data-price")) || 0;
  const quantity =
    parseInt(tabPane.querySelector(`#SOLUONG-${tabIndex}`).value) || 0;
  const total = price * quantity;

  tabPane.querySelector(`#THANHTIEN-${tabIndex}`).value = total.toFixed(2);

  updateTotalPriceAndQuantity();
}

function updateTotalPriceAndQuantity() {
  const tongSoLuongInput = document.getElementById("TONGSOLUONGHD");
  const tongTienInput = document.getElementById("TONGTIENHD");
  const tongSoLuong = document.getElementById("summary-so-luong");
  const tongTien = document.getElementById("summary-tong-tien");
  let totalPrice = 0;
  let totalQuantity = 0;

  const thanhTienInputs = document.querySelectorAll('input[name^="THANHTIEN"]');
  const quantityInputs = document.querySelectorAll('input[name^="SOLUONG"]');

  thanhTienInputs.forEach((input) => {
    totalPrice += parseFloat(input.value) || 0;
  });

  quantityInputs.forEach((input) => {
    totalQuantity += parseInt(input.value) || 0;
  });

  // Cập nhật vào các input hidden và span
  tongTienInput.value = totalPrice.toFixed(2);
  tongSoLuongInput.value = totalQuantity;
  tongTien.textContent = totalPrice.toFixed(2); // Cập nhật tổng tiền vào phần tử span
  tongSoLuong.textContent = totalQuantity; // Cập nhật tổng số lượng vào phần tử span
}

// Active the first tab by default
function setActiveFirstTab() {
  const tabContent = document.getElementById("tabContent");
  const tabList = document.getElementById("tabList");
  if (tabList.querySelector("li")) {
    tabList.querySelector("li a").classList.add("active");
    tabContent.querySelector(".tab-pane").classList.add("show", "active");
  }
}
