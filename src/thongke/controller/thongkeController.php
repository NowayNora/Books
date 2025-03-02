<?php

namespace Thongke;

use Engine\Base;
use Thongke\ThongKeModel;

class ThongKeController extends Base
{
    public function index(): void
    {
        $this->HandleThongKe(); // Chuyển mọi yêu cầu đến HandleThongKe
    }

    public function HandleThongKe()
    {
        $tungay = $_POST['tungay'] ?? null;
        $denngay = $_POST['denngay'] ?? null;
        $type = $_POST['type'] ?? '';

        error_log("🔍 POST Data: tungay=$tungay, denngay=$denngay, type=$type");

        if ($tungay) {
            $tungay = date('Y-m-d 00:00:00', strtotime($tungay));
        }
        if ($denngay) {
            $denngay = date('Y-m-d 23:59:59', strtotime($denngay));
        }

        error_log("🔍 Ngày sau định dạng: tungay=$tungay, denngay=$denngay");

        $model = new ThongKeModel();
        $data = [];

        if ($type == 'tonkho') {
            $soluongnhap = $model->getSoLuongNhap();
            $soluongban = $model->getSoLuongBan();
            $tonkho = $model->getTonKho();
            error_log("🔍 Tồn kho - Nhập: " . json_encode($soluongnhap));
            error_log("🔍 Tồn kho - Bán: " . json_encode($soluongban));
            error_log("🔍 Tồn kho - Tồn: " . json_encode($tonkho));

            $result = [];
            foreach ($soluongnhap as $nhap) {
                $idSach = $nhap['ID_SACH'];
                $result[$idSach] = [
                    'ID_SACH' => $idSach,
                    'TENSACH' => $nhap['TENSACH'],
                    'SOLUONG_NHAP' => $nhap['SOLUONG_NHAP'] ?? 0,
                    'SOLUONG_BAN' => 0,
                    'SOLUONGTON' => 0
                ];
            }
            foreach ($soluongban as $ban) {
                $idSach = $ban['ID_SACH'];
                if (!isset($result[$idSach])) {
                    $result[$idSach] = [
                        'ID_SACH' => $idSach,
                        'TENSACH' => $ban['TENSACH'],
                        'SOLUONG_NHAP' => 0,
                        'SOLUONG_BAN' => $ban['SOLUONG_BAN'] ?? 0,
                        'SOLUONGTON' => 0
                    ];
                } else {
                    $result[$idSach]['SOLUONG_BAN'] = $ban['SOLUONG_BAN'] ?? 0;
                }
            }
            foreach ($tonkho as $ton) {
                $idSach = $ton['ID_SACH'];
                if (!isset($result[$idSach])) {
                    $result[$idSach] = [
                        'ID_SACH' => $idSach,
                        'TENSACH' => $ton['TENSACH'],
                        'SOLUONG_NHAP' => 0,
                        'SOLUONG_BAN' => 0,
                        'SOLUONGTON' => $ton['SOLUONGTON'] ?? 0
                    ];
                } else {
                    $result[$idSach]['SOLUONGTON'] = $ton['SOLUONGTON'] ?? 0;
                }
            }
            $data['tonkho'] = array_values($result);
        } elseif ($type == 'doanhthu') {
            $doanhthuChart = $model->getDoanhThuTheoNgay($tungay, $denngay);
            $doanhthuChiTiet = $model->getThongKeChiTiet($tungay, $denngay);
            error_log("🔍 Doanh thu chart: " . json_encode($doanhthuChart));
            error_log("🔍 Doanh thu chi tiết: " . json_encode($doanhthuChiTiet));
            $data['doanhthu_chart'] = $doanhthuChart;
            $data['doanhthu'] = $doanhthuChiTiet;
        }

        error_log("🔍 Render view HTML");
        $this->output->load("thongke/thongke", $data); // Luôn render HTML
    }
}
