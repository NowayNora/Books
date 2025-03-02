<!DOCTYPE html>
<html lang="vi">
<?php
// Lấy dữ liệu tháng
$year = date('Y');
$month = date('m');
$toady = $year . '-' . $month;

// Sử lý dữ liệu
$count_books = 0;
$count_books_in = 0;
$count_books_out = 0;
$cal_books_in = 0;
$cal_books_out = 0;
$result_cal = 0;
$this_month_money = 0;
foreach ($_SESSION['book_count'] as $counting):
    $count_books = $count_books + $counting['SOLUONG'];
endforeach;

foreach ($_SESSION['book_count_in'] as $counting):
    $count_books_in = $count_books_in + 1;
endforeach;

foreach ($_SESSION['book_count_out'] as $counting):
    $count_books_out = $count_books_out + 1;
endforeach;

foreach ($_SESSION['book_cal_in'] as $counting):
    $cal_books_in = $counting['SOLUONG'];
endforeach;

foreach ($_SESSION['book_cal_out'] as $counting):
    $cal_books_out = $counting['SOLUONG'];
endforeach;
$result_cal = $cal_books_in = $cal_books_out;

foreach ($_SESSION['sale_value'] as $counting):
    if (str_contains($counting['THOIGIANLAPBAN'], $toady)) {
        $this_month_money = $this_month_money + $counting['TONGTIEN'];
    }
endforeach;
?>
<!-- Other Function to count the array! -->

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang Chủ</title>
    <link rel="icon" type="image/x-icon" href="/www/dist/img_home/logo.png">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Custom fonts for this template-->
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <link href="/www/dist/home/main.css" rel="stylesheet" type="text/css">

</head>

