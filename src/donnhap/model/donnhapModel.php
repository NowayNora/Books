<?php

namespace Donnhap;

use Engine\Base;
use PDO;
use PDOException;

class DonnhapModel extends Base
{
    private $database;
    public function __construct()
    {
        try {
            $this->database = new PDO(
                // "mysql:host=127.0.0.1;port=3308;dbname=qlsach;charset=utf8mb4",//máy tôi sài cái này
                "mysql:host=localhost;dbname=qlsach;charset=utf8mb4",
                "root",
                ""
            );
            $this->database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Kết nối cơ sở dữ liệu thất bại: " . $e->getMessage());
        }
    }

    public function createDonNhap(string $idTaiKhoan, string $thoiGianLap, int $tongSoSach, float $tongTien, int $tinhTrang, string $noiNhap)
    {
        $query = "INSERT INTO `donnhaps` (`ID_TAIKHOAN`, `THOIGIANLAP`, `TONGSOSACH`, `TONGTIEN`, `TINHTRANG`, `NOINHAP`) 
              VALUES (:id_taikhoan, :thoigianlap, :tongso_sach, :tongtien, :tinhtrang, :noinhap)";

        $stmt = $this->database->prepare($query);

        $stmt->bindParam(':id_taikhoan', $idTaiKhoan, PDO::PARAM_INT);
        $stmt->bindParam(':thoigianlap', $thoiGianLap);
        $stmt->bindParam(':tongso_sach', $tongSoSach, PDO::PARAM_INT);
        $stmt->bindParam(':tongtien', $tongTien);
        $stmt->bindParam(':tinhtrang', $tinhTrang, PDO::PARAM_INT);
        $stmt->bindParam(':noinhap', $noiNhap);


        if ($stmt->execute()) {
            return $this->database->lastInsertId();
        } else {
            error_log("Lỗi khi tạo đơn nhập: " . implode(", ", $stmt->errorInfo()));
            return false;
        }
    }

