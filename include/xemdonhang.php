<?php
    if(isset($_GET['huydon']) && isset($_GET['magiaodich'])){
        $huydon = $_GET['huydon'];
        $magiaodich = $_GET['magiaodich'];
    }else{
        $huydon = '';
        $magiaodich = '';
    }
    $sql_update_donhang = mysqli_query($con,"UPDATE tbl_donhang SET huydon = '$huydon' WHERE mahang = '$magiaodich'");
    $sql_update_giaodich = mysqli_query($con,"UPDATE tbl_giaodich SET huydon = '$huydon' WHERE magiaodich = '$magiaodich'");
?>

<!-- top Products -->
<div class="ads-grid py-sm-5 py-4">
    <div class="container py-xl-4 py-lg-2">
        <!-- tittle heading -->
        <h3 class="tittle-w3l text-center mb-lg-5 mb-sm-4 mb-3">
            XEM ĐƠN HÀNG
        </h3>
        <!-- //tittle heading -->
        <div class="row">
            <!-- product left -->
            <div class="agileinfo-ads-display col-lg-9">
                <div class="wrapper">
                    <!-- first section -->
                    <div class="product-sec1 px-sm-4 px-3 py-sm-5  py-3 mb-4">
                        <div class="row">
                            <?php
                            if (isset($_SESSION['dangnhap_home'])) {
                                echo '<h5>Đơn hàng của  : ' . $_SESSION['dangnhap_home']. '</h5>';
                            }
                            ?>
                            <br>
                            <br>
                            <div class="col-md-12">
                                <?php
                                if (isset($_GET['khachhang'])) {
                                    $id_khachhang = $_GET['khachhang'];
                                } else {
                                    $id_khachhang = '';
                                }
                                $sql_select = mysqli_query($con, "SELECT magiaodich, MAX(ngaythang) AS ngaythang, COUNT(*) AS total_items, 
                                    MAX(tinhtrangdon) AS tinhtrangdon, MAX(huydon) AS huydon 
                                    FROM tbl_giaodich 
                                    WHERE khachhang_id = '$id_khachhang' 
                                    GROUP BY magiaodich");
                                ?>
                                <table class="table table-bordered">
                                    <tr>
                                        <td>Thứ tự</td>
                                        <td>Mã giao dịch</td>
                                        <td>Ngày đặt hàng</td>
                                        <td>Quản lý</td>
                                        <td>Tình trạng</td>
                                        <td>Hủy đơn</td>
                                    </tr>
                                    <?php
                                    $i = 0;
                                    while ($row_donhang = mysqli_fetch_array($sql_select)) {
                                        $i++;
                                    ?>
                                        <tr>
                                            <td><?php echo $i; ?></td>
                                            <td><?php echo $row_donhang['magiaodich']; ?></td>
                                            <td><?php echo $row_donhang['ngaythang']; ?></td>
                                            <td><a href="index.php?quanly=xemdonhang&khachhang=<?php echo $_SESSION['khachhang_id']?>&magiaodich=<?php echo $row_donhang['magiaodich'] ?>">Xem chi tiết đơn hàng</a></td>
                                            <td><?php
                                                if (isset($row_donhang['tinhtrangdon'])) {
                                                    echo $row_donhang['tinhtrangdon'] == 0 ? 'Đã đặt hàng' : 'Đã xử lý và đang giao hàng';
                                                } else {
                                                    echo 'Tình trạng không xác định';
                                                }
                                            ?></td>
                                            <td>
                                                <?php
                                                if (isset($row_donhang['huydon'])) {
                                                    if ($row_donhang['huydon'] == 0) {
                                                ?>    
                                                        <a href="index.php?quanly=xemdonhang&khachhang=<?php echo $_SESSION['khachhang_id']?>&magiaodich=<?php echo $row_donhang['magiaodich'] ?>&huydon=1">Hủy đơn</a>
                                                <?php
                                                    } else if ($row_donhang['huydon'] == 1) {
                                                ?>
                                                    <p>Đang chờ hủy</p>
                                                <?php
                                                    } else {
                                                        echo "Đã hủy đơn";
                                                    }
                                                } else {
                                                    echo 'Trạng thái hủy không xác định';
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                    <?php
                                    }
                                    ?>
                                </table>
                            </div>
                            <div class="col-md-12">
                                <h5>Chi tiết đơn hàng</h5><br>
                                <?php
                                if (isset($_GET['magiaodich'])) {
                                    $magiaodich = $_GET['magiaodich'];
                                } else {
                                    $magiaodich = '';
                                }
                                $sql_select = mysqli_query($con, "SELECT * FROM tbl_giaodich,tbl_khachhang,tbl_sanpham 
                                    WHERE tbl_giaodich.sanpham_id = tbl_sanpham.sanpham_id 
                                    AND tbl_khachhang.khachhang_id = tbl_giaodich.khachhang_id
                                    AND tbl_giaodich.magiaodich = '$magiaodich'
                                    ORDER BY tbl_giaodich.giaodich_id DESC");
                                ?>
                                <table class="table table-bordered">
                                    <tr>
                                        <td>Thứ tự</td>
                                        <td>Mã giao dịch</td>
                                        <td>Tên Sản phẩm</td>
                                        <td>Số lượng</td>
                                        <td>Giá</td>
                                        <td>Tổng tiền</td>
                                        <td>Ngày đặt hàng</td>
                                    </tr>
                                    <?php
                                    $i = 0;
                                    while ($row_donhang = mysqli_fetch_array($sql_select)) {
                                        $i++;
                                    ?>
                                        <tr>
                                            <td><?php echo $i; ?></td>
                                            <td><?php echo $row_donhang['magiaodich']; ?></td>
                                            <td><?php echo $row_donhang['sanpham_name']; ?></td>
                                            <td><?php echo $row_donhang['soluong']; ?></td>
                                            <td><?php echo number_format($row_donhang['sanpham_giakhuyenmai']). 'VNĐ'; ?></td>
                                            <td><?php echo number_format($row_donhang['soluong'] * $row_donhang['sanpham_giakhuyenmai']) . 'VNĐ'; ?></td>
                                            <td><?php echo $row_donhang['ngaythang']; ?></td>
                                        </tr>
                                    <?php
                                    }
                                    ?>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- //first section -->
                </div>
            </div>
            <!-- //product left -->
        </div>
    </div>
</div>
</div>
<!-- //top products -->