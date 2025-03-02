function openAddQuyenHanModal() {
  document.getElementById("addQuyenhanModal").style.display = "block";
}

// không dùng ajax thay vào đó dùng bootstrap "để mở modal"

function openEditQuyenModal(event, id, tenquyen, motaquyen) {
  event.preventDefault();
  console.log("Dữ liệu truyền vào:", id, tenquyen, motaquyen);
  const idQuyen = document.getElementById("editIdQuyen");
  const tenQuyen = document.getElementById("editTenQuyen");
  const moTaQuyen = document.getElementById("editMoTaQuyen");
  if (idQuyen && tenQuyen && moTaQuyen) {
    idQuyen.value = id || "";
    tenQuyen.value = tenquyen || "";
    moTaQuyen.value = motaquyen || "";
    let editModal = new bootstrap.Modal(
      document.getElementById("editQuyenModal")
    );
    editModal.show();
  } else {
    console.error("Không tìm thấy các phần tử trong modal");
  }
}

document.addEventListener("DOMContentLoaded", function () {
  window.openEditQuyenModal = function (event, id, tenquyen, motaquyen) {
    event.preventDefault();
    console.log("Dữ liệu truyền vào:", id, tenquyen, motaquyen);
    const idQuyen = document.getElementById("editIdQuyen");
    const tenQuyen = document.getElementById("editTenQuyen");
    const moTaQuyen = document.getElementById("editMoTaQuyen");
    if (idQuyen && tenQuyen && moTaQuyen) {
      idQuyen.value = id || "";
      tenQuyen.value = tenquyen || "";
      moTaQuyen.value = motaquyen || "";
      let editModal = new bootstrap.Modal(
        document.getElementById("editQuyenModal")
      );
      editModal.show();
    } else {
      console.error("Không tìm thấy các phần tử trong modal");
    }
  };
});

function closeModal(modalId) {
  document.getElementById(modalId).style.display = "none";
}
window.onclick = function (event) {
  let addModal = document.getElementById("addQuyenhanModal");
  let editModal = document.getElementById("editQuyenhanModal");

  if (event.target === addModal) {
    closeModal("addQuyenhanModal");
  }
  if (event.target === editModal) {
    closeModal("editQuyenhanModal");
  }
};
