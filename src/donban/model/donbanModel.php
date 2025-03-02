<?php

namespace Donban;

use Engine\Base;
use PDO;
use PDOException;

class DonbanModel extends Base
{
    private $database;
    public function __construct()
    {
        try {
            $this->database = new PDO(
                //"mysql:host=127.0.0.1;port=3308;dbname=qlsach;charset=utf8mb4",
                "mysql:host=localhost;dbname=qlsach;charset=utf8mb4",
                "root",
                ""
            );
            $this->database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Kết nối cơ sở dữ liệu thất bại: " . $e->getMessage());
        }
    }

    public function createDonBan(string $idTaiKhoan, string $thoiGianLapBan, int $tongSoSach, float $tongTien, int $tinhTrang)
    {
        // Chuẩn bị câu truy vấn SQL để chèn thông tin vào bảng 'donbans'
        $query = "INSERT INTO `donbans` (`ID_TAIKHOAN`, `THOIGIANLAPBAN`, `TONGSOSACH`, `TONGTIEN`, `TINHTRANG`) 
              VALUES (:id_taikhoan, :thoigianlapban, :tongso_sach, :tongtien, :tinhtrang)";

        $stmt = $this->database->prepare($query);

        // Bind các giá trị tham số với PDO
        $stmt->bindParam(':id_taikhoan', $idTaiKhoan, PDO::PARAM_INT);
        $stmt->bindParam(':thoigianlapban', $thoiGianLapBan);
        $stmt->bindParam(':tongso_sach', $tongSoSach, PDO::PARAM_INT);
        $stmt->bindParam(':tongtien', $tongTien);
        $stmt->bindParam(':tinhtrang', $tinhTrang, PDO::PARAM_INT);

        // Thực thi truy vấn và kiểm tra kết quả
        if ($stmt->execute()) {
            // Nếu thành công, trả về ID của đơn bán vừa tạo
            return $this->database->lastInsertId();  // Trả về ID của đơn bán mới
        } else {
            // Nếu có lỗi, ghi lại thông tin lỗi
            error_log("Lỗi khi tạo đơn bán: " . implode(", ", $stmt->errorInfo()));
            return false;
        }
    }

