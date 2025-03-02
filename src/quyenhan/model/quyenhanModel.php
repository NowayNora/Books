<?php

namespace Quyenhan;

use Engine\Base;

class QuyenhanModel extends Base
{
    public function getAllQuyenhan(): array
    {
        return $this->database->query("SELECT * FROM `quyenhans`");
    }

    public function createQuyenhan(string $tenquyen, string $motaquyen)
    {
        $query = "INSERT INTO `quyenhans`(`TENQUYEN`, `MOTAQUYEN`) 
        VALUES('$tenquyen', '$motaquyen')";
        return $this->database->query($query);
    }

    /**
     * Cập nhật quyền hạn dựa trên ID
     * @param int $id
     * @param string $tenQuyen
     * @param string $moTaQuyen
     * @return bool
     */
    public function updateQuyenhan(int $id_quyen, string $tenquyen, string $motaquyen)
    {
        // Chỉnh sửa câu SQL để loại bỏ dấu phẩy thừa
        $query = "UPDATE quyenhans SET
        tenquyen = '$tenquyen',
        motaquyen = '$motaquyen'
        WHERE id_quyen = $id_quyen;";

        // Thực hiện câu truy vấn
        return $this->database->query($query);
    }

    // public function updateByid(int $id_quyen, string $tenquyen, string $motaquyen)
    // {
    //     $query = "UPDATE quyenhans SET
    //         tenquyen = '$tenquyen',
    //         motaquyen = '$motaquyen'
    //         " . "WHERE id = $id_quyen;";
    //     return $this->database->query($query);
    // }

    /**
     * Xóa quyền hạn dựa trên ID
     * @param int $id
     * @return bool
     */
    // public function deleteQuyenhan(int $id_quyen): bool
    // {
    //     $sql = "DELETE FROM `quyenhans` WHERE `ID_QUYEN` = :id_quyen";
    //     $stmt = $this->database->prepare($sql);
    //     $stmt->bindValue(":id_quyen", $id_quyen);
    //     return $stmt->execute();
    // }

    public function deletequyenhanById(int $id_quyen)
    {
        $query = "DELETE FROM `quyenhans` WHERE id_quyen = $id_quyen";
        return $this->database->query($query);
    }

    /**
     * Tìm kiếm quyền hạn theo tên
     * @param string $tenQuyen
     * @return array
     */
    public function findByName(string $tenQuyen): array
    {
        $sql = "SELECT * FROM `QUYENHANS` WHERE `TENQUYEN` LIKE :tenQuyen";
        $stmt = $this->database->prepare($sql);
        $stmt->bindValue(":tenQuyen", "%$tenQuyen%");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function findquyenhanById(int $id_quyen)
    {
     
          return $this->database->query("SELECT * FROM `quyenhans` WHERE id_quyen = $id_quyen");
    }
}
