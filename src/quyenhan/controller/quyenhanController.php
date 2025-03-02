<?php

namespace Quyenhan;

use Engine\Base;

class QuyenhanController extends Base
{
    public function index(): void
    {
        $quyenhanModel = new QuyenhanModel();
        $data = array();
        $data['quyenhans'] = $quyenhanModel->getAllQuyenhan();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id_quyen = $_POST['id_quyen'];
            $quyenhanModel->deletequyenhanById($id_quyen);
            // Redirect về này trang chủ
            header("Location: /quyenhan");
        }

        $this->output->load("quyenhan/quyenhan", $data);
    }

    public function showCreateModal(): void
    {
        $this->output->load("quyenhan/create");
    }

    /**
     * Lưu quyền mới
     */
    public function create()
    {
        $data = array();
        $quyenhan_model = new quyenhanModel();

        // Nếu phương thức hiện tại là POST => thực hiện thêm dữ liệu
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Kiểm tra và lấy dữ liệu từ form
            $tenquyen = isset($_POST['tenquyen']) ? trim($_POST['tenquyen']) : '';
            $motaquyen = isset($_POST['motaquyen']) ? trim($_POST['motaquyen']) : '';

            // Kiểm tra tính hợp lệ của dữ liệu
            if (empty($tenquyen) || empty($motaquyen)) {
                $data['error'] = 'Tên quyền và mô tả không được để trống!';
            } else {
                // Gọi model để thêm quyền mới
                try {
                    $quyenhan_model->createQuyenhan($tenquyen, $motaquyen);
                    // Chuyển hướng về trang danh sách sau khi thêm thành công
                    header("Location: /quyenhan");
                    exit;
                } catch (\Exception $e) {
                    $data['error'] = 'Đã xảy ra lỗi khi thêm quyền: ' . $e->getMessage();
                }
            }
        }

        // Load view với thông báo lỗi (nếu có)
        $this->output->load("quyenhan/quyenhan/index");
    }

    /**
     * Hiển thị modal form chỉnh sửa quyền
     * @param int $id
     */
    public function showUpdateModal(int $id_quyen): void
    {
        $quyenhanModel = new QuyenhanModel();
        $quyen = $quyenhanModel->findquyenhanById($id_quyen);

        if ($quyen) {
            $this->output->load("quyenhan/modal_update", ['quyen' => $quyen]);
        } else {
            echo "Không tìm thấy quyền cần chỉnh sửa.";
        }
    }


    public function update()
    {
        $data = array();
        $quyenhan_model = new QuyenhanModel();
        // Lấy dữ liệu nếu có $id
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id_quyen = isset($_POST['id_quyen']) ? (int) $_POST['id_quyen'] : null;
            $tenquyen = isset($_POST['tenquyen']) ? $_POST['tenquyen'] : '';
            $motaquyen = isset($_POST['motaquyen']) ? $_POST['motaquyen'] : '';

            if ($id_quyen !== null) {
                $quyenhan_model->updateQuyenhan(
                    $id_quyen,
                    $tenquyen,
                    $motaquyen
                );
                header("Location: /quyenhan");
            }
        } else {
            // Lấy dữ liệu nếu có $id
            if ($_GET['id_quyen']) {
                $data['quyenhans'] = $quyenhan_model->findquyenhanById($_GET['id']);
            }
            $this->output->load("quyenhan/quyenhan/index", $data);
        }
    }


    /**
     * Xóa quyền
     * @param int $id
     */
    public function delete(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_quyen = isset($_POST['id_quyen']) ? (int) $_POST['id_quyen'] : null;

            if ($id_quyen !== null) {
                $quyenhanModel = new QuyenhanModel();
                $quyenhanModel->deletequyenhanById($id_quyen);

                // Redirect về trang danh sách quyền
                header("Location: /quyenhan");
                exit;
            } else {
                echo "Không tìm thấy ID quyền. Không thể xóa.";
            }
        } else {
            echo "Phương thức không hợp lệ.";
        }
    }
}
