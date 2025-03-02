<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách tài khoản</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th,
        td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f4f4f4;
        }

        .error {
            color: red;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
    <h1>Danh sách tài khoản</h1>

    <!-- Hiển thị thông báo lỗi (nếu có) -->
    <?php if (!empty($data['error'])): ?>
        <p class="error"><?= htmlspecialchars($data['error']) ?></p>
    <?php endif; ?>

    <!-- Hiển thị bảng danh sách tài khoản -->
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên đăng nhập</th>
                <th>Quyền</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($data['taikhoans'])): ?>
                <?php foreach ($data['taikhoans'] as $account): ?>
                    <tr>
                        <td><?= htmlspecialchars($account['ID_TAIKHOAN']) ?></td>
                        <td><?= htmlspecialchars($account['USERNAME']) ?></td>
                        <td><?= htmlspecialchars($account['ID_QUYEN']) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3">Không có tài khoản nào.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>

</html>