<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="/www/dist/thongke/thongke.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <div class="bill-body">
        <div class="bill-title-input bill-title-input-dn" style="color:#28a745;">
            CHỌN ĐỂ THỐNG KÊ
        </div>
        <div class="bill-container-update">
            <form method="POST" class="thongke-di" action="">
                <div class="info-bill-input">
                    <div class="info-bill-left">
                        <table>
                            <tr>
                                <td><label for="tungay" style="color:#e74c3c;">Từ ngày</label></td>
                                <td><input type="date" name="tungay" id="tungay"
                                        value="<?= htmlspecialchars($_POST['tungay'] ?? '') ?>" required
                                        style="width: 170px; height: 30px; font-size: 16px;"></td>
                            </tr>
                        </table>
                    </div>
                    <div class="info-bill-mid">
                        <table>
                            <tr>
                                <td><label for="denngay" style="color:#e74c3c;">Đến ngày</label></td>
                                <td><input type="date" name="denngay" id="denngay"
                                        value="<?= htmlspecialchars($_POST['denngay'] ?? '') ?>" required
                                        style="width: 170px; height: 30px; font-size: 16px;"></td>
                            </tr>
                        </table>
                    </div>
                    <div class="info-bill-right">
                        <table>
                            <tr>
                                <td><label for="type" style="color:#e74c3c;">Chọn thống kê</label></td>
                                <td>
                                    <select name="type" id="type" style="width: 170px; height: 35px; font-size: 16px;">
                                        <option value="tonkho"
                                            <?= isset($_POST['type']) && $_POST['type'] == 'tonkho' ? 'selected' : '' ?>>
                                            Tồn kho</option>
                                        <option value="doanhthu"
                                            <?= isset($_POST['type']) && $_POST['type'] == 'doanhthu' ? 'selected' : '' ?>>
                                            Doanh thu</option>
                                    </select>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div style="text-align: center; margin: 20px 0;">
                        <label for="chartType" style="font-size: 16px; color:#e74c3c;">Chọn loại biểu đồ</label>
                        <select id="chartType">
                            <option value="bar">Cột</option>
                            <option value="line">Đường</option>
                            <option value="pie">Tròn</option>
                        </select>
                    </div>
                </div>
                <div class="submit-database" style="text-align: right; margin: 20px 0px;">
                    <button class="thongke-handle-list">Tìm Kiếm</button>
                </div>
            </form>
        </div>
        <div class="thongke-container">
            <?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
                <div class="thongke-buttons" style="text-align: center; margin-top: 20px;">
                    <button id="btnTongQuan" class="toggle-btn">Tổng Quan</button>
                    <button id="btnChiTiet" class="toggle-btn">Chi Tiết</button>
                </div>
                <div class="excel">
                    <button class="excel-btn">Xuất Excel</button>
                </div>
            <?php endif; ?>
        </div>
        <?php
        $hasTonKho = isset($data['tonkho']) && !empty($data['tonkho']);
        $hasDoanhThu = isset($data['doanhthu']) && !empty($data['doanhthu']);
        $hasData = $hasTonKho || $hasDoanhThu;
        ?>
        <?php if ($hasData): ?>
            <div class="thongke-result">
                <div id="tongQuanContent" style="display: block;">
                    <div class="NameChart">Biểu Đồ Thống Kê</div>
                    <div id="chartContainer" style="width: 100%; overflow-x: auto; white-space: nowrap;">
                        <canvas id="chartCanvas"></canvas>
                    </div>
                </div>
                <div id="chiTietContent" style="display: none;">
                    <div class="NameChart">Bảng Thống Kê Chi Tiết</div>
                    <?php if ($hasTonKho): ?>
                        <table class="content-table-bill-big">
                            <thead>
                                <tr>
                                    <th>Tên Sách</th>
                                    <th>Số Lượng Nhập</th>
                                    <th>Số Lượng Bán Ra</th>
                                    <th>Tồn Kho</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($data['tonkho'] as $row): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($row['TENSACH'] ?? 'Không rõ') ?></td>
                                        <td><?= htmlspecialchars($row['SOLUONG_NHAP'] ?? 0) ?></td>
                                        <td><?= htmlspecialchars($row['SOLUONG_BAN'] ?? 0) ?></td>
                                        <td><?= htmlspecialchars($row['SOLUONGTON'] ?? 0) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                    <?php if ($hasDoanhThu): ?>
                        <table class="content-table-bill-big" style="margin-top: 20px;">
                            <thead>
                                <tr>
                                    <th>Doanh Thu</th>
                                    <th>Tổng Đơn Hàng</th>
                                    <th>Tổng Sản Phẩm Bán</th>
                                    <th>Đơn Hàng Cao Nhất</th>
                                    <th>Đơn Hàng Thấp Nhất</th>
                                    <th>Ngày Cao Nhất</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><?= number_format($data['doanhthu']['DOANHTHU'] ?? 0, 0, ',', '.') ?> VNĐ</td>
                                    <td><?= $data['doanhthu']['TONG_DONHANG'] ?? 0 ?></td>
                                    <td><?= $data['doanhthu']['TONG_SANPHAM'] ?? 0 ?></td>
                                    <td><?= number_format($data['doanhthu']['DONHANG_CAO_NHAT'] ?? 0, 0, ',', '.') ?> VNĐ</td>
                                    <td><?= number_format($data['doanhthu']['DONHANG_THAP_NHAT'] ?? 0, 0, ',', '.') ?> VNĐ</td>
                                    <td><?= htmlspecialchars($data['doanhthu']['NGAY_CAO_NHAT'] ?? 'Không có') ?></td>
                                </tr>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </div>
        <?php else: ?>
            <?php if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$hasData): ?>
                <div class="table-new" style="height: 350px; display: flex; justify-content: center; align-items: center;">
                    <h2>Không có dữ liệu để thống kê trong khoảng thời gian này</h2>
                </div>
            <?php endif; ?>
        <?php endif; ?>
        <script id="chartDataScript">
            window.chartData = <?= json_encode($data, JSON_NUMERIC_CHECK | JSON_UNESCAPED_UNICODE) ?>;
        </script>
    </div>
</body>

</html>