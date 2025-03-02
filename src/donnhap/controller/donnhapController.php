<?php

namespace Donnhap;

use Engine\Base;
use Donnhap\DonnhapModel;

class DonnhapController extends Base
{
    public function listAllDonnhaps()
    {
        $donnhap_model = new DonnhapModel();
        $data = [];
        $limit = 8;
        $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        $offset = ($page - 1) * $limit;
        $donnhaps = $donnhap_model->getAllDonNhap($limit, $offset);
        $totalDonNhap = $donnhap_model->countAllDonNhap();
        $totalPages = ceil($totalDonNhap / $limit);
        $data["totalPages"] = $totalPages;
        $sachs = $donnhap_model->getAllBooks();
        $data['sachs'] = $sachs;
        $taikhoans = $donnhap_model->getAllTaiKhoan();
        $data['taikhoans'] = $taikhoans;


        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['ID_DONNHAP'])) {
            $id_donnhap = filter_input(INPUT_POST, 'ID_DONNHAP', FILTER_SANITIZE_NUMBER_INT);

            if ($id_donnhap) {
                $isDeleted = $donnhap_model->deleteDonNhapById($id_donnhap);

                if ($isDeleted) {
                    $_SESSION['message'] = "Xóa đơn nhập thành công.";
                } else {
                    $_SESSION['message'] = "Xóa đơn nhập thất bại.";
                }

                header("Location: /donnhap");
                exit;
            }
        }

        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $keyword = $_GET['keyword'] ?? '';

            if ($keyword) {

                $result = $donnhap_model->filterDonNhap($keyword);

                $donnhaps = empty($result) ? array() : $result;
            } else {

                $donnhaps = $donnhap_model->getAllDonNhap($limit, $offset);
            }
        }
        if (empty($donnhaps)) {
            $data['error_message'] = "Không có đơn nhập nào.";
        } else {
            $data['donnhaps'] = $donnhaps;
        }
        $this->output->load("donnhap/listDonnhap", $data);
    }
    public function themdonnhap()
    {
        $data = array();
        $donnhap_model = new DonnhapModel();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $ngaynhap = $_POST['THOIGIANLAP'] ?? null;
            $nguoilap = $_POST['ID_TAIKHOAN'] ?? null;
            $tongso_sach = $_POST['TONGSOLUONG'] ?? null;
            $tongtien = $_POST['TONGTIEN'] ?? null;
            $tinhtrang = $_POST['TINHTRANG'] ?? null;
            $noinhap = $_POST['NOINHAP'] ?? null;
            $id_taikhoan = $_POST['ID_TAIKHOAN'] ?? null;

            $requiredFields = [
                'Ngày nhập' => $ngaynhap,
                'Người lập' => $nguoilap,
                'Số lượng sách' => $tongso_sach,
                'Tổng tiền' => $tongtien,
                'Tình trạng' => $tinhtrang,
                'Nơi nhập' => $noinhap,
                'Tài khoản' => $id_taikhoan,
            ];
            $errors = $this->validateRequiredFields($requiredFields);
            if (!empty($errors)) {
                $data['error_message'] = implode('<br>', $errors);
                $_SESSION['error_message'] = $data['error_message'];
            } else {
                if ($tongso_sach <= 0) {
                    $_SESSION['error_message'] = "Vui lòng nhập số lượng sách hợp lệ.";
                } elseif ($tongtien <= 0) {
                    $_SESSION['error_message'] = "Vui lòng nhập tổng tiền hợp lệ.";
                } else {

                    $donnhap_id = $donnhap_model->createDonNhap($id_taikhoan, $ngaynhap, $tongso_sach, $tongtien, $tinhtrang, $noinhap);

                    if ($donnhap_id) {
                        $sachIds = $_POST['SACHID'] ?? [];
                        $soluong = $_POST['SOLUONG'] ?? [];
                        $thanhtien = $_POST['THANHTIEN'] ?? [];

                        if (empty($sachIds) || empty($soluong) || empty($thanhtien)) {
                            $_SESSION['error_message'] = "Vui lòng chọn sách và nhập số lượng, thành tiền hợp lệ."; // Lưu thông báo lỗi vào session
                        } else {
                            foreach ($sachIds as $key => $sachId) {
                                if (empty($soluong[$key]) || empty($thanhtien[$key])) {
                                    $_SESSION['error_message'] = "Số lượng và thành tiền cho mỗi sách phải hợp lệ."; // Lưu thông báo lỗi vào session
                                    break;
                                }

                                $sachId_value = (int) $sachId[0];
                                $soluong_value = (int) $soluong[$key];
                                $thanhtien_value = (float) $thanhtien[$key];
                                if ($soluong_value <= 0 || $thanhtien_value <= 0) {
                                    $_SESSION['error_message'] = "Số lượng và thành tiền cho mỗi sách phải lớn hơn 0."; // Lưu thông báo lỗi vào session
                                    break;
                                }

                                $donnhap_model->createChiTietDonNhap($donnhap_id, $sachId_value, $soluong_value, $thanhtien_value);
                            }

                            if (empty($_SESSION['error_message'])) {
                                $_SESSION['message'] = "Cập nhật đơn nhập thành công!";
                            }
                        }
                    } else {
                        $_SESSION['error_message'] = "Không thể tạo đơn nhập.";
                    }
                }
            }
        }
        header("Location: /donnhap");
        exit;
    }
    public function xoaDonNhap()
    {
        $data = [];
        $donnhap_model = new DonnhapModel();

        $id_donnhap = $_POST['ID_DONNHAP'] ?? null;

        if ($id_donnhap) {
            $donnhap = $donnhap_model->getDonNhapById($id_donnhap);

            $data['donnhap'] = $donnhap ?? null;
            if (!$data['donnhap']) {
                $_SESSION['error_message'] = "Đơn nhập không tồn tại.";
            } else {
                if ($donnhap['TINHTRANG'] == 1) {
                    $_SESSION['error_message'] = "Không thể xóa đơn nhập đã thanh toán.";
                } else {
                    if ($donnhap_model->checkChiTietDonNhap($id_donnhap)) {
                        $isDeleted = $donnhap_model->deleteChiTietDonNhapById($id_donnhap);

                        if ($isDeleted) {
                            $isDeletedDonNhap = $donnhap_model->deleteDonNhapById($id_donnhap);

                            if ($isDeletedDonNhap) {
                                $_SESSION['message'] = "Đơn nhập và chi tiết đơn nhập đã được xóa.";
                            } else {
                                $_SESSION['error_message'] = "Xóa đơn nhập thất bại.";
                            }
                        } else {
                            $_SESSION['error_message'] = "Xóa chi tiết đơn nhập thất bại.";
                        }
                    } else {
                        $isDeletedDonNhap = $donnhap_model->deleteDonNhapById($id_donnhap);

                        if ($isDeletedDonNhap) {
                            $_SESSION['message'] = "Đơn nhập đã được xóa.";
                        } else {
                            $_SESSION['error_message'] = "Xóa đơn nhập thất bại.";
                        }
                    }
                }
            }
        } else {
            $_SESSION['error_message'] = "ID đơn nhập không hợp lệ.";
        }
        header("Location: /donnhap");
        exit;
    }
    public function viewSuaDonNhap()
    {
        $id_donnhap = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

        if (!$id_donnhap) {
            die("ID đơn nhập không hợp lệ.");
        }

        $data = [];
        $donnhap_model = new DonnhapModel();

        $donnhap = $donnhap_model->getDonNhapById($id_donnhap);
        if (!$donnhap) {
            die("Không tìm thấy đơn nhập.");
        }

        $taikhoansHoaDon = $donnhap_model->getTaiKhoanById($donnhap['ID_TAIKHOAN']);
        $data['taikhoansHoaDon'] = $taikhoansHoaDon;
        $data['donnhap'] = $donnhap;

        $sachs = $donnhap_model->getAllBooks();
        $data['sachs'] = $sachs;

        $taikhoans = $donnhap_model->getAllTaiKhoan();
        $data['taikhoans'] = $taikhoans;

        $chiTietDonNhap = $donnhap_model->getChiTietDonNhapById($id_donnhap);
        $data['chiTietDonNhap'] = $chiTietDonNhap;

        $this->output->load("donnhap/updateHoadonnhap", $data);
    }
    public function SuaDonNhap()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $idDonNhap = $_GET['id'];
            $idTaiKhoan = $_POST['ID_TAIKHOAN'];
            $thoiGianLap = $_POST['THOIGIANLAP'];
            $noiNhap = $_POST['NOINHAP'];
            $tongSoLuong = $_POST['TONGSOLUONG'];
            $tongTien = $_POST['TONGTIEN'];
            $chiTietDonNhap = $_POST['sachs'] ?? [];
            $donNhapModel = new DonNhapModel();

            $donNhapHienTai = $donNhapModel->getDonNhapById($idDonNhap);

            if ($donNhapHienTai) {
                $isUpdated = $donNhapModel->updateDonNhap($idDonNhap, $idTaiKhoan, $thoiGianLap, $noiNhap, $tongSoLuong, $tongTien);

                if ($isUpdated) {

                    $donNhapModel->deleteChiTietDonNhap($idDonNhap);

                    foreach ($chiTietDonNhap as $idSach => $chiTiet) {
                        $sachId = (int) $chiTiet['id'];
                        $soLuong = $chiTiet['so_luong'];
                        $thanh_tien = $chiTiet['thanh_tien'];
                        $donNhapModel->insertChiTietDonNhap($idDonNhap, $sachId, $soLuong, $thanh_tien);
                    }
                    $_SESSION['message'] = "Cập nhật đơn nhập thành công!";

                    header("Location: /donnhap");
                    exit;
                } else {
                    $_SESSION['error_message'] = "Lỗi khi cập nhật đơn nhập!";


                    header("Location: /donnhap/sua?id=$idDonNhap");
                    exit;
                }
            } else {

                $_SESSION['error_message'] = "Không tìm thấy đơn nhập với ID: $idDonNhap!";

                header("Location: /donnhap/sua?id=$idDonNhap");
                exit;
            }
        } else {
            $_SESSION['error_message'] = "Phương thức không hợp lệ!";
            header("Location: /donnhap");
            exit;
        }
    }
    public function thanhtoan()
    {
        $id_donnhap = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        $tinhTrang = filter_input(INPUT_GET, 'TINHTRANG', FILTER_SANITIZE_NUMBER_INT);

        if (!$id_donnhap || !isset($tinhTrang)) {
            $_SESSION['error_message'] = "ID đơn bán hoặc tình trạng không hợp lệ.";
            header("Location: /donnhap");
            exit();
        }

        $donnhap_model = new DonnhapModel();

        $donnhap = $donnhap_model->getDonNhapById($id_donnhap);

        if (!$donnhap) {
            $_SESSION['error_message'] = "Không tìm thấy đơn nhập.";
            header("Location: /donnhap");
            exit();
        }

        if ($tinhTrang == 0) {
            $updateSuccess = $donnhap_model->updateDonNhapStatus($id_donnhap, 1);
        } elseif ($tinhTrang == 1) {
            $updateSuccess = $donnhap_model->updateDonNhapStatus($id_donnhap, 0);
        } else {
            $_SESSION['error_message'] = "Tình trạng thanh toán không hợp lệ.";
            header("Location: /donnhap");
            exit();
        }

        if ($updateSuccess) {

            $_SESSION['message'] = "Cập nhật trạng thái thanh toán thành công!";
            header("Location: /donnhap");
            exit();
        } else {
            $_SESSION['error_message'] = "Cập nhật trạng thái thanh toán không thành công.";
            header("Location: /donnhap");
            exit();
        }
    }
    private function validateRequiredFields($fields)
    {
        $errors = [];

        foreach ($fields as $field => $value) {

            if ($field == 'TINHTRANG' && $value === '0') {
                continue;
            }
            if (empty($value) && $value !== '0') {
                $errors[] = "Vui lòng nhập đầy đủ thông tin cho trường '$field'.";
            }
        }

        return $errors;
    }
    public function viewThemdonnhap()
    {
        $data = [];
        $donnhap_model = new DonnhapModel();

        $sachs = $donnhap_model->getAllBooks();
        $data['sachs'] = $sachs;

        $taikhoans = $donnhap_model->getAllTaiKhoan();
        $data['taikhoans'] = $taikhoans;
        $this->output->load("donnhap/createDonNhap", $data);
    }
}