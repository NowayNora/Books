// document.addEventListener("DOMContentLoaded", function () {
//   const menuItems = {
//     "book-list-link": "/book",
//     "taikhoan-list": "/taikhoan",
//     "nguoidung-list": "/nguoidung",
//     "quyenhan-list": "/quyenhan",
//     "donban-list": "/donban",
//     "donnhap-list": "/donnhap",
//     "thongke-list": "/thongke",
//   };

//   Object.keys(menuItems).forEach((id) => {
//     const element = document.getElementById(id);
//     if (element) {
//       element.addEventListener("click", function (event) {
//         event.preventDefault();
//         const url = menuItems[id];
//         loadPage(url);
//         history.pushState(null, "", url);
//         if (id === "thongke-list") {
//           initializeThongKeEvents();
//         }
//       });
//     }
//   });

//   document
//     .getElementById("home-link")
//     .addEventListener("click", function (event) {
//       event.preventDefault();
//       history.pushState(null, "", "/index");
//       window.location.reload();
//     });

//   document.addEventListener("submit", function (event) {
//     if (event.target && event.target.id === "searchForm") {
//       event.preventDefault();
//       const keyword = document.getElementById("keyword").value;
//       const url = `/book?keyword=${encodeURIComponent(keyword)}`;
//       fetch(url)
//         .then((response) => response.text())
//         .then((data) => {
//           document.querySelector("main").innerHTML = data;
//           history.pushState(null, "", url);
//         })
//         .catch((error) => console.error("Lỗi khi tìm kiếm sách:", error));
//     }
//   });

//   function loadPage(url) {
//     fetch(url)
//       .then((response) => response.text())
//       .then((data) => {
//         document.querySelector("main").innerHTML = data;
//         if (url === "/thongke") {
//           initializeThongKeEvents();
//         }
//       })
//       .catch((error) => console.error("Lỗi khi tải nội dung:", error));
//   }

//   function initializeThongKeEvents() {
//     const form = document.querySelector("form.thongke-di");
//     if (form) {
//       form.addEventListener("submit", function (event) {
//         event.preventDefault();
//         const formData = new FormData(this);
//         const tungay = formData.get("tungay");
//         const denngay = formData.get("denngay");
//         const type = formData.get("type");

//         fetch("/thongke", {
//           method: "POST",
//           body: formData,
//         })
//           .then((response) => response.text())
//           .then((html) => {
//             document.querySelector("main").innerHTML = html;
//             initializeChart(); // Vẽ biểu đồ từ dữ liệu trong HTML
//             initializeToggleEvents(); // Gắn sự kiện toggle
//           })
//           .catch((error) => console.error("Lỗi khi gửi form thống kê:", error));
//       });
//     }
//   }

//   // Hàm khởi tạo biểu đồ từ dữ liệu trong HTML
//   function initializeChart() {
//     const type = document.getElementById("type")?.value || "";
//     const chartDataScript = document.getElementById("chartDataScript");
//     if (!chartDataScript) {
//       console.error("Chart data script not found");
//       return;
//     }

//     // Trích xuất và parse dữ liệu từ script
//     const scriptContent = chartDataScript.textContent.trim();
//     console.log("🔍 Nội dung script:", scriptContent); // Debug nội dung script

//     const chartDataMatch = scriptContent.match(/window\.chartData = (.*);/);
//     if (!chartDataMatch || !chartDataMatch[1]) {
//       console.error("Không tìm thấy dữ liệu chartData trong script");
//       return;
//     }

//     try {
//       const chartDataRaw = chartDataMatch[1].trim();
//       const chartData = JSON.parse(chartDataRaw); // Parse chỉ phần JSON
//       console.log("🔍 Dữ liệu chart parsed:", chartData);

//       if (
//         !chartData ||
//         (!chartData.tonkho && !chartData.doanhthu_chart && !chartData.doanhthu)
//       ) {
//         console.warn("Không có dữ liệu để vẽ biểu đồ");
//         return;
//       }

//       let labels = [];
//       let values = [];
//       let chartLabel = "";

//       if (type === "doanhthu" && chartData.doanhthu_chart) {
//         chartLabel = "Doanh thu theo ngày";
//         labels = chartData.doanhthu_chart.map((item) => item.NGAY);
//         values = chartData.doanhthu_chart.map(
//           (item) => parseFloat(item.TONGTIEN) || 0
//         );
//       } else if (type === "tonkho" && chartData.tonkho) {
//         chartLabel = "Tồn kho";
//         labels = chartData.tonkho.map((item) => item.TENSACH || "Không rõ");
//         values = chartData.tonkho.map(
//           (item) => parseFloat(item.SOLUONGTON) || 0
//         );
//       }

