<?php

namespace Donban;

use Engine\Base;
use Donban\DonbanModel;

class DonbanController extends Base
{
    public function listAllDonbans()
    {
        $donban_model = new DonbanModel();
        $data = [];
        // Assuming default limit and offset
        $limit = 8; // Số sản phẩm trên mỗi trang
        $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        $offset = ($page - 1) * $limit;
        $donbans = $donban_model->getAllDonBan($limit, $offset);
        // Tìm tổng số nhân viên
        $totalDonBan = $donban_model->countAllDonBan();
        // Tính số trang
        $totalPages = ceil($totalDonBan / $limit);
        // Gán số trang vào mảng $data["totalPages"]
        $data["totalPages"] = $totalPages;

        // Lấy danh sách sách để hiển thị trong form
        $sachs = $donban_model->getAllBooks();
        $data['sachs'] = $sachs;
        $taikhoans = $donban_model->getAllTaiKhoan();
        $data['taikhoans'] = $taikhoans;
        $donbanLocs = $donban_model->getAllDonBanLoc();
        $data['donbanLocs'] = $donbanLocs;

        // Xử lý yêu cầu xóa đơn bán
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['ID_DONBAN'])) {
            $id_donban = filter_input(INPUT_POST, 'ID_DONBAN', FILTER_SANITIZE_NUMBER_INT);

            if ($id_donban) {
                $isDeleted = $donban_model->deleteDonBanById($id_donban);

                if ($isDeleted) {
                    $_SESSION['message'] = "Xóa đơn bán thành công.";
                } else {
                    $_SESSION['message'] = "Xóa đơn bán thất bại.";
                }

                header("Location: /donban");
                exit;
            }
        }

        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $keyword = $_GET['keyword'] ?? ''; // Lấy keyword từ URL, nếu không có thì mặc định là rỗng

            if ($keyword) {
                // Nếu có từ khóa, lọc kết quả theo từ khóa
                $result = $donban_model->filterDonBan($keyword);
                // Nếu có kết quả, gán vào biến $donbans, nếu không thì gán mảng rỗng
                $donbans = empty($result) ? array() : $result;
            } else {
                // Nếu không có từ khóa, lấy tất cả đơn bán
                $donbans = $donban_model->getAllDonBan($limit, $offset);
            }
        }
        if (empty($donbans)) {
            $data['error_message'] = "Không có đơn bán nào.";
        } else {
            $data['donbans'] = $donbans;
        }
        $this->output->load("donban/listDonban", $data);
    }
    public function themdonban()
    {
        $data = array();
        $donban_model = new DonbanModel();
    
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Lấy thông tin từ form
            $ngayban = $_POST['THOIGIANLAPBAN'] ?? null;
            $khachhang = $_POST['ID_TAIKHOAN'] ?? null;
            $tongso_sach = $_POST['TONGSOLUONG'] ?? null;
            $tongtien = $_POST['TONGTIEN'] ?? null;
            $tinhtrang = $_POST['TINHTRANG'] ?? null;
            $id_taikhoan = $_POST['ID_TAIKHOAN'] ?? null;
    
            // Validate dữ liệu
            $requiredFields = [
                'Ngày bán' => $ngayban,
                'Khách hàng' => $khachhang,
                'Số lượng sách' => $tongso_sach,
                'Tổng tiền' => $tongtien,
                'Tình trạng' => $tinhtrang,
                'Tài khoản' => $id_taikhoan,
            ];
            $errors = $this->validateRequiredFields($requiredFields);
            
            if (!empty($errors)) {
                $data['error_message'] = implode('<br>', $errors);
            } else {
                // Kiểm tra số lượng sách và tổng tiền hợp lệ
                if ($tongso_sach <= 0) {
                    $data['error_message'] = "Vui lòng nhập số lượng sách hợp lệ.";
                } elseif ($tongtien <= 0) {
                    $data['error_message'] = "Vui lòng nhập tổng tiền hợp lệ.";
                } else {
                    // Thêm đơn bán mới
                    $donban_id = $donban_model->createDonBan($id_taikhoan, $ngayban, $tongso_sach, $tongtien, $tinhtrang);
                    if ($donban_id) {
                        // Xử lý chi tiết đơn bán
                        $sachIds = $_POST['SACHID'] ?? [];
                        $soluong = $_POST['SOLUONG'] ?? [];
                        $thanhtien = $_POST['THANHTIEN'] ?? [];
                        
                        if (empty($sachIds) || empty($soluong) || empty($thanhtien)) {
                            $data['error_message'] = "Vui lòng chọn sách và nhập số lượng, thành tiền hợp lệ.";
                        } else {
                            foreach ($sachIds as $key => $sachId) {
                                if (empty($soluong[$key]) || empty($thanhtien[$key])) continue;
    
                                $sachId_value = (int) $sachId[0];
                                $soluong_value = (int) $soluong[$key];
                                $thanhtien_value = (float) $thanhtien[$key];
    
                                if ($soluong_value <= 0 || $thanhtien_value <= 0) {
                                    $data['error_message'] = "Số lượng và thành tiền cho mỗi sách phải lớn hơn 0.";
                                    break;
                                }
    
                                $donban_model->createChiTietDonBan($donban_id, $sachId_value, $soluong_value, $thanhtien_value);
                            }
    
                            // Thành công
                            if (empty($data['error_message'])) {
                                $data['success_message'] = "Thêm đơn bán mới thành công!";
                            }
                        }
                    } else {
                        $data['error_message'] = "Không thể tạo đơn bán.";
                    }
                }
            }
        }
        // Trả về dữ liệu JSON với thông tin phản hồi
        echo json_encode($data);
        exit;
    }
    
    public function xoaDonBan()
    {
        $data = [];
        $donban_model = new DonbanModel();

        $id_donban = $_POST['ID_DONBAN'] ?? null;

        if ($id_donban) {
            // Kiểm tra nếu đơn bán tồn tại
            $donban = $donban_model->getDonBanById($id_donban);

            $data['donban'] = $donban ?? null;
            if (!$data['donban']) {
                $_SESSION['error_message'] = "Đơn bán không tồn tại.";
            } else {
                // Kiểm tra trạng thái đơn bán
                if ($donban['TINHTRANG'] == 1) {
                    // Nếu trạng thái đơn bán là 1 (đã thanh toán), không cho phép xóa
                    $_SESSION['error_message'] = "Không thể xóa đơn bán đã thanh toán.";
                } else {
                    // Kiểm tra nếu có chi tiết đơn bán
                    if ($donban_model->checkChiTietDonBan($id_donban)) {
                        // Nếu có chi tiết, xóa chi tiết đơn bán trước
                        $isDeleted = $donban_model->deleteChiTietDonBanByDonBanId($id_donban);

                        if ($isDeleted) {
                            // Sau khi xóa chi tiết đơn bán, xóa đơn bán
                            $isDeletedDonBan = $donban_model->deleteDonBanById($id_donban);

                            if ($isDeletedDonBan) {
                                $_SESSION['message'] = "Đơn bán và chi tiết đơn bán đã được xóa.";
                            } else {
                                $_SESSION['error_message'] = "Xóa đơn bán thất bại.";
                            }
                        } else {
                            $_SESSION['error_message'] = "Xóa chi tiết đơn bán thất bại.";
                        }
                    } else {
                        // Nếu không có chi tiết, chỉ xóa đơn bán
                        $isDeletedDonBan = $donban_model->deleteDonBanById($id_donban);

                        if ($isDeletedDonBan) {
                            $_SESSION['message'] = "Đơn bán đã được xóa.";
                        } else {
                            $_SESSION['error_message'] = "Xóa đơn bán thất bại.";
                        }
                    }
                }
            }
        } else {
            $_SESSION['error_message'] = "ID đơn bán không hợp lệ.";
        }

        // Chuyển hướng về trang danh sách đơn bán
        header("Location: /donban");
        exit;
    }
    public function viewSuaDonBan()
    {
        $id_donban = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

        if (!$id_donban) {
            die("ID đơn bán không hợp lệ.");
        }

        $data = [];
        $donban_model = new DonbanModel();

        // Lấy thông tin đơn bán theo ID
        $donban = $donban_model->getDonBanById($id_donban);
        if (!$donban) {
            die("Không tìm thấy đơn bán.");
        }
        // Định dạng lại thời gian THOIGIANLAPB

        // Lấy thông tin tài khoản của đơn bán
        $taikhoansHoaDon = $donban_model->getTaiKhoanById($donban['ID_TAIKHOAN']);
        $data['taikhoansHoaDon'] = $taikhoansHoaDon;
        $data['donban'] = $donban; // Lưu thông tin đơn bán vào data

        // Lấy danh sách sách để hiển thị trong form
        $sachs = $donban_model->getAllBooks();
        $data['sachs'] = $sachs;

        // Lấy danh sách tất cả tài khoản
        $taikhoans = $donban_model->getAllTaiKhoan();
        $data['taikhoans'] = $taikhoans;

        // Lấy chi tiết đơn bán theo ID đơn bán
        $chiTietDonBan = $donban_model->getChiTietDonBanById($id_donban);
        $data['chiTietDonBan'] = $chiTietDonBan;

        // Tiếp tục xử lý logic chỉnh sửa đơn bán
        $this->output->load("donban/updateHoadonban", $data);
    }
    public function SuaDonBan()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $idDonBan = $_GET['id']; // Lấy ID đơn bán từ query string
            $idTaiKhoan = $_POST['ID_TAIKHOAN'];
            $thoiGianLap = $_POST['THOIGIANLAPBAN'];
            $tongSoLuong = $_POST['TONGSOLUONG'];
            $tongTien = $_POST['TONGTIEN'];
            $chiTietDonBan = $_POST['sachs'] ?? [];
            $donBanModel = new DonBanModel();

            // Kiểm tra đơn bán có tồn tại hay không
            $donBanHienTai = $donBanModel->getDonBanById($idDonBan);

            if ($donBanHienTai) {
                // Cập nhật thông tin đơn bán
                $isUpdated = $donBanModel->updateDonBan($idDonBan, $idTaiKhoan, $thoiGianLap, $tongSoLuong, $tongTien);

                if ($isUpdated) {
                    // Xóa chi tiết đơn bán cũ
                    $donBanModel->deleteChiTietDonBan($idDonBan);

                    // Lưu chi tiết đơn bán mới
                    foreach ($chiTietDonBan as $idSach => $chiTiet) {
                        $sachId = (int) $chiTiet['id'];
                        $soLuong = $chiTiet['so_luong'];
                        $thanh_tien = $chiTiet['thanh_tien'];
                        $donBanModel->insertChiTietDonBan($idDonBan, $sachId, $soLuong, $thanh_tien);
                    }

                    // Lưu thông báo thành công vào session
                    $_SESSION['message'] = "Cập nhật đơn bán thành công!";

                    // Chuyển hướng về trang danh sách đơn bán (hoặc trang khác nếu cần)
                    header("Location: /donban");
                    exit;
                } else {
                    // Lưu thông báo lỗi vào session
                    $_SESSION['error_message'] = "Lỗi khi cập nhật đơn bán!";

                    // Chuyển hướng lại về trang chỉnh sửa
                    header("Location: /donban/sua?id=$idDonBan");
                    exit;
                }
            } else {
                // Lưu thông báo lỗi nếu không tìm thấy đơn bán
                $_SESSION['error_message'] = "Không tìm thấy đơn bán với ID: $idDonBan!";

                // Chuyển hướng lại về trang chỉnh sửa
                header("Location: /donban/sua?id=$idDonBan");
                exit;
            }
        } else {
            // Nếu phương thức không phải là POST, lưu thông báo lỗi
            $_SESSION['error_message'] = "Phương thức không hợp lệ!";

            // Chuyển hướng về trang danh sách đơn bán
            header("Location: /donban");
            exit;
        }
    }
    public function thanhtoan()
    {
        // Lấy ID đơn bán và trạng thái từ URL
        $id_donban = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        $tinhTrang = filter_input(INPUT_GET, 'TINHTRANG', FILTER_SANITIZE_NUMBER_INT); // Lấy giá trị TINHTRANG

        if (!$id_donban || !isset($tinhTrang)) {
            $_SESSION['error_message'] = "ID đơn bán hoặc tình trạng không hợp lệ.";
            header("Location: /donban");
            exit();
        }

        // Khởi tạo model DonbanModel để thao tác với cơ sở dữ liệu
        $donban_model = new DonbanModel();

        // Lấy thông tin hóa đơn từ cơ sở dữ liệu dựa trên ID
        $donban = $donban_model->getDonBanById($id_donban);

        if (!$donban) {
            $_SESSION['error_message'] = "Không tìm thấy đơn bán.";
            header("Location: /donban");
            exit();
        }

        // Kiểm tra trạng thái và cập nhật
        if ($tinhTrang == 0) {
            // Cập nhật trạng thái hóa đơn thành 1 (đã thanh toán)
            $updateSuccess = $donban_model->updateDonBanStatus($id_donban, 1);
        } elseif ($tinhTrang == 1) {
            // Cập nhật trạng thái hóa đơn thành 0 (chưa thanh toán)
            $updateSuccess = $donban_model->updateDonBanStatus($id_donban, 0);
        } else {
            $_SESSION['error_message'] = "Tình trạng thanh toán không hợp lệ.";
            header("Location: /donban");
            exit();
        }

        if ($updateSuccess) {
            // Lưu thông báo thành công vào session và chuyển hướng đến trang danh sách đơn bán
            $_SESSION['message'] = "Cập nhật trạng thái thanh toán thành công!";
            header("Location: /donban");
            exit();
        } else {
            // Lưu thông báo thất bại vào session và chuyển hướng
            $_SESSION['error_message'] = "Cập nhật trạng thái thanh toán không thành công.";
            header("Location: /donban");
            exit();
        }
    }
    private function validateRequiredFields($fields)
    {
        $errors = [];

        foreach ($fields as $field => $value) {
            // Kiểm tra trường hợp "TINHTRANG" và cho phép giá trị 0
            if ($field == 'TINHTRANG' && $value === '0') {
                continue;  // Bỏ qua kiểm tra nếu TINHTRANG = 0
            }

            // Kiểm tra các trường còn lại có giá trị trống
            if (empty($value) && $value !== '0') {
                $errors[] = "Vui lòng nhập đầy đủ thông tin cho trường '$field'.";
            }
        }

        return $errors;
    }
    public function viewThemdonban()
    {
        $data = [];
        $donban_model = new DonbanModel();

        // Lấy danh sách sách để hiển thị trong form
        $sachs = $donban_model->getAllBooks();
        $data['sachs'] = $sachs;

        // Lấy danh sách tất cả tài khoản
        $taikhoans = $donban_model->getAllTaiKhoan();
        $data['taikhoans'] = $taikhoans;
        // Tiếp tục xử lý logic chỉnh sửa đơn bán
        $this->output->load("donban/createDonBan", $data);
    }
    public function xemDonBan()
    {
        // Lấy ID đơn bán từ request
        $id_donban = isset($_GET['id']) ? intval($_GET['id']) : 0;
        if ($id_donban <= 0) {
            die("ID đơn bán không hợp lệ.");
        }
        // Gọi Model để lấy dữ liệu
        $donbanModel = new DonbanModel();
        $donban = $donbanModel->getTTDonBanById($id_donban);
        $chitiet_donban = $donbanModel->getChiTietDonBan($id_donban);

        if (!$donban) {
            die("Không tìm thấy đơn bán.");
        }

        // Truyền dữ liệu qua View
        $data = [
            'donban' => $donban,
            'chitiet_donban' => $chitiet_donban
        ];

        $this->output->load("donban/chitietDonBan", $data);
    }
}