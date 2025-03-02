// // Lắng nghe sự kiện submit form
// document
//   .getElementById("addBookForm")
//   .addEventListener("submit", function (event) {
//     event.preventDefault(); // Ngăn chặn form gửi đi theo cách thông thường

//     const formData = new FormData(this);

//     fetch("/book/add", {
//       method: "POST",
//       body: formData,
//     })
//       .then((response) => response.text()) // Lấy nội dung dạng text thay vì JSON
//       .then((text) => {
//         console.log("Phản hồi từ server:", text); // In nội dung để debug
//         return JSON.parse(text); // Sau đó mới parse JSON
//       })
//       .then((data) => {
//         if (data.success) {
//           alert("Thêm sách thành công!");
//           window.location.href = "/book";
//         } else {
//           alert("Có lỗi xảy ra: " + data.message);
//         }
//       })
//       .catch((error) => {
//         console.error("Lỗi:", error);
//         alert(
//           "Không thể gửi dữ liệu. Kiểm tra lại kết nối mạng hoặc thử lại sau."
//         );
//       });
//   });
