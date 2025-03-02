<?php
namespace Phanloai;
use Engine\Base;

class PhanloaiController extends Base
{
    public function index(): void
    {
        $phanloai_model = new PhanLoaiModel;
        $data = array();
        $data["phanloais"] = $phanloai_model->getphanloai();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $ID_LOAI = $_POST['ID_LOAI'];
            $phanloai_model->delete($ID_LOAI);
            header("Location: /phanloai");
        }
        $this->output->load("phanloai/listPhanloai", $data);
    }

    public function create() {
        $data = array();
        $phanloai_model = new PhanLoaiModel ();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $TENLOAI = $_POST['TENLOAI'];
            $MOTALOAI = $_POST['MOTALOAI'];

            $phanloai_model->create(
                $TENLOAI,
                $MOTALOAI
            );

            header("Location: /phanloai");
    }
    $this->output->load("phanloai/addphanloai", $data); 
    }

    public function update() {
        $data = array();
        $phanloai_model = new PhanLoaiModel ();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $ID_LOAI = $_GET['ID_LOAI'];
            $TENLOAI = $_POST['TENLOAI'];
            $MOTALOAI = $_POST['MOTALOAI'];

            $phanloai_model->update(
                $ID_LOAI,
                $TENLOAI,
                $MOTALOAI
            );

            header("Location: /phanloai");
        } else {
            if ($_GET['ID_LOAI']) {
                $data['phanloais'] = $phanloai_model->findphanloaiid($_GET['ID_LOAI']);
            }
        }
    $this->output->load("phanloai/updatephanloai", $data);
    }

    public function delete() {
        $data = array();
        $phanloai_model = new PhanLoaiModel();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $ID_LOAI = $_GET['ID_LOAI'];

            $phanloai_model->delete(
                $ID_LOAI
            );
            header("Location: /phanloai");
        } else {
            if ($_GET['ID_LOAI']) {
                $data['phanloais'] = $phanloai_model->findphanloaiid($_GET['ID_LOAI']);
            }
        }
        
    }
}
?>