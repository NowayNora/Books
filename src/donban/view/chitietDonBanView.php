<div class="container mt-1">
    <div id="invoice-print" class="invoice p-4 rounded shadow bg-light">
        <!-- Tiêu đề -->
        <div class="text-center mb-4">
            <h2 class="text-uppercase fw-bold text-danger">Hóa đơn bán sách</h2>
        </div>
        <!-- Thông tin chung -->
        <div class="row mb-4">
            <div class="col-md-6">
                <p><strong>HĐ:</strong> <?= htmlspecialchars($donban['ID_DONBAN']) ?></p>
                <p><strong>Ngày:</strong> <?= date('d-m-Y', strtotime($donban['THOIGIANLAPBAN'])) ?></p>
            </div>
            <div class="col-md-6 text-end">
                <p><strong>Khách hàng:</strong> <?= htmlspecialchars($donban['USERNAME']) ?>
                    (<?= htmlspecialchars($donban['NAME']) ?>)</p>
            </div>
        </div>
        <!-- Bảng chi tiết đơn hàng -->
        <div class="table-responsive mb-4">
            <table class="table table-bordered table-striped table-hover">
                <thead class="table-primary">
                    <tr>
                        <th>Tên sách</th>
                        <th>Tác giả</th>
                        <th class="text-center">SL</th>
                        <th class="text-end">Thành tiền</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($chitiet_donban as $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['TENSACH']) ?></td>
                        <td><?= htmlspecialchars($item['TACGIA']) ?></td>
                        <td class="text-center"><?= htmlspecialchars($item['SOLUONG']) ?></td>
                        <td class="text-end"><?= number_format($item['THANHTIEN'], 0, ',', '.') ?> đ</td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot class="table-light">
                    <tr>
                        <td colspan="3" class="text-end fw-bold">Tổng cộng</td>
                        <td class="text-end text-danger fw-bold"><?= number_format($donban['TONGTIEN'], 0, ',', '.') ?>
                            đ</td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <!-- Nút In hóa đơn ở góc phải dưới cùng -->
        <div class="text-end">
            <button class="btn btn-danger btn-sm fw-bold px-4 py-2" onclick="printInvoice()">
                <i class="bi bi-printer"></i> In đơn bán
            </button>
        </div>
    </div>
</div>
<style>
@media print {
    body {
        visibility: hidden;
        background: white;
        font-size: 14pt;
    }

    #invoice-print,
    #invoice-print * {
        visibility: visible;
    }

    #invoice-print {
        position: absolute;
        left: 50%;
        top: 10%;
        transform: translateX(-50%);
        width: 90%;
        max-width: 800px;
        margin: auto;
        padding: 20px;
        border: 1px solid #333;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
        background: white;
        font-size: 12pt;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
    }

    .table th,
    .table td {
        border: 1px solid #333 !important;
        padding: 8px;
    }

    .table-primary {
        background-color: #007bff !important;
        color: white;
    }

    .table-light {
        background-color: #f8f9fa !important;
    }

    .text-center {
        text-align: center !important;
    }

    .text-end {
        text-align: right !important;
    }

    .fw-bold {
        font-weight: bold !important;
    }

    .text-danger {
        color: #dc3545 !important;
    }

    .btn {
        display: none;
        /* Ẩn nút khi in */
    }
}
</style>