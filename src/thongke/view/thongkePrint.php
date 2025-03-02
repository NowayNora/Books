<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href= "../../styles/style-bill-print.css">
    <link rel="shortcut icon" href="../../images/icon-shop.png">
    <title>In Hóa Đơn</title>
</head>
<body>
    <div class="page">
        <div class="top">
            <div class="top-left">
                <div class="name-store">
                    <div class="logo">
                        <img src="../../images/LOGO-WEB.png" alt="">
                    </div>
                    <div class="name-company">
                        <div class="name">DOUBLE K</div>
                        <div class="expert">CHUYÊN: THỜI TRANG NAM, CÁC THƯƠNG HIỆU NỔI TIẾNG</div>
                    </div>
                </div>
                <div class="info">
                    <div class="info-address">ĐC: Trần Chiên - Cái Răng - Cần Thơ</div>
                    <div class="info-phone">ĐT: 0365907475</div>
                    <div class="info-business">ĐKKD: 0976283123 - KT: 0956745243</div>
                </div>
            </div>
            <div class="top-right">
                <div class="name-bill">HÓA ĐƠN BÁN HÀNG THỜI TRANG NAM</div>
                <div class="bill-expert">
                    <p>MUA - BÁN - SỬA CHỮA</p>
                    <p>CUNG CẤP PHỤ KIỆN THỜI TRANG NAM</p>
                    <p>DỊCH VỤ TƯ VẤN THỜI TRANG</p>
                    <p>PHỤC VỤ CHO MỌI NGƯỜI ĐÀN ÔNG</p>
                </div>
            </div>
        </div>
        <div class="body">
            <div class="code-bill">
                Nhân viên thống kê: <span style="font-style: italic; font-weight: bold;"> <?php echo $getnv->TENNV; ?> </span>
            </div>
            <div class="name-customer">
                Thống kê từ ngày: <span style="font-style: italic; font-weight: bold;"> <?php echo $getthongke->TUNGAY; ?> </span>
            </div>
            <div class="address-customer">
                Thống kê đến ngày: <span style="font-style: italic; font-weight: bold;"> <?php echo $getthongke->DENNGAY; ?> </span>
            </div>
             <div class="table-thongke">
            <?php 
                $idthongke = $_GET['idthongke'];
                $getthongke = $thongke->ThongkeGetbyId($idthongke);
                $tungay = $getthongke->TUNGAY;
                $denngay = $getthongke->DENNGAY;

                $tenthongke = $getthongke->TENTHONGKE;
                if($tenthongke == 'Tất cả'){
                    $donban = new Donban();
                    $donbanct = new DonbanCT();
                    $donnhap = new Doncungung();
                    $donnhapct = new DoncungungCT();
                    $congno = new Congno();
                    $listdonban = $donban->DonbanGetByNgay($tungay,$denngay);
                    $listdonnhap = $donnhap->DonnhapGetByNgay($tungay,$denngay);
                    $listcongno = $congno->CongnoGetbyNgay($tungay,$denngay);
                    if(count($listdonban) > 0 || count($listdonnhap) > 0 || count($listcongno) > 0){
                        $soluongban = 0;
                        $soluongmua = 0;
                        foreach($listdonban as $db){
                            $listdbct = $donbanct->DonbanCTGetbyIDDonban($db->ID_DB);
                            foreach($listdbct as $dbct){
                                $soluongban += $dbct->SOLUONG;
                            }
                        }
                        foreach($listdonnhap as $dn){
                            $listdnct = $donnhapct->DoncungungCTGetbyIdDCU($dn->ID_DCU);
                            foreach($listdnct as $dnct){
                                $soluongmua += $dnct->SOLUONG;
                            }
                        }
                        $tonkho = $soluongmua - $soluongban;

                        $tongtienno = 0;
                        $tongtientra = 0;
                        foreach($listcongno as $cn){
                            $tongtienno += $cn->SOTIENNO;
                            $tongtientra += $cn->SOTIENTRA;
                        }
                        $conlai = $tongtienno - $tongtientra;

                        $tongthu = 0;
                        $tongchi = 0;
                        foreach($listdonban as $db){
                            $tongthu += $db->TONGCONG;
                        }
                        foreach($listdonnhap as $dn){
                            $tongchi += $dn->TONGCONG;
                        }
                        $loinhuan = $tongthu - $tongchi;
                    ?>
                        <div class="table-new" style="overflow: hidden; height: 270px;">
                            <div style="text-align: center; margin: 0px 0 10px 0; color: black; font-weight: bold; font-size: 25px;">
                                Kết quả thống kê từ ngày <?php echo date("d-m-Y", strtotime($tungay)); ?> đến ngày <?php echo date("d-m-Y", strtotime($denngay)); ?>
                            </div>
                            <div style="margin-bottom: 30px; display: flex; justify-content: center;">
                                <table class="content-table-bill-big" style="width: 60%;">
                                    <thead>
                                        <tr>
                                            <th>Số Lượng Nhập</th>
                                            <th>Số Lượng Bán Ra</th>
                                            <th>Tồn Kho</th>                   
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td style="text-align: center;"><?php echo $soluongmua; ?></td>
                                            <td style="text-align: center;"><?php echo $soluongban; ?></td>
                                            <td style="text-align: center;"><?php echo $tonkho; ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div style="display: flex; ">
                                <div style="flex: 1; display: flex; justify-content: center;">
                                    <table class="content-table-bill-big" style="width: 90%;">
                                        <thead>
                                            <tr>
                                                <th>Số Hóa Đơn Nợ</th>
                                                <th>Tổng Số Tiền Nợ</th>
                                                <th>Tổng Số Tiền Đã Trả</th>
                                                <th>Còn Lại</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td style="text-align: center;"><?php echo count($listcongno); ?></td>
                                                <td style="text-align: center;"><?php echo number_format($tongtienno, 0, ',', '.') . " ₫"; ?></td>
                                                <td style="text-align: center;"><?php echo number_format($tongtientra, 0, ',', '.') . " ₫"; ?></td>
                                                <td style="text-align: center;"><?php echo number_format($conlai, 0, ',', '.') . " ₫"; ?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div style="flex: 1; display: flex; justify-content: center;">
                                    <table class="content-table-bill-big" style="width: 90%;"> 
                                        <thead>
                                            <tr>
                                                <th>Tổng Thu</th>
                                                <th>Tổng Chi</th>
                                                <th>Lợi Nhuận</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td style="text-align: center;"><?php echo number_format($tongthu, 0, ',', '.') . " ₫"; ?></td>
                                                <td style="text-align: center;"><?php echo number_format($tongchi, 0, ',', '.') . " ₫"; ?></td>
                                                <td style="text-align: center;"><?php echo number_format($loinhuan, 0, ',', '.') . " ₫"; ?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    <?php
                    }
                    else{
                    ?>
                        <div class="table-new" style="overflow: hidden; height: 290px; display: flex; justify-content: center; align-items: center;">
                            <h2 data-text='Không có dữ liệu để thống kê trong khoảng thời gian này'>Không có dữ liệu để thống kê trong khoảng thời gian này</h2>
                        </div>
                    <?php
                    }
                }
                else if($tenthongke == 'Tồn kho'){
                    $donban = new Donban();
                    $donbanct = new DonbanCT();
                    $donnhap = new Doncungung();
                    $donnhapct = new DoncungungCT();
                    $listdonban = $donban->DonbanGetByNgay($tungay,$denngay);
                    $listdonnhap = $donnhap->DonnhapGetByNgay($tungay,$denngay);

                    if(count($listdonban) > 0 || count($listdonnhap) > 0){
                        $soluongban = 0;
                        $soluongmua = 0;
                        foreach($listdonban as $db){
                            $listdbct = $donbanct->DonbanCTGetbyIDDonban($db->ID_DB);
                            foreach($listdbct as $dbct){
                                $soluongban += $dbct->SOLUONG;
                            }
                        }
                        foreach($listdonnhap as $dn){
                            $listdnct = $donnhapct->DoncungungCTGetbyIdDCU($dn->ID_DCU);
                            foreach($listdnct as $dnct){
                                $soluongmua += $dnct->SOLUONG;
                            }
                        }
                        $conlai = $soluongmua - $soluongban;
                ?>
                        <div class="table-new" style="overflow: hidden; height: 270px;">
                            <div style="text-align: center; margin: 10px 0 20px 0; color: black; font-weight: bold; font-size: 25px;">
                                Kết Quả Thống Kê Từ <?php echo $tungay; ?> Đến <?php echo $denngay; ?>
                            </div>
                            <table class="content-table-bill-big">
                                <thead>
                                    <tr>
                                        <th>Số Lượng Nhập</th>
                                        <th>Số Lượng Bán Ra</th>
                                        <th>Tồn Kho</th>                   
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td style="text-align: center;"><?php echo $soluongmua; ?></td>
                                        <td style="text-align: center;"><?php echo $soluongban; ?></td>
                                        <td style="text-align: center;"><?php echo $conlai; ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                <?php
                    }
                    else{
                    ?>
                        <div class="table-new" style="overflow: hidden; height: 290px; display: flex; justify-content: center; align-items: center;">
                            <h2 data-text='Không có dữ liệu để thống kê trong khoảng thời gian này'>Không có dữ liệu để thống kê trong khoảng thời gian này</h2>
                        </div>
                    <?php
                    }
                }
                else if($tenthongke == 'Công nợ'){
                    $congno = new Congno();
                    $listcongno = $congno->CongnoGetbyNgay($tungay,$denngay);

                    if(count($listcongno) > 0){
                        $tongtienno = 0;
                        $tongtientra = 0;
                        foreach($listcongno as $cn){
                            $tongtienno += $cn->SOTIENNO;
                            $tongtientra += $cn->SOTIENTRA;
                        }
                        $conlai = $tongtienno - $tongtientra;
                ?>
                        <div class="table-new" style="overflow: hidden; height: 270px;">
                            <div style="text-align: center; margin: 10px 0 20px 0; color: black; font-weight: bold; font-size: 25px;">
                                Kết Quả Thống Kê Từ <?php echo $tungay; ?> Đến <?php echo $denngay; ?>
                            </div>
                            <table class="content-table-bill-big">
                                <thead>
                                    <tr>
                                        <th>Số Hóa Đơn Nợ</th>
                                        <th>Tổng Số Tiền Nợ</th>
                                        <th>Tổng Số Tiền Đã Trả</th>
                                        <th>Còn Lại</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td style="text-align: center;"><?php echo count($listcongno); ?></td>
                                        <td style="text-align: center;"><?php echo number_format($tongtienno, 0, ',', '.') . " ₫"; ?></td>
                                        <td style="text-align: center;"><?php echo number_format($tongtientra, 0, ',', '.') . " ₫"; ?></td>
                                        <td style="text-align: center;"><?php echo number_format($conlai, 0, ',', '.') . " ₫"; ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                <?php
                    }
                    else{
                    ?>
                        <div class="table-new" style="overflow: hidden; height: 290px; display: flex; justify-content: center; align-items: center;">
                            <h2 data-text='Không có dữ liệu để thống kê trong khoảng thời gian này'>Không có dữ liệu để thống kê trong khoảng thời gian này</h2>
                        </div>
                    <?php
                    }
                }
                else{
                    // doanh thu
                    $donban = new Donban();
                    $donnhap = new Doncungung();
                    $listdonban = $donban->DonbanGetByNgay($tungay,$denngay);
                    $listdonnhap = $donnhap->DonnhapGetByNgay($tungay,$denngay);


                    if(count($listdonban) > 0 || count($listdonnhap) > 0){
                        $tongthu = 0;
                        $tongchi = 0;
                        foreach($listdonban as $db){
                            $tongthu += $db->TONGCONG;
                        }
                        foreach($listdonnhap as $dn){
                            $tongchi += $dn->TONGCONG;
                        }
                        $loinhuan = $tongthu - $tongchi;
                ?>
                        <div class="table-new" style="overflow: hidden; height: 270px;">
                            <div style="text-align: center; margin: 10px 0 20px 0; color: black; font-weight: bold; font-size: 25px;">
                                Kết Quả Thống Kê Từ <?php echo $tungay; ?> Đến <?php echo $denngay; ?>
                            </div>
                            <table class="content-table-bill-big">
                                <thead>
                                    <tr>
                                        <th>Tổng Thu</th>
                                        <th>Tổng Chi</th>
                                        <th>Lợi Nhuận</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td style="text-align: center;"><?php echo number_format($tongthu, 0, ',', '.') . " ₫"; ?></td>
                                        <td style="text-align: center;"><?php echo number_format($tongchi, 0, ',', '.') . " ₫"; ?></td>
                                        <td style="text-align: center;"><?php echo number_format($loinhuan, 0, ',', '.') . " ₫"; ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                <?php
                    }
                    else{
                    ?>
                        <div class="table-new" style="overflow: hidden; height: 290px; display: flex; justify-content: center; align-items: center;">
                            <h2 data-text='Không có dữ liệu để thống kê trong khoảng thời gian này'>Không có dữ liệu để thống kê trong khoảng thời gian này</h2>
                        </div>
                    <?php
                    }
                }
                
            ?>
            </div>
        </div>
    </div>
</body>
</html>