<?php

if (isset($_POST['themgiohang'])) {
    $tensanpham = $_POST['tensanpham'];
    $sanpham_id = $_POST['sanpham_id'];
    $hinhanh = $_POST['hinhanh'];
    $gia = $_POST['giasanpham'];
    $soluong = $_POST['soluong'];
    $sql_select_giohang = mysqli_query($con, "SELECT * FROM tbl_giohang WHERE sanpham_id = '$sanpham_id'");
    $count = mysqli_num_rows($sql_select_giohang);

    if ($count > 0) {
        $row_sanpham = mysqli_fetch_array($sql_select_giohang);
        $soluong = $row_sanpham['soluong'] + 1;
        $sql_giohang = "UPDATE tbl_giohang SET soluong = '$soluong' WHERE sanpham_id = '$sanpham_id'";
    } else {
        $soluong = $soluong;
        $sql_giohang = "INSERT INTO tbl_giohang(tensanpham,sanpham_id,giasanpham,hinhanh,soluong)
            VALUES ('$tensanpham','$sanpham_id','$gia','$hinhanh','$soluong')";
    }

    $insert_row = mysqli_query($con, $sql_giohang);

    if ($insert_row == 0) {
        header('Location: index.php?quanly=chitietsp&id=' . $sanpham_id);
    }
} else if (isset($_POST['capnhatgiohang'])) {
    for ($i = 0; $i < count($_POST['product_id']); $i++) {
        $sanpham_id = $_POST['product_id'][$i];
        $soluong = $_POST['soluong'][$i];
        if ($soluong <= 0) {
            $sql_delete = mysqli_query($con, "DELETE FROM tbl_giohang WHERE sanpham_id = '$sanpham_id' ");
        } else {
            $sql_update = mysqli_query($con, "UPDATE tbl_giohang SET soluong = $soluong WHERE sanpham_id = $sanpham_id");
        }
    }
} else if (isset($_GET['xoa'])) {
    $id = $_GET['xoa'];
    $sql_delete =  mysqli_query($con, "DELETE FROM tbl_giohang WHERE giohang_id = '$id'");
} else if (isset($_GET['dangxuat'])) {
    $id = $_GET['dangxuat'];
    if ($id == 1) {
        unset($_SESSION['dangnhap_home']);
    }
} else if (isset($_POST['thanhtoan'])) {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $email = $_POST['email'];
    $password = md5($_POST['password']);

    $sql_khachhang = mysqli_query($con, "INSERT INTO tbl_khachhang(name,phone,address,email,giaohang,note,password)
        VALUES ('$name','$phone','$address','$email','0','','$password')");

    if ($sql_khachhang) {
        $sql_select_khachhang = mysqli_query($con, "SELECT * FROM tbl_khachhang ORDER BY khachhang_id DESC LIMIT 1");
        $mahang = rand(0, 99999);
        $row_khachhang = mysqli_fetch_array($sql_select_khachhang);
        $khachhang_id = $row_khachhang['khachhang_id'];
        $_SESSION['dangnhap_home'] = $row_khachhang['name'];
        $_SESSION['khachhang_id'] = $khachhang_id;
        for ($i = 0; $i < count($_POST['thanhtoan_product_id']); $i++) {
            $sanpham_id = $_POST['thanhtoan_product_id'][$i];
            $soluong = $_POST['thanhtoan_soluong'][$i];
            $sql_donhang = mysqli_query($con, "INSERT INTO tbl_donhang(sanpham_id,khachhang_id,soluong,mahang,trangthai,huydon)
                VALUES ('$sanpham_id','$khachhang_id','$soluong','$mahang',0,0)");
            $sql_giaodich = mysqli_query($con, "INSERT INTO tbl_giaodich(sanpham_id,soluong,magiaodich,khachhang_id,tinhtrangdon,huydon)
                VALUES ('$sanpham_id','$soluong','$mahang','$khachhang_id',0,0) ");
            $sql_delete_thanhtoan =  mysqli_query($con, "DELETE FROM tbl_giohang WHERE sanpham_id = '$sanpham_id'");
        }
    }
} else if (isset($_POST['thanhtoandangnhap'])) {
    $khachhang_id = $_SESSION['khachhang_id'];
    $mahang = rand(0, 99999);
    $magiaodich = rand(0, 99999);
    for ($i = 0; $i < count($_POST['thanhtoan_product_id']); $i++) {
        $sanpham_id = $_POST['thanhtoan_product_id'][$i];
        $soluong = $_POST['thanhtoan_soluong'][$i];

        // Check if giaodich_id is set and valid
        $giaodich_id = $magiaodich;

        // Insert into tbl_donhang
        $sql_donhang = mysqli_query($con, "INSERT INTO tbl_donhang(sanpham_id,khachhang_id,soluong,mahang,trangthai,huydon)
            VALUES ('$sanpham_id','$khachhang_id','$soluong','$mahang',0,0)");

        // If giaodich_id is null, don't include it in the insert statement
        if ($giaodich_id !== null) {
            $sql_giaodich = mysqli_query($con, "INSERT INTO tbl_giaodich(giaodich_id,sanpham_id,soluong,magiaodich,khachhang_id,tinhtrangdon,huydon)
                VALUES ('$giaodich_id','$sanpham_id','$soluong','$mahang','$khachhang_id',0,0) ");
        } else {
            // Handle the case where giaodich_id is not provided
            $sql_giaodich = mysqli_query($con, "INSERT INTO tbl_giaodich(sanpham_id,soluong,magiaodich,khachhang_id,tinhtrangdon,huydon)
                VALUES ('$sanpham_id','$soluong','$mahang','$khachhang_id',0,0) ");
        }

        // Delete from tbl_giohang
        $sql_delete_thanhtoan = mysqli_query($con, "DELETE FROM tbl_giohang WHERE sanpham_id = '$sanpham_id'");
    }
}

?>
<!-- checkout page -->
<div class="privacy py-sm-5 py-4">
    <div class="container py-xl-4 py-lg-2">
        <!-- tittle heading -->
        <h3 class="tittle-w3l text-center mb-lg-5 mb-sm-4 mb-3">
            <span>Giỏ hàng</span>
        </h3>
        <?php
        if (isset($_SESSION['dangnhap_home'])) {
            echo "<p style='font-size:large; color:#0879c9;'>Xin chào bạn : "
                . "$_SESSION[dangnhap_home]" .
                " <br><a href='index.php?quanly=giohang&dangxuat=1'>Đăng xuất</a></p>";
        } else {
            echo "";
        }
        ?>
        <!-- //tittle heading -->
        <div class="checkout-right">
            <?php
            $sql_lay_giohang = mysqli_query($con, "SELECT * FROM tbl_giohang ORDER BY giohang_id DESC");
            ?>
            <div class="table-responsive">
                <form action="" method="post">
                    <table class="timetable_sub">
                        <thead>
                            <tr>
                                <th>Thứ tự</th>
                                <th>Sản phẩm</th>
                                <th>Số lượng</th>
                                <th>Tên sản phẩm</th>

                                <th>Giá</th>
                                <th>Giá tổng</th>
                                <th>Quản lý</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 0;
                            $total = 0;
                            while ($row_fetch_giohang = mysqli_fetch_array($sql_lay_giohang)) {
                                $sub_total = $row_fetch_giohang['soluong'] * $row_fetch_giohang['giasanpham'];
                                $total += $sub_total;
                                $i++;
                            ?>
                                <tr class="rem1">
                                    <td class="invert"><?php echo $i ?></td>
                                    <td class="invert-image">
                                        <a href="?quanly=chitietsp&id=<?php echo $row_fetch_giohang['sanpham_id']; ?>">
                                            <img src="images/<?php echo $row_fetch_giohang['hinhanh'] ?>" style="width:100px; height: 100px;" alt=" " class="img-responsive">
                                        </a>
                                    </td>
                                    <td class="invert">
                                        <input type="number" name="soluong[]" value="<?php echo $row_fetch_giohang['soluong'] ?>" min="1">
                                        <input type="hidden" name="product_id[]" value="<?php echo $row_fetch_giohang['sanpham_id'] ?>">
                                    </td>
                                    <td class="invert"><?php echo $row_fetch_giohang['tensanpham'] ?></td>
                                    <td class="invert"><?php echo number_format($row_fetch_giohang['giasanpham']) . 'VNĐ' ?></td>
                                    <td class="invert"><?php echo number_format($sub_total) . 'VNĐ' ?></td>
                                    <td class="invert">
                                        <a href="?quanly=giohang&xoa=<?php echo $row_fetch_giohang['giohang_id']; ?>">Xóa</a>
                                    </td>
                                </tr>
                            <?php
                            }
                            ?>
                            <tr>
                                <td colspan="7">Tổng tiền cần thanh toán: <?php echo number_format($total) . 'VNĐ' ?></td>

                            </tr>
                            <tr>
                                <td colspan="7"><input type="submit" value="cập nhật giỏ hàng" class="btn btn-success" name="capnhatgiohang" />
                                    <?php
                                    $sql_select_hang = mysqli_query($con, "SELECT * FROM tbl_giohang");
                                    $count_giohang_select = mysqli_num_rows($sql_select_hang);
                                    if (isset($_SESSION['dangnhap_home']) && $count_giohang_select > 0) {
                                        while($row_tt = mysqli_fetch_array($sql_select_hang)){
                                    ?>
                                        <input type="hidden" name="thanhtoan_product_id[]" value="<?php echo $row_tt['sanpham_id'] ?>" />

                                        <input type="hidden" name="thanhtoan_soluong[]" value="<?php echo $row_tt['soluong'] ?>" />
                                    <?php
                                        }
                                    ?>
                                        <input type="submit" value="thanh toán giỏ hàng" class="btn btn-primary" name="thanhtoandangnhap" />
                                </td>
                            <?php
                                    }
                            ?>
                            </tr>
                        </tbody>
                    </table>
                </form>
            </div>
        </div>
        <?php
        if (!isset($_SESSION['dangnhap_home'])) {
        ?>
            <div class="checkout-left">
                <div class="address_form_agile mt-sm-5 mt-4">
                    <h4 class="mb-sm-4 mb-3">Thêm địa chỉ giao hàng</h4>
                    <form action="" method="post" class="creditly-card-form agileinfo_form">
                        <div class="creditly-wrapper wthree, w3_agileits_wrapper">
                            <div class="information-wrapper">
                                <div class="first-row">
                                    <div class="controls form-group">
                                        <input class="billing-address-name form-control" type="text" name="name" placeholder="Họ và tên" required="">
                                    </div>
                                    <div class="w3_agileits_card_number_grids">
                                        <div class="w3_agileits_card_number_grid_left form-group">
                                            <div class="controls">
                                                <input type="text" class="form-control" placeholder="Số điện thoại" name="phone" required="">
                                            </div>
                                        </div>
                                        <div class="w3_agileits_card_number_grid_right form-group">
                                            <div class="controls">
                                                <input type="text" class="form-control" placeholder="Địa chỉ" name="address" required="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="controls form-group">
                                        <input type="email" class="form-control" placeholder="Email" name="email" required="">
                                    </div>
                                    <div class="controls form-group">
                                        <input type="password" class="form-control" placeholder="Password" name="password" required="">
                                    </div>
                                    <div class="controls form-group">
                                        <select class="option-w3ls" name="giaohang">
                                            <option>Chọn hình thức giao hàng</option>
                                            <option value="1">Thanh toán bằng ATM</option>
                                            <option value="0 ">Thanh toán lúc nhận hàng</option>
                                        </select>
                                    </div>
                                    <div class="controls form-group">
                                        <textarea type="email" class="form-control" placeholder="Ghi chú" name="note"></textarea>
                                    </div>
                                </div>
                                <?php
                                $sql_lay_giohang = mysqli_query($con, "SELECT * FROM  tbl_giohang ORDER BY giohang_id DESC");
                                while ($row_thanhtoan = mysqli_fetch_array($sql_lay_giohang)) {
                                ?>

                                    <input type="hidden" name="thanhtoan_product_id[]" value="<?php echo $row_thanhtoan['sanpham_id'] ?>" />

                                    <input type="hidden" name="thanhtoan_soluong[]" value="<?php echo $row_thanhtoan['soluong'] ?>" />

                                <?php
                                }
                                ?>
                                <input type="submit" name="thanhtoan" class="btn btn-success" value="Đặt hàng"></input>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        <?php
        }
        ?>
    </div>
</div>
<!-- //checkout page -->