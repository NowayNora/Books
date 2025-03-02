<?php

namespace Taikhoan;

use Nguoidung\nguoidungModel;
use Quyenhan\quyenhanModel;

use Engine\Base;

class TaiKhoanController extends Base
{
    /**
     * Hiển thị danh sách tài khoản
     */
    public function index(): void
    {
        $taikhoanModel = new taikhoanModel();
        // Lấy tất cả tài khoản với thông tin người dùng và quyền hạn
        $taikhoans = $taikhoanModel->getAllTaiKhoansWithDetails();

        // Lấy tất cả người dùng và quyền hạn
        $nguoidungs = $taikhoanModel->getAllNguoidungs();
        $quyenhans = $taikhoanModel->getAllQuyenhans();

        // Truyền dữ liệu vào view
        $this->output->load("taikhoan/taikhoan", [
            'taikhoans' => $taikhoans,
            'nguoidungs' => $nguoidungs,
            'quyenhans' => $quyenhans
        ]);
    }


    // public function index(): void
    // {
    //     // Khởi tạo model
    //     $taikhoanModel = new TaikhoanModel();
    //     $nguoidungModel = new nguoidungModel();
    //     $quyenhanModel = new quyenhanModel();

    //     // Lấy danh sách tài khoản
    //     $taikhoans = $taikhoanModel->getAllTaikhoans(); // Lấy dữ liệu từ bảng `taikhoans`

    //     // Thêm thông tin chi tiết từ NguoiDung và QuyenHan
    //     foreach ($taikhoans as &$taikhoan) {
    //         // Lấy thông tin NguoiDung
    //         if (!empty($taikhoan['id_nguoidung'])) {
    //             $nguoidung = $nguoidungModel->findNguoiDungById($taikhoan['id_nguoidung']);
    //             // Nếu không tìm thấy, gán chữ "Không lấy được data"
    //             $taikhoan['nguoidung'] = $nguoidung ?: "Không lấy được data";
    //         } else {
    //             $taikhoan['nguoidung'] = "Không lấy được id data"; // Trường hợp không có id_nguoidung
    //         }

    //         // Lấy thông tin QuyenHan
    //         if (!empty($taikhoan['id_quyen'])) {
    //             $quyenhan = $quyenhanModel->findQuyenHanById($taikhoan['id_quyen']);
    //             // Nếu không tìm thấy, gán chữ "Không lấy được data"
    //             $taikhoan['quyenhan'] = $quyenhan ?: "Không lấy được data";
    //         } else {
    //             $taikhoan['quyenhan'] = "Không lấy được id data"; // Trường hợp không có id_quyen
    //         }
    //     }

    //     // Gửi dữ liệu ra view
    //     $data = ['taikhoans' => $taikhoans];
    //     $this->output->load("taikhoan/taikhoan", $data);
    // }



    /**
     * Hiển thị modal form tạo mới tài khoản
     */
    public function showCreateModal(): void
    {
        $nguoidungModel = new \Nguoidung\NguoidungModel();
        $quyenhanModel = new \Quyenhan\QuyenhanModel();

        $data['nguoidungs'] = $nguoidungModel->getAllNguoidungs();
        $data['quyenhans'] = $quyenhanModel->getAllQuyenhan();

        $this->output->load("taikhoan/create", $data);
    }

