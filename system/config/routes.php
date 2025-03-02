<?php

/**
 * Key của mảng => URL
 * Value của mảng => thư mục/class-controller/method (method index nếu không được chỉ định)
 */


return [
    // Các route không cần đăng nhập
    "/" => "login/login/login",
    "/login" => "login/login/login",
    "/login/logout" => "login/login/logout",
    "/register" => "login/login/register",
    "/forgot-password" => "login/login/forgotPassword",
    "/index" => "login/login/indexView", // Khi đăng nhập thành công, chuyển hướng tới đây

    "/book" => "book/book/listBooks",
    "/book/add" => "book/book/create",
    "/book/update" => "book/book/update",
    "/book/delete" => "book/book/delete",
    // "/add" => "book/book/create",

    "/quyenhan" => "quyenhan/quyenhan/index",
    "/quyenhan/add" => "quyenhan/quyenhan/create",
    "/quyenhan/edit" => "quyenhan/quyenhan/update",
    "/quyenhan/delete" => "quyenhan/quyenhan/delete",

    "/nguoidung" => "nguoidung/nguoidung/index",
    "/nguoidung/add" => "nguoidung/nguoidung/create",
    "/nguoidung/edit" => "nguoidung/nguoidung/update",
    "/nguoidung/delete" => "nguoidung/nguoidung/delete",

    "/taikhoan" => "taikhoan/taikhoan/index",
    "/taikhoan/add" => "taikhoan/taikhoan/create",
    "/taikhoan/edit" => "taikhoan/taikhoan/update",
    "/taikhoan/delete" => "taikhoan/taikhoan/delete",

    // "/them" => "book/book/create",
    // "/sua" => "book/book/update",
    "/phanloai" => "phanloai/phanloai/index",
    "/addphanloai" => "phanloai/phanloai/create",
    "/updatephanloai" => "phanloai/phanloai/update",

    // chart.js
    // "/chart" => "login/login/chartPage",
    // "/chart" => "book/book/chartPage",

    // Đơn bán
    "/donban" => "donban/donban/listAllDonbans",
    "/donban/viewthem" => "donban/donban/viewThemdonban",
    "/donban/them" => "donban/donban/themdonban",
    "/donban/xoa" => "donban/donban/xoaDonBan",
    "/donban/viewSua" => "donban/donban/viewSuaDonBan",
    "/donban/sua" => "donban/donban/SuaDonBan",
    "/donban/thanhtoan" => "donban/donban/thanhtoan",
    "/donban/xemDonBan" => "donban/donban/xemDonBan",

    // Đơn nhập
    "/donnhap" => "donnhap/donnhap/listAllDonnhaps",
    "/donnhap/viewthem" => "donnhap/donnhap/viewThemdonnhap",
    "/donnhap/them" => "donnhap/donnhap/themdonnhap",
    "/donnhap/xoa" => "donnhap/donnhap/xoaDonNhap",
    "/donnhap/viewSua" => "donnhap/donnhap/viewSuaDonNhap",
    "/donnhap/sua" => "donnhap/donnhap/SuaDonNhap",
    "/donnhap/thanhtoan" => "donnhap/donnhap/thanhtoan",


    //ThongKE
    "/thongke" => "thongke/thongke/index",
    "/thong-ke-handle" => "thongke/thongke/HandleThongKe",
    "/export" => "thongke/thongke/export"
];