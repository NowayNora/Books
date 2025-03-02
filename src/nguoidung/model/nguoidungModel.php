<?php

namespace Nguoidung;

use Engine\Base;

class nguoidungModel extends Base
{
    /**
     * Lấy danh sách tất cả người dùng
     * @return array
     */
    public function getAllNguoidungs(): array
    {
        return $this->database->query("SELECT * FROM `nguoidungs`");
    }



    /**
     * Tạo người dùng mới
     * @param string $name
     * @param string $email
     * @param string $sdt
     * @param string $diachi
     * @param string $hinhanhnd
     * @return bool
     */

    public function createNguoiDung(string $name, string $email, string $sdt, string $diachi, $hinhanhnd = null): bool
    {
        if ($hinhanhnd === null) {
            // Xử lý trường hợp không có hình ảnh
        }
        $query = "INSERT INTO `nguoidungs` (`NAME`, `EMAIL`, `SDT`, `DIACHI`, `HINHANHND`) 
              VALUES (:name, :email, :sdt, :diachi, :hinhanhnd)";
        $stmt = $this->database->prepare($query);
        return $stmt->execute([
            ':name' => $name,
            ':email' => $email,
            ':sdt' => $sdt,
            ':diachi' => $diachi,
            ':hinhanhnd' => $hinhanhnd,
        ]);
    }

    /**
     * Cập nhật thông tin người dùng
     * @param int $id_nguoidung
     * @param string $name
     * @param string $email
     * @param string $sdt
     * @param string $diachi
     * @param string $hinhanhnd
     * @return bool
     */
    public function updateNguoiDung(int $id_nguoidung, string $name, string $email, string $sdt, string $diachi, $hinhanhnd = null)
    {
        $query = "UPDATE `nguoidungs` SET
              `NAME` = :name,
              `EMAIL` = :email,
              `SDT` = :sdt,
              `DIACHI` = :diachi";

        $params = [
            ':name' => $name,
            ':email' => $email,
            ':sdt' => $sdt,
            ':diachi' => $diachi,
            ':id' => $id_nguoidung
        ];

        // Nếu có ảnh mới, thêm vào câu lệnh SQL
        if ($hinhanhnd !== null) {
            $query .= ", `HINHANHND` = :hinhanhnd";
            $params[':hinhanhnd'] = $hinhanhnd;
        }

        $query .= " WHERE `ID_NGUOIDUNG` = :id";

        $stmt = $this->database->prepare($query);
        return $stmt->execute($params);
    }

    /**
     * Xóa người dùng theo ID
     * @param int $id_nguoidung
     * @return bool
     */
    public function deleteNguoiDungById(int $id_nguoidung)
    {
        $query = "DELETE FROM `nguoidungs` WHERE `ID_NGUOIDUNG` = $id_nguoidung";
        return $this->database->query($query);
    }

    /**
     * Tìm người dùng theo email
     * @param string $email
     * @return array
     */
    public function findNguoiDungByEmail(string $email): array
    {
        $sql = "SELECT * FROM `nguoidungs` WHERE `EMAIL` LIKE :email";
        $stmt = $this->database->prepare($sql);
        $stmt->bindValue(":email", "%$email%");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Tìm người dùng theo ID
     * @param int $id_nguoidung
     * @return array|null
     */
    public function findNguoiDungById(int $id_nguoidung): array
    {
        $result = $this->database->query("SELECT * FROM `nguoidungs` WHERE `ID_NGUOIDUNG` = $id_nguoidung");
        return $result ? $result[0] : null;
    }
}