//       console.log("🔍 Labels:", labels);
//       console.log("🔍 Values:", values);

//       if (labels.length === 0 || values.length === 0) {
//         console.warn("Dữ liệu biểu đồ rỗng");
//         return;
//       }

//       const ctx = document.getElementById("chartCanvas")?.getContext("2d");
//       const chartTypeSelect = document.getElementById("chartType");
//       console.log("🔍 Chart canvas:", ctx);
//       console.log("🔍 Chart type select:", chartTypeSelect);

//       if (!ctx || !chartTypeSelect) {
//         console.error("Không tìm thấy canvas hoặc select để vẽ biểu đồ");
//         return;
//       }

//       let myChart;

//       function createChart(type) {
//         if (myChart) myChart.destroy();

//         const chartCanvas = document.getElementById("chartCanvas");
//         if (type === "bar" || type === "line") {
//           chartCanvas.width = Math.max(800, labels.length * 40);
//           chartCanvas.height = 400;
//         } else {
//           chartCanvas.width = 600;
//           chartCanvas.height = 400;
//         }

//         myChart = new Chart(ctx, {
//           type: type,
//           data: {
//             labels: labels,
//             datasets: [
//               {
//                 label: chartLabel,
//                 data: values,
//                 backgroundColor:
//                   type === "pie"
//                     ? [
//                         "#36A2EB",
//                         "#FF6384",
//                         "#FFCE56",
//                         "#4BC0C0",
//                         "#9966FF",
//                         "#FF5733",
//                         "#8D33FF",
//                         "#33FFA1",
//                         "#FFD433",
//                         "#3381FF",
//                       ]
//                     : "rgba(54, 162, 235, 0.6)",
//                 borderColor: type === "pie" ? [] : "rgba(54, 162, 235, 1)",
//                 borderWidth: 1,
//                 pointRadius: type === "line" ? 4 : 0,
//               },
//             ],
//           },
//           options: {
//             responsive: true,
//             maintainAspectRatio: false,
//             plugins: {
//               legend: {
//                 display: true,
//                 position: type === "pie" ? "bottom" : "top",
//               },
//             },
//             scales: {
//               x: {
//                 ticks: {
//                   autoSkip: true,
//                   maxRotation: 0,
//                   minRotation: 0,
//                 },
//               },
//               y: {
//                 beginAtZero: true,
//               },
//             },
//           },
//         });
//         console.log("🔍 Biểu đồ đã được vẽ");
//       }

//       createChart(chartTypeSelect.value);
//       chartTypeSelect.addEventListener("change", function () {
//         createChart(this.value);
//       });
//     } catch (error) {
//       console.error("Lỗi khi parse dữ liệu chart:", error);
//     }
//   }

//   // Hàm gắn sự kiện toggle
//   function initializeToggleEvents() {
//     const btnTongQuan = document.getElementById("btnTongQuan");
//     const btnChiTiet = document.getElementById("btnChiTiet");
//     const tongQuanContent = document.getElementById("tongQuanContent");
//     const chiTietContent = document.getElementById("chiTietContent");

//     if (btnTongQuan && btnChiTiet && tongQuanContent && chiTietContent) {
//       btnTongQuan.addEventListener("click", function () {
//         tongQuanContent.style.display = "block";
//         chiTietContent.style.display = "none";
//         btnTongQuan.classList.add("active");
//         btnChiTiet.classList.remove("active");
//       });

//       btnChiTiet.addEventListener("click", function () {
//         chiTietContent.style.display = "block";
//         tongQuanContent.style.display = "none";
//         btnChiTiet.classList.add("active");
//         btnTongQuan.classList.remove("active");
//       });
//     }
//   }

//   // Xử lý Thêm & Chỉnh Sửa Người Dùng
//   $(document).on("submit", "#addModal form, #editModal form", function (event) {
//     event.preventDefault();
//     const formData = new FormData(this);
//     const url = $(this).attr("action");
//     const method = $(this).attr("method");
//     $.ajax({
//       url: url,
//       type: method,
//       data: formData,
//       contentType: false,
//       processData: false,
//       success: function () {
//         $(".modal").hide();
//         loadPage("/nguoidung");
//         history.pushState(null, "", "/nguoidung");
//       },
//       error: function () {
//         alert("Có lỗi xảy ra!");
//       },
//     });
//   });

