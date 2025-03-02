<?php

namespace Nguoidung;

use Engine\Base;

class NguoiDungController extends Base
{
    /**
     * Hiển thị danh sách người dùng
     */
    public function index(): void
    {
        // session_start(); // Bắt đầu session
        // if (!isset($_SESSION['user_logged_in'])) {
        //     header("Location: /");
        //     exit();
        // }
        $nguoidungModel = new nguoidungModel();
        $data = array();
        $data['nguoidungs'] = $nguoidungModel->getAllNguoidungs();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_nguoidung = isset($_POST['id_nguoidung']) ? (int) $_POST['id_nguoidung'] : null;
            if ($id_nguoidung !== null) {
                $nguoidungModel->deleteNguoiDungById($id_nguoidung);
                header("Location: /nguoidung");
            }
        }

        if ($this->isAjax()) {
            // echo "lor dien";
            // $this->output->partial("book/bookListPartial", $data);
            $this->output->load("nguoidung/nguoidung", $data);
        } else {
            // echo "lor dien";
            $this->output->load("nguoidung/nguoidung", $data);
        }

        // $this->output->load("nguoidung/nguoidung", $data);
    }

    /**
     * Hiển thị modal form tạo mới người dùng
     */
    public function showCreateModal(): void
    {
        $this->output->load("nguoidung/create");
    }

    /**
     * Tạo mới người dùng
     */

    // public function create(): void
    // {
    //     $nguoidungModel = new NguoiDungModel();
    //     $data = [];

    //     if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //         $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    //         $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    //         $sdt = isset($_POST['sdt']) ? trim($_POST['sdt']) : '';
    //         $diachi = isset($_POST['diachi']) ? trim($_POST['diachi']) : '';
    //         $hinhanhnd = null;

    //         // Kiểm tra và xử lý file upload
    //         if (!empty($_FILES['hinhanhnd']['tmp_name'])) {
    //             $hinhanhnd = file_get_contents($_FILES['hinhanhnd']['tmp_name']); // Đọc dữ liệu file
    //         }

    //         if (empty($name) || empty($email)) {
    //             $data['error'] = 'Tên và email không được để trống!';
    //         } else {
    //             try {
    //                 // Truyền dữ liệu vào model
    //                 $nguoidungModel->createNguoiDung($name, $email, $sdt, $diachi, $hinhanhnd);
    //                 header("Location: /nguoidung");
    //                 exit;
    //             } catch (\Exception $e) {
    //                 $data['error'] = 'Lỗi khi thêm người dùng: ' . $e->getMessage();
    //             }
    //         }
    //     }

    //     $this->output->load("nguoidung/create", $data);
    // }


    public function create(): void
    {
        $nguoidungModel = new NguoiDungModel();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = isset($_POST['name']) ? trim($_POST['name']) : '';
            $email = isset($_POST['email']) ? trim($_POST['email']) : '';
            $sdt = isset($_POST['sdt']) ? trim($_POST['sdt']) : '';
            $diachi = isset($_POST['diachi']) ? trim($_POST['diachi']) : '';
            $hinhanhnd = null;

            // Xử lý file hình ảnh nếu có
            if (!empty($_FILES['hinhanhnd']['tmp_name'])) {
                $hinhanhnd = file_get_contents($_FILES['hinhanhnd']['tmp_name']);
            }

            if (empty($name) || empty($email)) {
                echo json_encode(["success" => false, "message" => "Tên và email không được để trống!"]);
                exit;
            }

            try {
                // Thêm người dùng vào database
                $nguoidungModel->createNguoiDung($name, $email, $sdt, $diachi, $hinhanhnd);

                // Chuyển hướng về trang danh sách người dùng
                header("Location: /nguoidung");
                exit;
            } catch (\Exception $e) {
                echo json_encode(["success" => false, "message" => "Lỗi khi thêm người dùng: " . $e->getMessage()]);
            }
        }
    }


    /**
     * Hiển thị modal chỉnh sửa người dùng
     * @param int $id_nguoidung
     */
    public function showUpdateModal(int $id_nguoidung): void
    {
        $nguoidungModel = new NguoiDungModel();
        $nguoidung = $nguoidungModel->findNguoiDungById($id_nguoidung);

        if ($nguoidung) {
            $this->output->load("nguoidung/modal_update", ['nguoidung' => $nguoidung]);
        } else {
            echo "Không tìm thấy người dùng cần chỉnh sửa.";
        }
    }

    /**
     * Cập nhật thông tin người dùng
     */
    // public function update(): void
    // {
    //     $nguoidungModel = new NguoiDungModel();
    //     $data = [];

    //     if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //         $id_nguoidung = isset($_POST['id_nguoidung']) ? (int) $_POST['id_nguoidung'] : null;
    //         $name = isset($_POST['name']) ? $_POST['name'] : '';
    //         $email = isset($_POST['email']) ? $_POST['email'] : '';
    //         $sdt = isset($_POST['sdt']) ? $_POST['sdt'] : '';
    //         $diachi = isset($_POST['diachi']) ? $_POST['diachi'] : '';
    //         $hinhanhnd = isset($_POST['hinhanhnd']) ? $_POST['hinhanhnd'] : '';

    //         if ($id_nguoidung !== null) {
    //             $nguoidungModel->updateNguoiDung($id_nguoidung, $name, $email, $sdt, $diachi, $hinhanhnd);
    //             header("Location: /nguoidung");
    //             exit;
    //         }
    //     }

    //     $this->output->load("nguoidung/update", $data);
    // }


    public function update(): void
    {
        $nguoidungModel = new NguoiDungModel();
        $data = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_nguoidung = isset($_POST['id_nguoidung']) ? (int)$_POST['id_nguoidung'] : null;
            $name = isset($_POST['name']) ? trim($_POST['name']) : '';
            $email = isset($_POST['email']) ? trim($_POST['email']) : '';
            $sdt = isset($_POST['sdt']) ? trim($_POST['sdt']) : '';
            $diachi = isset($_POST['diachi']) ? trim($_POST['diachi']) : '';
            $hinhanhnd = null;

            // Xử lý file hình ảnh
            if (isset($_FILES['hinhanhnd']) && $_FILES['hinhanhnd']['error'] === UPLOAD_ERR_OK) {
                $fileTmpPath = $_FILES['hinhanhnd']['tmp_name'];
                $fileType = mime_content_type($fileTmpPath);

                // Kiểm tra loại file (chỉ chấp nhận ảnh)
                if (strpos($fileType, 'image') === 0) {
                    $hinhanhnd = file_get_contents($fileTmpPath);
                } else {
                    $data['error'] = 'File tải lên không phải là hình ảnh hợp lệ.';
                }
            }

            if ($id_nguoidung !== null) {
                try {
                    $nguoidungModel->updateNguoiDung($id_nguoidung, $name, $email, $sdt, $diachi, $hinhanhnd);
                    header("Location: /nguoidung");
                    exit;
                } catch (\Exception $e) {
                    $data['error'] = 'Có lỗi xảy ra khi cập nhật người dùng: ' . $e->getMessage();
                }
            }
        }

        $this->output->load("nguoidung/update", $data);
    }

    /**
     * Xóa người dùng
     */
    public function delete(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_nguoidung = isset($_POST['id_nguoidung']) ? (int) $_POST['id_nguoidung'] : null;

            if ($id_nguoidung !== null) {
                $nguoidungModel = new NguoiDungModel();
                $nguoidungModel->deleteNguoiDungById($id_nguoidung);
                header("Location: /nguoidung");
                exit;
            } else {
                echo "Không tìm thấy ID người dùng. Không thể xóa.";
            }
        } else {
            echo "Phương thức không hợp lệ.";
        }
    }

    private function isAjax(): bool
    {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }
}
