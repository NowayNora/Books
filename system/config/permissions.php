<?php

/**
 * Permission configuration for route authorization
 * - 'public': Routes accessible without login
 * - 'protected': Routes requiring login and specific roles
 * Roles: 1 = Admin, 2 = Staff, 3 = Customer
 */

return [
    'public' => [
        '/',
        '/login',
        '/login/logout',
        '/register',
        '/forgot-password',
    ],
    'protected' => [
        '/index' => [1, 2, 3, 5],          // All logged-in users
        '/book' => [5],             // Admin, Staff
        '/book/add' => [5],
        '/book/update' => [5],
        '/book/delete' => [5],
        '/quyenhan' => [5],            // Admin only
        '/quyenhan/add' => [5],
        '/quyenhan/edit' => [5],
        '/quyenhan/delete' => [5],
        '/nguoidung' => [5],           // Admin only
        '/nguoidung/add' => [5],
        '/nguoidung/edit' => [5],
        '/nguoidung/delete' => [5],
        '/taikhoan' => [5],         // Admin, Staff
        '/taikhoan/add' => [5],
        '/taikhoan/edit' => [5],
        '/taikhoan/delete' => [5],
        '/phanloai' => [5],         // Admin, Staff
        '/addphanloai' => [5],
        '/updatephanloai' => [5],
        '/donban' => [5],           // Admin, Staff
        '/donban/viewthem' => [5],
        '/donban/them' => [5],
        '/donban/xoa' => [5],
        '/donban/viewSua' => [5],
        '/donban/sua' => [5],
        '/donban/thanhtoan' => [5],
        '/donban/xemDonBan' => [5],
        '/donnhap' => [5],          // Admin, Staff
        '/donnhap/viewthem' => [5],
        '/donnhap/them' => [5],
        '/donnhap/xoa' => [5],
        '/donnhap/viewSua' => [5],
        '/donnhap/sua' => [5],
        '/donnhap/thanhtoan' => [5],
        '/thongke' => [5],             // Admin only
        '/thong-ke-handle' => [5],
        '/export' => [5],
    ],
];