//   window.openAddModal = function () {
//     $("#addModal").show();
//   };

//   window.openEditModal = function (event, id, name, email, sdt, diachi) {
//     event.preventDefault();
//     $("#editId").val(id);
//     $("#editName").val(name);
//     $("#editEmail").val(email);
//     $("#editSdt").val(sdt);
//     $("#editDiachi").val(diachi);
//     let editModal = new bootstrap.Modal(document.getElementById("editModal"));
//     editModal.show();
//   };

//   window.closeModal = function (modalId) {
//     $("#" + modalId).hide();
//   };

//   window.addEventListener("popstate", function () {
//     const currentUrl = window.location.pathname;
//     loadPage(currentUrl);
//   });
// });

document.addEventListener("DOMContentLoaded", function () {
  const menuItems = {
    "book-list-link": "/book",
    "taikhoan-list": "/taikhoan",
    "nguoidung-list": "/nguoidung",
    "quyenhan-list": "/quyenhan",
    "donban-list": "/donban",
    "donnhap-list": "/donnhap",
    "thongke-list": "/thongke",
  };

  Object.keys(menuItems).forEach((id) => {
    const element = document.getElementById(id);
    if (element) {
      element.addEventListener("click", function (event) {
        event.preventDefault();
        const url = menuItems[id];
        loadPage(url);
        history.pushState(null, "", url);
        if (id === "thongke-list") {
          initializeThongKeEvents();
        }
      });
    }
  });

  document
    .getElementById("home-link")
    .addEventListener("click", function (event) {
      event.preventDefault();
      history.pushState(null, "", "/index");
      window.location.reload();
    });

  document.addEventListener("submit", function (event) {
    if (event.target && event.target.id === "searchForm") {
      event.preventDefault();
      const keyword = document.getElementById("keyword").value;
      const url = `/book?keyword=${encodeURIComponent(keyword)}`;
      fetch(url)
        .then((response) => response.text())
        .then((data) => {
          document.querySelector("main").innerHTML = data;
          history.pushState(null, "", url);
        })
        .catch((error) => console.error("Lỗi khi tìm kiếm sách:", error));
    }
  });

  function loadPage(url) {
    fetch(url)
      .then((response) => response.text())
      .then((data) => {
        document.querySelector("main").innerHTML = data;
        if (url === "/thongke") {
          initializeThongKeEvents();
        }
      })
      .catch((error) => console.error("Lỗi khi tải nội dung:", error));
  }

  function initializeThongKeEvents() {
    const form = document.querySelector("form.thongke-di");
    if (form) {
      form.addEventListener("submit", function (event) {
        event.preventDefault();
        const formData = new FormData(this);
        const tungay = formData.get("tungay");
        const denngay = formData.get("denngay");
        const type = formData.get("type");

        fetch("/thongke", {
          method: "POST",
          body: formData,
        })
          .then((response) => response.text())
          .then((html) => {
            document.querySelector("main").innerHTML = html;
            initializeChart(); // Vẽ biểu đồ từ dữ liệu trong HTML
            initializeToggleEvents(); // Gắn sự kiện toggle
          })
          .catch((error) => console.error("Lỗi khi gửi form thống kê:", error));
      });
    }
  }

  // Hàm khởi tạo biểu đồ từ dữ liệu trong HTML
  function initializeChart() {
    const type = document.getElementById("type")?.value || "";
    const chartDataScript = document.getElementById("chartDataScript");
    if (!chartDataScript) {
      console.error("Chart data script not found");
      return;
    }

    // Trích xuất và parse dữ liệu từ script
    const scriptContent = chartDataScript.textContent.trim();
    console.log("🔍 Nội dung script:", scriptContent); // Debug nội dung script

    const chartDataMatch = scriptContent.match(/window\.chartData = (.*);/);
    if (!chartDataMatch || !chartDataMatch[1]) {
      console.error("Không tìm thấy dữ liệu chartData trong script");
      return;
    }

    try {
      const chartDataRaw = chartDataMatch[1].trim();
      const chartData = JSON.parse(chartDataRaw); // Parse chỉ phần JSON
      console.log("🔍 Dữ liệu chart parsed:", chartData);

      if (
        !chartData ||
        (!chartData.tonkho && !chartData.doanhthu_chart && !chartData.doanhthu)
      ) {
        console.warn("Không có dữ liệu để vẽ biểu đồ");
        return;
      }

      let labels = [];
      let values = [];
      let chartLabel = "";

      if (type === "doanhthu" && chartData.doanhthu_chart) {
        chartLabel = "Doanh thu theo ngày";
        labels = chartData.doanhthu_chart.map((item) => item.NGAY);
        values = chartData.doanhthu_chart.map(
          (item) => parseFloat(item.TONGTIEN) || 0
        );
      } else if (type === "tonkho" && chartData.tonkho) {
        chartLabel = "Tồn kho";
        labels = chartData.tonkho.map((item) => item.TENSACH || "Không rõ");
        values = chartData.tonkho.map(
          (item) => parseFloat(item.SOLUONGTON) || 0
        );
      }

      console.log("🔍 Labels:", labels);
      console.log("🔍 Values:", values);

      if (labels.length === 0 || values.length === 0) {
        console.warn("Dữ liệu biểu đồ rỗng");
        return;
      }

      const ctx = document.getElementById("chartCanvas")?.getContext("2d");
      const chartTypeSelect = document.getElementById("chartType");
      console.log("🔍 Chart canvas:", ctx);
      console.log("🔍 Chart type select:", chartTypeSelect);

      if (!ctx || !chartTypeSelect) {
        console.error("Không tìm thấy canvas hoặc select để vẽ biểu đồ");
        return;
      }

      let myChart;

      function createChart(type) {
        if (myChart) myChart.destroy();

        const chartCanvas = document.getElementById("chartCanvas");
        if (type === "bar" || type === "line") {
          chartCanvas.width = Math.max(800, labels.length * 40);
          chartCanvas.height = 400;
        } else {
          chartCanvas.width = 600;
          chartCanvas.height = 400;
        }

        myChart = new Chart(ctx, {
          type: type,
          data: {
            labels: labels,
            datasets: [
              {
                label: chartLabel,
                data: values,
                backgroundColor:
                  type === "pie"
                    ? [
                        "#36A2EB",
                        "#FF6384",
                        "#FFCE56",
                        "#4BC0C0",
                        "#9966FF",
                        "#FF5733",
                        "#8D33FF",
                        "#33FFA1",
                        "#FFD433",
                        "#3381FF",
                      ]
                    : "rgba(54, 162, 235, 0.6)",
                borderColor: type === "pie" ? [] : "rgba(54, 162, 235, 1)",
                borderWidth: 1,
                pointRadius: type === "line" ? 4 : 0,
              },
            ],
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
              legend: {
                display: true,
                position: type === "pie" ? "bottom" : "top",
              },
            },
            scales: {
              x: {
                ticks: {
                  autoSkip: true,
                  maxRotation: 0,
                  minRotation: 0,
                },
              },
              y: {
                beginAtZero: true,
              },
            },
          },
        });
        console.log("🔍 Biểu đồ đã được vẽ");
      }

      createChart(chartTypeSelect.value);
      chartTypeSelect.addEventListener("change", function () {
        createChart(this.value);
      });
    } catch (error) {
      console.error("Lỗi khi parse dữ liệu chart:", error);
    }
  }

  // Hàm gắn sự kiện toggle
  function initializeToggleEvents() {
    const btnTongQuan = document.getElementById("btnTongQuan");
    const btnChiTiet = document.getElementById("btnChiTiet");
    const tongQuanContent = document.getElementById("tongQuanContent");
    const chiTietContent = document.getElementById("chiTietContent");

    if (btnTongQuan && btnChiTiet && tongQuanContent && chiTietContent) {
      btnTongQuan.addEventListener("click", function () {
        tongQuanContent.style.display = "block";
        chiTietContent.style.display = "none";
        btnTongQuan.classList.add("active");
        btnChiTiet.classList.remove("active");
      });

      btnChiTiet.addEventListener("click", function () {
        chiTietContent.style.display = "block";
        tongQuanContent.style.display = "none";
        btnChiTiet.classList.add("active");
        btnTongQuan.classList.remove("active");
      });
    }
  }

  // Xử lý Thêm & Chỉnh Sửa Người Dùng
  $(document).on("submit", "#addModal form, #editModal form", function (event) {
    event.preventDefault();
    const formData = new FormData(this);
    const url = $(this).attr("action");
    const method = $(this).attr("method");
    $.ajax({
      url: url,
      type: method,
      data: formData,
      contentType: false,
      processData: false,
      success: function () {
        $(".modal").hide();
        loadPage("/nguoidung");
        history.pushState(null, "", "/nguoidung");
      },
      error: function () {
        alert("Có lỗi xảy ra!");
      },
    });
  });

  // Xử lý submit form trong modal
  $(document).on(
    "submit",
    "#addNguoiDungModal form, #addQuyenHanModal form, #addTaiKhoanModal form, #editNguoiDungModal form, #editQuyenModal form, #editTaiKhoanModal form",
    function (event) {
      event.preventDefault();
      const formData = new FormData(this);
      const url = $(this).attr("action");
      const method = $(this).attr("method");
      $.ajax({
        url: url,
        type: method,
        data: formData,
        contentType: false,
        processData: false,
        success: function () {
          $(".modal").hide();
          const page = url.split("/")[1]; // Lấy phần "nguoidung", "quyenhan", "taikhoan"
          loadPage("/" + page);
          history.pushState(null, "", "/" + page);
        },
        error: function () {
          alert("Có lỗi xảy ra!");
        },
      });
    }
  );

  // Hàm mở modal
  window.openAddNguoiDungModal = function () {
    let modal = new bootstrap.Modal(
      document.getElementById("addNguoiDungModal")
    );
    modal.show();
    // Xóa backdrop sau khi mở modal
    document.querySelector(".modal-backdrop").remove();
  };

  window.openAddQuyenHanModal = function () {
    let modal = new bootstrap.Modal(
      document.getElementById("addQuyenHanModal")
    );
    modal.show();
    // Xóa backdrop sau khi mở modal
    document.querySelector(".modal-backdrop").remove();
  };

  window.openAddTaiKhoanModal = function () {
    let modal = new bootstrap.Modal(
      document.getElementById("addTaiKhoanModal")
    );
    modal.show();
    // Xóa backdrop sau khi mở modal
    document.querySelector(".modal-backdrop").remove();
  };

  // Hàm mở các modal chỉnh sửa (Edit)
  window.openEditNguoiDungModal = function (
    event,
    id,
    name,
    email,
    sdt,
    diachi
  ) {
    event.preventDefault();
    $("#editId").val(id);
    $("#editName").val(name);
    $("#editEmail").val(email);
    $("#editSdt").val(sdt);
    $("#editDiachi").val(diachi);
    let modal = new bootstrap.Modal(
      document.getElementById("editNguoiDungModal")
    );
    modal.show();

    // Xóa backdrop sau khi mở modal
    document.querySelector(".modal-backdrop").remove();
  };

  window.openEditQuyenModal = function (event, id, tenQuyen, moTaQuyen) {
    event.preventDefault();
    $("#editIdQuyen").val(id);
    $("#editTenQuyen").val(tenQuyen);
    $("#editMoTaQuyen").val(moTaQuyen);
    let modal = new bootstrap.Modal(document.getElementById("editQuyenModal"));
    modal.show();

    // Xóa backdrop sau khi mở modal
    document.querySelector(".modal-backdrop").remove();
  };

  window.openEditTaiKhoanModal = function (
    event,
    id,
    username,
    password,
    idNguoiDung,
    idQuyen,
    trangThai,
    nguoiDungName,
    nguoiDungEmail,
    nguoiDungSdt,
    nguoiDungDiachi
  ) {
    event.preventDefault();
    $("#editId").val(id);
    $("#editUsername").val(username);
    $("#editPassword").val(password);
    $("#editNguoiDung").val(idNguoiDung || "");
    $("#editQuyen").val(idQuyen || "");
    $("#editTrangthai").val(trangThai);
    $("#editName").val(nguoiDungName || "");
    $("#editEmail").val(nguoiDungEmail || "");
    $("#editSdt").val(nguoiDungSdt || "");
    $("#editDiachi").val(nguoiDungDiachi || "");
    let modal = new bootstrap.Modal(
      document.getElementById("editTaiKhoanModal")
    );
    modal.show();

    // Xóa backdrop sau khi mở modal
    document.querySelector(".modal-backdrop").remove();
  };

  window.openEditModal = function (event, id, name, email, sdt, diachi) {
    event.preventDefault();
    $("#editId").val(id);
    $("#editName").val(name);
    $("#editEmail").val(email);
    $("#editSdt").val(sdt);
    $("#editDiachi").val(diachi);
    let editModal = new bootstrap.Modal(document.getElementById("editModal"));
    editModal.show();
  };

  window.closeModal = function (modalId) {
    let modal = bootstrap.Modal.getInstance(document.getElementById(modalId));
    if (modal) {
      modal.hide();
    } else {
      $("#" + modalId).hide(); // Fallback nếu Bootstrap không hoạt động
    }
  };

  window.addEventListener("popstate", function () {
    const currentUrl = window.location.pathname;
    loadPage(currentUrl);
  });
});
