function openAddAccountModal() {
  document.getElementById("addTaiKhoanModal").style.display = "block";
}

function openEditTaiKhoanModal(
  event,
  id_taikhoan,
  username,
  password,
  id_nguoidung,
  id_quyen,
  trangthai,
  name,
  email,
  sdt,
  diachi
) {
  event.preventDefault();
  console.log(
    "Dữ liệu truyền vào:",
    id_taikhoan,
    username,
    password,
    id_nguoidung,
    id_quyen,
    trangthai,
    name,
    email,
    sdt,
    diachi
  );
  const modalElement = document.getElementById("editTaiKhoanModal");
  if (!modalElement) {
    console.error("Không tìm thấy modal với id 'editTaiKhoanModal'");
    return;
  }

  const editId = document.getElementById("editId");
  const editUsername = document.getElementById("editUsername");
  const editPassword = document.getElementById("editPassword");
  const editNguoiDung = document.getElementById("editNguoiDung");
  const editQuyen = document.getElementById("editQuyen");
  const editTrangthai = document.getElementById("editTrangthai");
  const editName = document.getElementById("editName");
  const editEmail = document.getElementById("editEmail");
  const editSdt = document.getElementById("editSdt");
  const editDiachi = document.getElementById("editDiachi");

  if (
    editId &&
    editUsername &&
    editPassword &&
    editNguoiDung &&
    editQuyen &&
    editTrangthai &&
    editName &&
    editEmail &&
    editSdt &&
    editDiachi
  ) {
    editId.value = id_taikhoan || "";
    editUsername.value = username || "";
    editPassword.value = password || "";
    editNguoiDung.value = id_nguoidung || "";
    editQuyen.value = id_quyen || "";
    editTrangthai.value = trangthai || "";
    editName.value = name || "";
    editEmail.value = email || "";
    editSdt.value = sdt || "";
    editDiachi.value = diachi || "";
    let editModal = new bootstrap.Modal(modalElement);
    editModal.show();
  } else {
    console.error("Không tìm thấy các phần tử trong modal");
  }
}

document.addEventListener("DOMContentLoaded", function () {
  window.openEditTaiKhoanModal = function (
    event,
    id_taikhoan,
    username,
    password,
    id_nguoidung,
    id_quyen,
    trangthai,
    name,
    email,
    sdt,
    diachi
  ) {
    event.preventDefault();
    console.log(
      "Dữ liệu truyền vào:",
      id_taikhoan,
      username,
      password,
      id_nguoidung,
      id_quyen,
      trangthai,
      name,
      email,
      sdt,
      diachi
    );
    const editId = document.getElementById("editId");
    const editUsername = document.getElementById("editUsername");
    const editPassword = document.getElementById("editPassword");
    const editNguoiDung = document.getElementById("editNguoiDung");
    const editQuyen = document.getElementById("editQuyen");
    const editTrangthai = document.getElementById("editTrangthai");
    const editName = document.getElementById("editName");
    const editEmail = document.getElementById("editEmail");
    const editSdt = document.getElementById("editSdt");
    const editDiachi = document.getElementById("editDiachi");

    if (
      editId &&
      editUsername &&
      editPassword &&
      editNguoiDung &&
      editQuyen &&
      editTrangthai &&
      editName &&
      editEmail &&
      editSdt &&
      editDiachi
    ) {
      editId.value = id_taikhoan || "";
      editUsername.value = username || "";
      editPassword.value = password || "";
      editNguoiDung.value = id_nguoidung || "";
      editQuyen.value = id_quyen || "";
      editTrangthai.value = trangthai || "";
      editName.value = name || "";
      editEmail.value = email || "";
      editSdt.value = sdt || "";
      editDiachi.value = diachi || "";
      let editModal = new bootstrap.Modal(
        document.getElementById("editTaiKhoanModal")
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