<body>
    <header>
        <img src="/www/dist/img_home/logo.png" alt="logo">
        <nav>
            <ul>
                <li><a href="#" id="home-link">Trang Chủ</a></li>
                <li><a href="index/book" id="book-list-link">Danh Sách Sách</a></li>
                <li><a href="/taikhoan" id="taikhoan-list">Danh sách tài khoản</a></li>
                <li><a href="/nguoidung" id="nguoidung-list">Danh sách người dùng</a></li>
                <li><a href="/quyenhan" id="quyenhan-list">Quyền hạn</a></li>
                <li><a href="/donban" id="donban-list">Đơn bán</a></li>
                <li><a href="/donnhap" id="donnhap-list">Đơn nhập</a></li>
                <li><a href="/thongke" id="thongke-list">Thống Kê</a></li>

            </ul>
            <div class="auth-buttons">
                <?php if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true): ?>
                    <button onclick="window.location.href='/'">Đăng Xuất</button>
                <?php else: ?>
                    <button onclick="window.location.href='/login'">Đăng Nhập</button>
                <?php endif; ?>
            </div>
        </nav>
    </header>

    <main>
        <!-- Main Content -->
        <div id="content">

            <!-- Begin Page Content -->
            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">Tổng Quan</h1>
                </div>

                <!-- Content Row -->
                <div class="row">

                    <!-- Earnings (Monthly) Card Example -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-primary shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                            Doanh Thu Tháng Này</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            <?php echo number_format((float)$this_month_money, 2, '.', ''); ?> VND</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-calendar fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Earnings (Monthly) Card Example -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-info shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                            Tồn kho</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $result_cal ?>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Earnings (Monthly) Card Example -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-success shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                            Số lượng sách</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $count_books; ?>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pending Requests Card Example -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-warning shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                            Số lượng đơn nhập và đơn bán</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">Nhập
                                            <?php echo $count_books_in ?>, Bán <?php echo $count_books_out ?></div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-comments fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Content Row -->

                <div class="row">

                    <!-- Area Chart -->
                    <div class="col-xl-8 col-lg-7">
                        <div class="card shadow mb-4">
                            <!-- Card Header - Dropdown -->
                            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                <h6 class="m-0 font-weight-bold text-primary">Tổng quan về thu nhập</h6>
                                <div class="dropdown no-arrow">
                                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                                        aria-labelledby="dropdownMenuLink">
                                        <div class="dropdown-header">Các thành phần:</div>
                                        <a class="dropdown-item" href="#">Mục 1</a>
                                        <a class="dropdown-item" href="#">Mục 2</a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="#">Mục 3</a>
                                    </div>
                                </div>
                            </div>
                            <!-- Card Body -->
                            <div class="card-body">
                                <div class="chart-area">
                                    <canvas id="myAreaChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pie Chart -->
                    <div class="col-xl-4 col-lg-5">
                        <div class="card shadow mb-4">
                            <!-- Card Header - Dropdown -->
                            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                <h6 class="m-0 font-weight-bold text-primary">Nguồn thu nhập</h6>
                                <div class="dropdown no-arrow">
                                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                                        aria-labelledby="dropdownMenuLink">
                                        <div class="dropdown-header">Các thành phần:</div>
                                        <a class="dropdown-item" href="#">Mục 1</a>
                                        <a class="dropdown-item" href="#">Mục 2</a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="#">Mục 3</a>
                                    </div>
                                </div>
                            </div>
                            <!-- Card Body -->
                            <div class="card-body">
                                <div class="chart-pie pt-4 pb-2">
                                    <canvas id="myPieChart"></canvas>
                                </div>
                                <div class="mt-4 text-center small">
                                    <span class="mr-2">
                                        <i class="fas fa-circle text-primary"></i> Direct
                                    </span>
                                    <span class="mr-2">
                                        <i class="fas fa-circle text-success"></i> Social
                                    </span>
                                    <span class="mr-2">
                                        <i class="fas fa-circle text-info"></i> Referral
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Content Row -->
                <div class="row">

                    <!-- Content Column -->
                    <div class="col-lg-6 mb-4">

                        <!-- Project Card Example -->
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Projects</h6>
                            </div>
                            <div class="card-body">
                                <h4 class="small font-weight-bold">1 <span class="float-right">20%</span></h4>
                                <div class="progress mb-4">
                                    <div class="progress-bar bg-danger" role="progressbar" style="width: 20%"
                                        aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <h4 class="small font-weight-bold">2 <span class="float-right">40%</span></h4>
                                <div class="progress mb-4">
                                    <div class="progress-bar bg-warning" role="progressbar" style="width: 40%"
                                        aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <h4 class="small font-weight-bold">3 <span class="float-right">60%</span></h4>
                                <div class="progress mb-4">
                                    <div class="progress-bar" role="progressbar" style="width: 60%" aria-valuenow="60"
                                        aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <h4 class="small font-weight-bold">4<span class="float-right">80%</span></h4>
                                <div class="progress mb-4">
                                    <div class="progress-bar bg-info" role="progressbar" style="width: 80%"
                                        aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <h4 class="small font-weight-bold">5 <span class="float-right">Hoàn Thành!</span></h4>
                                <div class="progress">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: 100%"
                                        aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="col-lg-6 mb-4">

                        <!-- Illustrations -->
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Minh họa</h6>
                            </div>
                            <div class="card-body">
                                <div class="text-center">
                                    <img class="img-fluid px-3 px-sm-4 mt-3 mb-4" style="width: 25rem;"
                                        src="/www/src/images/logo_L.png" alt="Ảnh minh họa">

                                </div>
                                <p>Nội dung</p>
                                <a target="_blank" rel="nofollow" href="https:">Đường dẫn &rarr;</a>
                            </div>
                        </div>

                        <!-- Approach -->
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Phương pháp phát triển</h6>
                            </div>
                            <div class="card-body">
                                <p>Nội dung 1</p>
                                <p class="mb-0">Nội dung 2</p>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- End of Main Content -->
        <!-- Custom scripts for all pages-->
        <script src="/www/dist/home/jquery/sb-admin-2.min.js"></script>
        <!-- Bootstrap core JavaScript-->
        <script src="/www/dist/home/jquery/jquery.min.js"></script>
        <script src="/www/dist/home/jquery/bootstrap.bundle.min.js"></script>

        <!-- Core plugin JavaScript-->
        <script src="/www/dist/home/jquery/jquery.easing.min.js"></script>

        <!-- Page level plugins -->
        <script src="/www/dist/home/jquery/Chart.min.js"></script>

        <!-- Page level custom scripts -->
        <script src="/www/dist/home/jquery/chart-area-demo.js"></script>
        <script src="/www/dist/home/jquery/chart-pie-demo.js"></script>
    </main>
    <footer>
        <p>© 2024 Cửa Hàng Online. All rights reserved.</p>
    </footer>

    <script src="/www/dist/home/main.js"></script>
    <script src="/www/dist/book/book.js"></script>
    <!-- <script src="/www/dist/warning/notify.js"></script> -->
    <!-- <script src="/www/dist/taikhoan/taikhoan.js"></script>
    <script src="/www/dist/quyenhan/quyenhan.js"></script>
    <script src="/www/dist/thongke/thongke.js"></script> -->

</body>

</html>