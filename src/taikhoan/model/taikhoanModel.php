<?php

namespace Taikhoan;

use Engine\Base;
use PDO;
use PDOException;
use Exception;

class TaikhoanModel extends Base
{
    private ?PDO $database = null;

    public function __construct()
    {
        $this->connectDatabase();
    }

    private function connectDatabase(): void
    {
        try {
            $this->database = new PDO('mysql:host=localhost;dbname=qlsach;charset=utf8', 'root', '', [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        } catch (PDOException $e) {
            throw new Exception("Database connection failed: " . $e->getMessage());
        }
    }

    public function closeConnection(): void
    {
        $this->database = null;
    }

    private function checkDatabase(): void
    {
        if ($this->database === null) {
            throw new Exception("Database connection is not established.");
        }
    }

    public function getAllTaikhoans(): array
    {
        $this->checkDatabase();
        $stmt = $this->database->query("SELECT * FROM taikhoans");
        return $stmt->fetchAll();
    }

    public function createTaikhoan(int $id_quyen, int $id_nguoidung, string $username, string $password, string $ngaytao, bool $trangthai): bool
    {
        $this->checkDatabase();
        $query = "INSERT INTO taikhoans (ID_QUYEN, ID_NGUOIDUNG, USERNAME, PASSWORD, NGAYTAO, TRANGTHAI) 
                  VALUES (:id_quyen, :id_nguoidung, :username, :password, :ngaytao, :trangthai)";
        $stmt = $this->database->prepare($query);

        return $stmt->execute([
            ':id_quyen' => $id_quyen,
            ':id_nguoidung' => $id_nguoidung,
            ':username' => $username,
            ':password' => password_hash($password, PASSWORD_BCRYPT), // Hash password
            ':ngaytao' => $ngaytao,
            ':trangthai' => (int) $trangthai,
        ]);
    }

    public function updateTaikhoan(int $id_taikhoan, int $id_quyen, int $id_nguoidung, string $username, string $password, bool $trangthai): bool
    {
        $this->checkDatabase();
        $query = "UPDATE taikhoans SET 
                  ID_QUYEN = :id_quyen, ID_NGUOIDUNG = :id_nguoidung, USERNAME = :username, 
                  PASSWORD = :password, TRANGTHAI = :trangthai
                  WHERE ID_TAIKHOAN = :id_taikhoan";
        $stmt = $this->database->prepare($query);

        return $stmt->execute([
            ':id_taikhoan' => $id_taikhoan,
            ':id_quyen' => $id_quyen,
            ':id_nguoidung' => $id_nguoidung,
            ':username' => $username,
            ':password' => password_hash($password, PASSWORD_BCRYPT),
            ':trangthai' => (int) $trangthai,
        ]);
    }

    public function deleteTaikhoanById(int $id_taikhoan): bool
    {
        $this->checkDatabase();
        $stmt = $this->database->prepare("DELETE FROM taikhoans WHERE ID_TAIKHOAN = :id_taikhoan");
        return $stmt->execute([':id_taikhoan' => $id_taikhoan]);
    }

    public function findTaikhoanById(int $id_taikhoan): ?array
    {
        $this->checkDatabase();
        $stmt = $this->database->prepare("SELECT * FROM taikhoans WHERE ID_TAIKHOAN = :id_taikhoan");
        $stmt->execute([':id_taikhoan' => $id_taikhoan]);
        return $stmt->fetch() ?: null;
    }

    public function findTaikhoanByUsername(string $username): array
    {
        $this->checkDatabase();
        $stmt = $this->database->prepare("SELECT * FROM taikhoans WHERE USERNAME LIKE :username");
        $stmt->execute([':username' => "%$username%"]);
        return $stmt->fetchAll();
    }

    public function getAllTaiKhoansWithDetails(): array
    {
        $this->checkDatabase();
        $sql = "SELECT t.*, n.NAME as nguoidung_name, n.EMAIL as nguoidung_email, q.TENQUYEN as quyenhan_name
                FROM taikhoans t
                LEFT JOIN nguoidungs n ON t.ID_NGUOIDUNG = n.ID_NGUOIDUNG
                LEFT JOIN quyenhans q ON t.ID_QUYEN = q.ID_QUYEN";

        $stmt = $this->database->query($sql);
        return $stmt->fetchAll();
    }

    public function getAllNguoidungs(): array
    {
        $this->checkDatabase();
        $stmt = $this->database->query("SELECT * FROM nguoidungs");
        return $stmt->fetchAll();
    }

    public function getAllQuyenhans(): array
    {
        $this->checkDatabase();
        $stmt = $this->database->query("SELECT * FROM quyenhans");
        return $stmt->fetchAll();
    }

    public function updateNguoidung(int $id_nguoidung, string $name, string $hinhanhnd, string $email, string $sdt, string $diachi): bool
    {
        $this->checkDatabase();
        $query = "UPDATE nguoidungs SET 
                  NAME = :name, HINHANHND = :hinhanhnd, EMAIL = :email, SDT = :sdt, DIACHI = :diachi
                  WHERE ID_NGUOIDUNG = :id_nguoidung";
        $stmt = $this->database->prepare($query);

        return $stmt->execute([
            ':id_nguoidung' => $id_nguoidung,
            ':name' => $name,
            ':hinhanhnd' => $hinhanhnd,
            ':email' => $email,
            ':sdt' => $sdt,
            ':diachi' => $diachi,
        ]);
    }
}
