<?php

namespace Thongke;

use PDO;
use PDOException;

class ThongKeModel {
    private $database;

    public function __construct() {
        try {
            $this->database = new PDO(
                "mysql:host=localhost;dbname=qlsach;charset=utf8mb4",
                "root",
                ""
            );
            $this->database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Kết nối cơ sở dữ liệu thất bại: " . $e->getMessage());
        }
    }

    // Tính tồn kho: Tổng số lượng sách - số sách đã bán
    public function getTonKho() {
        try {
            $stmt = $this->database->prepare("
                SELECT s.ID_SACH, s.TENSACH, 
                       COALESCE((SELECT SUM(nh.SOLUONG) FROM chitietdonnhap nh WHERE nh.ID_SACH = s.ID_SACH), 0) 
                       - COALESCE((SELECT SUM(ban.SOLUONG) FROM chitietdonban ban WHERE ban.ID_SACH = s.ID_SACH), 0) 
                       AS SOLUONGTON
                FROM sachs s
            ");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Lỗi getTonKho: " . $e->getMessage());
        }
    }
    

    public function getDoanhThu($tungay = null, $denngay = null) {
        try {
            $sql = "SELECT SUM(ctdb.THANHTIEN) AS DOANHTHU 
                    FROM chitietdonban ctdb
                    JOIN donbans db ON ctdb.ID_DONBAN = db.ID_DONBAN";
            $params = [];
    
            if ($tungay && $denngay) {
                $sql .= " WHERE db.THOIGIANLAPBAN BETWEEN :tungay AND :denngay";
                $params = [
                    ':tungay' => $tungay,
                    ':denngay' => $denngay
                ];
            }
    
            $stmt = $this->database->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Lỗi getDoanhThu: " . $e->getMessage());
        }
    }
    
    
    public function getSoLuongNhap() {
        try {
            $stmt = $this->database->prepare("
                SELECT s.ID_SACH, s.TENSACH, 
                       SUM(nh.SOLUONG) AS SOLUONG_NHAP
                FROM sachs s
                LEFT JOIN chitietdonnhap nh ON nh.ID_SACH = s.ID_SACH
                GROUP BY s.ID_SACH, s.TENSACH
            ");
            $stmt->execute();
    
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            return $result;
        } catch (PDOException $e) {
            die("Lỗi getSoLuongNhap: " . $e->getMessage());
        }
    }
    
    public function getSoLuongBan() {
        try {
            $stmt = $this->database->prepare("
                SELECT s.ID_SACH, s.TENSACH, 
                       SUM(ban.SOLUONG) AS SOLUONG_BAN
                FROM sachs s
                LEFT JOIN chitietdonban ban ON ban.ID_SACH = s.ID_SACH
                GROUP BY s.ID_SACH, s.TENSACH
            ");
            $stmt->execute();
    
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
      
            return $result;
        } catch (PDOException $e) {
            die("Lỗi getSoLuongBan: " . $e->getMessage());
        }
    }
    
    
    
    public function getThongKeChiTiet($tungay, $denngay) {
        $sql = "SELECT COALESCE(SUM(ctdb.THANHTIEN), 0) AS DOANHTHU,
                       COUNT(DISTINCT db.ID_DONBAN) AS TONG_DONHANG,
                       COALESCE(SUM(ctdb.SOLUONG), 0) AS TONG_SANPHAM,
                       COALESCE(MAX(db.TONGTIEN), 0) AS DONHANG_CAO_NHAT,
                       COALESCE(MIN(db.TONGTIEN), 0) AS DONHANG_THAP_NHAT,
                       (SELECT DATE(db2.THOIGIANLAPBAN) 
                        FROM donbans db2 
                        JOIN chitietdonban ctdb2 ON db2.ID_DONBAN = ctdb2.ID_DONBAN
                        WHERE (db2.THOIGIANLAPBAN BETWEEN :tungay2 AND :denngay2)
                        GROUP BY DATE(db2.THOIGIANLAPBAN)
                        ORDER BY SUM(ctdb2.THANHTIEN) DESC
                        LIMIT 1) AS NGAY_CAO_NHAT
                FROM chitietdonban ctdb
                JOIN donbans db ON ctdb.ID_DONBAN = db.ID_DONBAN
                WHERE (db.THOIGIANLAPBAN BETWEEN :tungay AND :denngay)";
                try {
                    $stmt = $this->database->prepare($sql);
                    $stmt->bindParam(':tungay', $tungay);
                    $stmt->bindParam(':denngay', $denngay);
                    $stmt->bindParam(':tungay2', $tungay);
                    $stmt->bindParam(':denngay2', $denngay);
                    
                    $stmt->execute();
                    $result = $stmt->fetch(PDO::FETCH_ASSOC);

                    return $result;
                } catch (PDOException $e) {
                    die("Lỗi getThongKeChiTiet: " . $e->getMessage());
                }

    }

    public function getDoanhThuTheoNgay($tungay, $denngay) {
        try {
            $sql = "SELECT DATE(db.THOIGIANLAPBAN) AS NGAY, 
                           SUM(ctdb.THANHTIEN) AS TONGTIEN 
                    FROM chitietdonban ctdb
                    JOIN donbans db ON ctdb.ID_DONBAN = db.ID_DONBAN
                    WHERE db.THOIGIANLAPBAN BETWEEN :tungay AND :denngay
                    GROUP BY DATE(db.THOIGIANLAPBAN)
                    ORDER BY NGAY ASC";
    
            $stmt = $this->database->prepare($sql);
            $stmt->execute([
                ':tungay' => $tungay,
                ':denngay' => $denngay
            ]);
    
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Lỗi getDoanhThuTheoNgay: " . $e->getMessage());
        }
    }
    
    
}

?>
