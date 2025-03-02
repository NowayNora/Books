<?php
namespace Phanloai;

use Engine\Base;

class PhanloaiModel extends Base
{
    public function create(string $TENLOAI, string $MOTALOAILOAI) {
        $query = "INSERT INTO `loaisachs` (`TENLOAI`, `MOTALOAI`) VALUES ('$TENLOAI', '$MOTALOAILOAI')";
        return $this->database->query($query);
    }

    public function findphanloaiid (int $ID_LOAI)
    {
        return $this->database->query("SELECT * FROM `loaisachs` WHERE ID_LOAI = $ID_LOAI;");
    }

    public function update(int $ID_LOAI, string $TENLOAI, string $MOTALOAI) {
        $query = "UPDATE loaisachs SET TENLOAI = '$TENLOAI', MOTALOAI = '$MOTALOAI' WHERE ID_LOAI = $ID_LOAI;";

        return $this->database->query($query);
    }

    public function delete(int $ID_LOAI)
    {
        $query = "DELETE FROM `loaisachs` WHERE ID_LOAI = $ID_LOAI";
        return $this->database->query($query);
    }

    public function getphanloai(): array
    {
        return $this->database->query("SELECT * FROM `loaisachs`;");
        // return array(
        //     [
        //         "id"=> 1,
        //         "phanloai"=> "Sample book 1",
        //         "mota"=>"This is a book",
        //     ]);
    }
}
?>