    public function getAllDonNhap($limit = 10, $offset = 0)
    {
        $query = "SELECT * FROM `donnhaps` LIMIT :limit OFFSET :offset";
        $stmt = $this->database->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function countAllDonNhap(): int
    {
        $stmt = $this->database->query("SELECT COUNT(*) as total FROM `donnhaps`");

        return (int) $stmt->fetchColumn();
    }

    public function updateDonNhap(int $idDonNhap, string $idTaiKhoan, string $thoiGianLap, string $noiNhap, int $tongSoSach, float $tongTien)
    {
        try {
            $query = "UPDATE `donnhaps` SET 
                  `ID_TAIKHOAN` = :id_taikhoan, 
                  `THOIGIANLAP` = :thoigianlap,
                  `NOINHAP` = :noinhap, 
                  `TONGSOSACH` = :tongso_sach, 
                  `TONGTIEN` = :tongtien                
                  WHERE `ID_DONNHAP` = :id_donnhap";
            $stmt = $this->database->prepare($query);

            // Bind các tham số
            $stmt->bindParam(':id_donnhap', $idDonNhap, PDO::PARAM_INT);
            $stmt->bindParam(':id_taikhoan', $idTaiKhoan, PDO::PARAM_STR);
            $stmt->bindParam(':thoigianlap', $thoiGianLap);
            $stmt->bindParam(':noinhap', $noiNhap, PDO::PARAM_STR);
            $stmt->bindParam(':tongso_sach', $tongSoSach, PDO::PARAM_INT);
            $stmt->bindParam(':tongtien', $tongTien, PDO::PARAM_STR);


            return $stmt->execute();
        } catch (PDOException $e) {

            error_log("Lỗi khi cập nhật đơn nhập: " . $e->getMessage());
            return false;
        }
    }

    public function getDonNhapById($id)
    {
        try {
            $query = "SELECT * FROM donnhaps WHERE ID_DONNHAP = :id";
            $stmt = $this->database->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Lỗi truy vấn: " . $e->getMessage());
        }
    }

    public function deleteDonNhapById($id_donnhap)
    {
        $stmt = $this->database->prepare("DELETE FROM donnhaps WHERE ID_DONNHAP = :id_donnhap");
        $stmt->bindParam(':id_donnhap', $id_donnhap, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function updateDonNhapStatus($id, $status)
    {

        $sql = "UPDATE donnhaps SET TINHTRANG = :status WHERE ID_DONNHAP = :id_donnhap";

        $stmt = $this->database->prepare($sql);

        $stmt->bindParam(':status', $status, PDO::PARAM_INT);
        $stmt->bindParam(':id_donnhap', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function filterDonNhap(string $keyword)
    {
        $query = "SELECT donnhaps.* FROM donnhaps 
              JOIN taikhoans ON donnhaps.ID_TAIKHOAN = taikhoans.ID_TAIKHOAN
              WHERE donnhaps.ID_DONNHAP LIKE :keyword OR taikhoans.USERNAME LIKE :keyword";

        $stmt = $this->database->prepare($query);

        $keywordParam = "%$keyword%";
        $stmt->bindParam(':keyword', $keywordParam, PDO::PARAM_STR);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getChiTietDonNhapById($id_donnhap)
    {
        $query = "
            SELECT 
                chitietdonnhap.*, 
                sachs.TENSACH, 
                sachs.GIASACH
            FROM 
                chitietdonnhap
            JOIN 
                sachs 
            ON 
                chitietdonnhap.ID_SACH = sachs.ID_SACH
            WHERE 
                chitietdonnhap.ID_DONNHAP = :id_donnhap
        ";
        $stmt = $this->database->prepare($query);

        $stmt->bindParam(':id_donnhap', $id_donnhap, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function createChiTietDonNhap(int $idDonNhap, int $idSach, int $soLuong, float $thanhTien)
    {

        $query = "INSERT INTO `chitietdonnhap` (`ID_DONNHAP`, `ID_SACH`, `SOLUONG`, `THANHTIEN`) 
              VALUES (:id_donnhap, :id_sach, :soluong, :thanhtien)";

        $stmt = $this->database->prepare($query);

        $stmt->bindParam(':id_donnhap', $idDonNhap, PDO::PARAM_INT);
        $stmt->bindParam(':id_sach', $idSach, PDO::PARAM_INT);
        $stmt->bindParam(':soluong', $soLuong, PDO::PARAM_INT);
        $stmt->bindParam(':thanhtien', $thanhTien);

        if ($stmt->execute()) {
            return true;
        } else {
            error_log("Lỗi khi thêm chi tiết đơn nhập: " . implode(", ", $stmt->errorInfo()));
            return false;
        }
    }
    public function insertChiTietDonNhap($idDonNhap, $idSach, $soLuong, $gia)
    {
        $sql = "INSERT INTO chitietdonnhap (ID_DONNHAP, ID_SACH, SOLUONG, THANHTIEN) VALUES (?, ?, ?, ?)";
        $stmt = $this->database->prepare($sql);
        return $stmt->execute([$idDonNhap, $idSach, $soLuong, $gia]);
    }
    public function deleteChiTietDonNhap($idDonNhap)
    {
        $sql = "DELETE FROM chitietdonnhap WHERE ID_DONNHAP = ?";
        $stmt = $this->database->prepare($sql);
        return $stmt->execute([$idDonNhap]);
    }

    public function checkChiTietDonNhap($id_donnhap)
    {
        $stmt = $this->database->prepare("SELECT COUNT(*) FROM chitietdonnhap WHERE ID_DONNHAP = :id_donnhap");
        $stmt->bindParam(':id_donnhap', $id_donnhap, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetchColumn();

        return $result > 0;
    }

    public function deleteChiTietDonNhapById($id_donnhap)
    {
        $stmt = $this->database->prepare("DELETE FROM chitietdonnhap WHERE ID_DONNHAP = :id_donnhap");
        $stmt->bindParam(':id_donnhap', $id_donnhap, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function getAllTaiKhoan()
    {
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
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Lỗi truy vấn: " . $e->getMessage());
        }
    }

    public function getAllBooks()
    {
        try {
            $query = "SELECT ID_SACH ,TENSACH, GIASACH FROM sachs";
            $stmt = $this->database->prepare($query);
            $stmt->execute();
            $books = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $books;
        } catch (PDOException $e) {
            echo "Lỗi khi truy vấn: " . $e->getMessage();
            return [];
        }
    }
}
