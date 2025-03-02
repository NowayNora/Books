<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>404 - Không tìm thấy trang</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            text-align: center;
        }

        .error-container {
            max-width: 600px;
            background: #fff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .error-code {
            font-size: 100px;
            font-weight: bold;
            color: #d9534f;
        }

        .error-message {
            font-size: 22px;
            font-weight: 500;
            margin-top: 10px;
        }

        .error-icon {
            font-size: 80px;
            color: #5bc0de;
        }
    </style>
</head>

<body>
    <div class="error-container">
        <i class="fas fa-book-open error-icon"></i>
        <div class="error-code">404</div>
        <div class="error-message">Xin lỗi, trang bạn tìm kiếm không tồn tại!</div>
        <p class="text-muted">Có thể trang đã bị di chuyển hoặc bạn đã nhập sai địa chỉ.</p>
        <a href="/index" class="btn btn-primary mt-3"><i class="fas fa-home"></i> Quay về trang chủ</a>
    </div>
</body>

</html>