    /**
     * Tạo mới tài khoản
     */
    public function create(): void
    {
        $taikhoanModel = new TaikhoanModel();
        $data = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_quyen = isset($_POST['id_quyen']) ? (int)$_POST['id_quyen'] : null;
            $id_nguoidung = isset($_POST['id_nguoidung']) ? (int)$_POST['id_nguoidung'] : null;
            $username = isset($_POST['username']) ? trim($_POST['username']) : '';
            $password = isset($_POST['password']) ? trim($_POST['password']) : '';
            $trangthai = isset($_POST['trangthai']) ? (bool)$_POST['trangthai'] : true;
            $ngaytao = date("Y-m-d H:i:s");

            if (empty($username) || empty($password)) {
                $data['error'] = 'Tên đăng nhập và mật khẩu không được để trống!';
            } else {
                try {
                    $taikhoanModel->createTaiKhoan($id_quyen, $id_nguoidung, $username, $password, $ngaytao, $trangthai);
                    header("Location: /taikhoan");
                    exit;
                } catch (\Exception $e) {
                    $data['error'] = 'Lỗi khi thêm tài khoản: ' . $e->getMessage();
                }
            }
        }

        $this->output->load("taikhoan/create", $data);
    }

    /**
     * Hiển thị modal chỉnh sửa tài khoản
     * @param int $id_taikhoan
     */
    public function showUpdateModal(int $id_taikhoan): void
    {
        $taikhoanModel = new taikhoanModel();

        // Lấy thông tin tài khoản
        $taikhoan = $taikhoanModel->findTaiKhoanById($id_taikhoan);

        if ($taikhoan) {
            // Lấy danh sách người dùng và quyền hạn từ taikhoanModel
            $nguoidungs = $taikhoanModel->getAllNguoidungs();
            $quyenhans = $taikhoanModel->getAllQuyenhans();

            // Truyền dữ liệu vào modal
            $data = [
                'taikhoan' => $taikhoan,
                'nguoidungs' => $nguoidungs,
                'quyenhans' => $quyenhans,
            ];

            $this->output->load("taikhoan/modal_update", $data);
        } else {
            echo "Không tìm thấy tài khoản cần chỉnh sửa.";
        }
    }

    // public function getAllNguoidungsWithNoAccount(): array
    // {
    //     // Truy vấn lấy tất cả người dùng chưa có tài khoản
    //     $sql = "
    //     SELECT nguoidungs.ID_NGUOIDUNG
    //     FROM nguoidungs
    //     LEFT JOIN taikhoans ON nguoidungs.ID_NGUOIDUNG = taikhoans.ID_NGUOIDUNG
    //     WHERE taikhoans.ID_NGUOIDUNG IS NULL";

    //     return $this->database->query($sql);
    // }

    // public function showUpdateModal(int $id_nguoidung): void
    // {
    //     $nguoidungModel = new NguoiDungModel();
    //     $nguoidung = $nguoidungModel->findNguoiDungById($id_nguoidung);

    //     if ($nguoidung) {
    //         // Lấy danh sách người dùng chưa có tài khoản
    //         $nguoidungs = $nguoidungModel->getAllNguoidungs(); // Tất cả người dùng
    //         $nguoidungsWithAccount = $this->getAllNguoidungsWithNoAccount(); // Người dùng chưa có tài khoản
    //         $this->output->load("nguoidung/modal_update", [
    //             'nguoidung' => $nguoidung,
    //             'nguoidungs' => $nguoidungs,
    //             'nguoidungsWithAccount' => $nguoidungsWithAccount, // Truyền danh sách người dùng chưa có tài khoản vào view
    //         ]);
    //     } else {
    //         echo "Không tìm thấy người dùng cần chỉnh sửa.";
    //     }
    // }



    // public function showUpdateModal(int $id_taikhoan): void
    // {
    //     $taikhoanModel = new taikhoanModel();

    //     // Lấy thông tin tài khoản
    //     $taikhoan = $taikhoanModel->findTaiKhoanById($id_taikhoan);

    //     if ($taikhoan) {
    //         // Lấy danh sách người dùng (bao gồm người dùng hiện tại)
    //         $nguoidungs = $taikhoanModel->getAllNguoidungs($taikhoan['ID_NGUOIDUNG']);
    //         $quyenhans = $taikhoanModel->getAllQuyenhans();

    //         // Truyền dữ liệu vào modal
    //         $data = [
    //             'taikhoan' => $taikhoan,
    //             'nguoidungs' => $nguoidungs,
    //             'quyenhans' => $quyenhans,
    //         ];

    //         $this->output->load("taikhoan/modal_update", $data);
    //     } else {
    //         echo "Không tìm thấy tài khoản cần chỉnh sửa.";
    //     }
    // }




    /**
     * Cập nhật thông tin tài khoản
     */
    public function update(): void
    {
        $taikhoanModel = new TaikhoanModel();
        $data = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_taikhoan = isset($_POST['id_taikhoan']) ? (int)$_POST['id_taikhoan'] : null;
            $id_quyen = isset($_POST['id_quyen']) ? (int)$_POST['id_quyen'] : null;
            $id_nguoidung = isset($_POST['id_nguoidung']) ? (int)$_POST['id_nguoidung'] : null;
            $username = isset($_POST['username']) ? trim($_POST['username']) : '';
            $password = isset($_POST['password']) ? trim($_POST['password']) : '';
            $trangthai = isset($_POST['trangthai']) ? (bool)$_POST['trangthai'] : true;

            if ($id_taikhoan !== null) {
                try {
                    $taikhoanModel->updateTaiKhoan($id_taikhoan, $id_quyen, $id_nguoidung, $username, $password, $trangthai);
                    header("Location: /taikhoan");
                    exit;
                } catch (\Exception $e) {
                    $data['error'] = 'Có lỗi xảy ra khi cập nhật tài khoản: ' . $e->getMessage();
                }
            }
        }

        $this->output->load("taikhoan/update", $data);
    }

    /**
     * Xóa tài khoản
     */
    public function delete(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_taikhoan = isset($_POST['id_taikhoan']) ? (int)$_POST['id_taikhoan'] : null;

            if ($id_taikhoan !== null) {
                $taikhoanModel = new TaikhoanModel();
                $taikhoanModel->deleteTaiKhoanById($id_taikhoan);
                header("Location: /taikhoan");
                exit;
            } else {
                echo "Không tìm thấy ID tài khoản. Không thể xóa.";
            }
        } else {
            echo "Phương thức không hợp lệ.";
        }
    }

    public function updateTaiKhoanVaNguoiDung(): void
    {
        $taikhoanModel = new TaikhoanModel();
        $data = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Lấy dữ liệu tài khoản từ form
            $id_taikhoan = (int)$_POST['id_taikhoan'];
            $id_quyen = (int)$_POST['id_quyen'];
            $id_nguoidung = (int)$_POST['id_nguoidung'];
            $username = trim($_POST['username']);
            $password = trim($_POST['password']);
            $trangthai = (bool)$_POST['trangthai'];

            // Lấy dữ liệu người dùng từ form
            $name = trim($_POST['name']);
            $email = trim($_POST['email']);
            $sdt = trim($_POST['sdt']);
            $diachi = trim($_POST['diachi']);
            $hinhanhnd = isset($_FILES['hinhanhnd']['tmp_name']) && is_uploaded_file($_FILES['hinhanhnd']['tmp_name'])
                ? file_get_contents($_FILES['hinhanhnd']['tmp_name'])
                : null;

            try {
                // Cập nhật tài khoản
                $taikhoanModel->updateTaikhoan($id_taikhoan, $id_quyen, $id_nguoidung, $username, $password, $trangthai);

                // Cập nhật người dùng
                $taikhoanModel->updateNguoidung($id_nguoidung, $name, $hinhanhnd, $email, $sdt, $diachi);

                // Chuyển hướng hoặc thông báo thành công
                header("Location: /taikhoan");
                exit;
            } catch (\Exception $e) {
                $data['error'] = 'Có lỗi xảy ra khi cập nhật: ' . $e->getMessage();
            }
        }

        // Tải view và hiển thị thông báo lỗi (nếu có)
        $this->output->load('taikhoan/update', $data);
    }
}