    public function getAllDonBan($limit = 10, $offset = 0)
    {
        $query = "SELECT * FROM `donbans` LIMIT :limit OFFSET :offset";
        $stmt = $this->database->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getAllDonBanLoc()
    {
        $query = "SELECT * FROM `donbans`";
        $stmt = $this->database->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countAllDonBan(): int
    {
        // Chuẩn bị câu truy vấn
        $stmt = $this->database->query("SELECT COUNT(*) as total FROM `donbans`");

        // Trả về số lượng nhân viên (sử dụng fetchColumn() để lấy giá trị từ cột đầu tiên)
        return (int) $stmt->fetchColumn();
    }

    public function updateDonBan(int $idDonBan, string $idTaiKhoan, string $thoiGianLapBan, int $tongSoSach, float $tongTien)
    {
        try {
            $query = "UPDATE `donbans` SET 
                  `ID_TAIKHOAN` = :id_taikhoan, 
                  `THOIGIANLAPBAN` = :thoigianlapban, 
                  `TONGSOSACH` = :tongso_sach, 
                  `TONGTIEN` = :tongtien                 
                  WHERE `ID_DONBAN` = :id_donban";
            $stmt = $this->database->prepare($query);

            // Bind các tham số
            $stmt->bindParam(':id_donban', $idDonBan, PDO::PARAM_INT);
            $stmt->bindParam(':id_taikhoan', $idTaiKhoan, PDO::PARAM_STR); // Chỉnh lại kiểu là PARAM_STR
            $stmt->bindParam(':thoigianlapban', $thoiGianLapBan);
            $stmt->bindParam(':tongso_sach', $tongSoSach, PDO::PARAM_INT);
            $stmt->bindParam(':tongtien', $tongTien, PDO::PARAM_STR); // Xử lý float là string để an toàn          

            // Thực thi câu lệnh
            return $stmt->execute();
        } catch (PDOException $e) {
            // Log lỗi hoặc xử lý ngoại lệ tùy nhu cầu
            error_log("Lỗi khi cập nhật đơn bán: " . $e->getMessage());
            return false;
        }
    }

    public function getDonBanById($id)
    {
        try {
            $query = "SELECT * FROM donbans WHERE ID_DONBAN = :id";
            $stmt = $this->database->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Lỗi truy vấn: " . $e->getMessage());
        }
    }
    // Xóa đơn bán
    public function deleteDonBanById($id_donban)
    {
        // Xóa đơn bán
        $stmt = $this->database->prepare("DELETE FROM donbans WHERE ID_DONBAN = :id_donban");
        $stmt->bindParam(':id_donban', $id_donban, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function updateDonBanStatus($id, $status)
    {
        // Cập nhật trạng thái hóa đơn
        $sql = "UPDATE donbans SET TINHTRANG = :status WHERE ID_DONBAN = :id_donban";

        // Chuẩn bị câu lệnh SQL
        $stmt = $this->database->prepare($sql);

        // Bind các tham số với giá trị thực tế
        $stmt->bindParam(':status', $status, PDO::PARAM_INT);  // Bind tham số trạng thái
        $stmt->bindParam(':id_donban', $id, PDO::PARAM_INT);   // Bind tham số ID đơn bán

        // Thực hiện câu lệnh và trả về kết quả
        return $stmt->execute();
    }

    public function filterDonBan(string $keyword)
{
    // Chuẩn bị câu truy vấn kết hợp bảng `taikhoans`
    $query = "SELECT donbans.*, taikhoans.USERNAME FROM donbans 
              JOIN taikhoans ON donbans.ID_TAIKHOAN = taikhoans.ID_TAIKHOAN
              WHERE donbans.ID_DONBAN LIKE :keyword OR taikhoans.USERNAME LIKE :keyword";

    // Chuẩn bị câu truy vấn
    $stmt = $this->database->prepare($query);

    // Liên kết giá trị $keyword với placeholder
    $keywordParam = "%" . $keyword . "%"; // Thêm dấu % để tìm kiếm theo phần từ trong chuỗi
    $stmt->bindParam(':keyword', $keywordParam, PDO::PARAM_STR);

    // Thực thi câu truy vấn
    $stmt->execute();

    // Trả về kết quả
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

    public function getChiTietDonBanById($id_donban)
    {
        // Truy vấn SQL JOIN để lấy thông tin chi tiết đơn bán cùng với thông tin sách
        $query = "
            SELECT 
                chitietdonban.*, 
                sachs.TENSACH, 
                sachs.GIASACH
            FROM 
                chitietdonban
            JOIN 
                sachs 
            ON 
                chitietdonban.ID_SACH = sachs.ID_SACH
            WHERE 
                chitietdonban.ID_DONBAN = :id_donban
        ";
        $stmt = $this->database->prepare($query);

        // Gắn giá trị tham số :id_donban vào câu truy vấn
        $stmt->bindParam(':id_donban', $id_donban, PDO::PARAM_INT);

        // Thực thi câu truy vấn
        $stmt->execute();

        // Trả về kết quả dưới dạng mảng liên kết
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function createChiTietDonBan(int $idDonBan, int $idSach, int $soLuong, float $thanhTien)
    {
        // Chuẩn bị câu truy vấn SQL để chèn chi tiết đơn bán vào bảng 'chitietdonbans'
        $query = "INSERT INTO `chitietdonban` (`ID_DONBAN`, `ID_SACH`, `SOLUONG`, `THANHTIEN`) 
              VALUES (:id_donban, :id_sach, :soluong, :thanhtien)";

        $stmt = $this->database->prepare($query);

        // Bind các giá trị tham số với PDO
        $stmt->bindParam(':id_donban', $idDonBan, PDO::PARAM_INT);
        $stmt->bindParam(':id_sach', $idSach, PDO::PARAM_INT);
        $stmt->bindParam(':soluong', $soLuong, PDO::PARAM_INT);
        $stmt->bindParam(':thanhtien', $thanhTien);

        // Thực thi truy vấn và kiểm tra kết quả
        if ($stmt->execute()) {
            return true;
        } else {
            // Nếu có lỗi, ghi lại thông tin lỗi
            error_log("Lỗi khi thêm chi tiết đơn bán: " . implode(", ", $stmt->errorInfo()));
            return false;
        }
    }
    public function insertChiTietDonBan($idDonBan, $idSach, $soLuong, $gia)
    {
        $sql = "INSERT INTO chitietdonban (ID_DONBAN, ID_SACH, SOLUONG, THANHTIEN) VALUES (?, ?, ?, ?)";
        $stmt = $this->database->prepare($sql);
        return $stmt->execute([$idDonBan, $idSach, $soLuong, $gia]);
    }
    public function deleteChiTietDonBan($idDonBan)
    {
        $sql = "DELETE FROM chitietdonban WHERE ID_DONBAN = ?";
        $stmt = $this->database->prepare($sql);
        return $stmt->execute([$idDonBan]);
    }
    // Kiểm tra xem đơn bán có chi tiết nào không
    public function checkChiTietDonBan($id_donban)
    {
        // Truy vấn để kiểm tra có chi tiết nào kết nối với đơn bán không
        $stmt = $this->database->prepare("SELECT COUNT(*) FROM chitietdonban WHERE ID_DONBAN = :id_donban");
        $stmt->bindParam(':id_donban', $id_donban, PDO::PARAM_INT);
        $stmt->execute();

        // Lấy số lượng chi tiết đơn bán
        $result = $stmt->fetchColumn();

        return $result > 0; // Nếu có chi tiết, trả về true, ngược lại false
    }
    // Xóa chi tiết đơn bán theo ID đơn bán
    public function deleteChiTietDonBanByDonBanId($id_donban)
    {
        // Xóa tất cả chi tiết đơn bán kết nối với đơn bán này
        $stmt = $this->database->prepare("DELETE FROM chitietdonban WHERE ID_DONBAN = :id_donban");
        $stmt->bindParam(':id_donban', $id_donban, PDO::PARAM_INT);
        return $stmt->execute();
    }
    public function getAllTaiKhoan()
    {
        // Truy vấn SQL kết nối bảng taikhoans và nguoidungs
        $query = "
        SELECT taikhoans.ID_TAIKHOAN, taikhoans.USERNAME, nguoidungs.NAME 
        FROM `taikhoans` 
        JOIN `nguoidungs` ON taikhoans.ID_NGUOIDUNG = nguoidungs.ID_NGUOIDUNG
    ";

        // Chuẩn bị và thực thi câu truy vấn
        $stmt = $this->database->prepare($query);
        $stmt->execute();

        // Trả về dữ liệu dưới dạng mảng kết hợp
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getTaiKhoanById($id)
    {
        try {
            $query = "
            SELECT 
                taikhoans.ID_TAIKHOAN, 
                taikhoans.USERNAME, 
                taikhoans.PASSWORD,       
                nguoidungs.NAME AS nguoidung_name, 
                nguoidungs.EMAIL AS nguoidung_email, 
                nguoidungs.SDT AS nguoidung_phone
            FROM 
                taikhoans
            INNER JOIN 
                nguoidungs 
            ON 
                taikhoans.ID_NGUOIDUNG = nguoidungs.ID_NGUOIDUNG
            WHERE 
                taikhoans.ID_TAIKHOAN = :id
            LIMIT 1
        ";
            $stmt = $this->database->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            // Lấy một dòng dữ liệu duy nhất
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Lỗi truy vấn: " . $e->getMessage());
        }
    }
    public function getAllBooks()
    {
        try {
            // Chuẩn bị câu truy vấn SQL để lấy danh sách sách
            $query = "SELECT ID_SACH ,TENSACH, GIASACH, TACGIA FROM sachs";
            $stmt = $this->database->prepare($query);

            // Thực thi câu truy vấn
            $stmt->execute();

            // Lấy tất cả dữ liệu và trả về dưới dạng mảng kết hợp
            $books = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Trả về dữ liệu dưới dạng JSON
            return $books;
        } catch (PDOException $e) {
            // Xử lý lỗi nếu có
            echo "Lỗi khi truy vấn: " . $e->getMessage();
            return [];
        }
    }
     // Lấy thông tin đơn bán theo ID
     public function getTTDonBanById($id) {
        $sql = "SELECT d.*, t.USERNAME, n.NAME 
                FROM donbans d
                JOIN taikhoans t ON d.ID_TAIKHOAN = t.ID_TAIKHOAN
                JOIN nguoidungs n ON t.ID_NGUOIDUNG = n.ID_NGUOIDUNG
                WHERE d.ID_DONBAN = ?";
        $stmt = $this->database->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Lấy danh sách sách trong đơn hàng
    public function getChiTietDonBan($id) {
        $sql = "SELECT s.TENSACH, s.TACGIA, c.SOLUONG, c.THANHTIEN
                FROM chitietdonban c
                JOIN sachs s ON c.ID_SACH = s.ID_SACH
                WHERE c.ID_DONBAN = ?";
        $stmt = $this->database->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}