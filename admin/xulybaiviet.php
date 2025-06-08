<?php
    include("../db/connect.php");
?>
<?php
    if(isset($_POST['thembaiviet'])){
        $tenbaiviet = $_POST['tenbaiviet'];
        $hinhanh = $_FILES['hinhanh']['name'];
        $danhmuc = $_POST['danhmuc'];
        $chitiet = $_POST['chitiet'];
        $mota = $_POST['mota'];

        $path = "../uploads/";
        $hinhanh_tmp = $_FILES['hinhanh']['tmp_name'];

        $sql_insert_product = mysqli_query($con,"INSERT INTO tbl_baiviet(tenbaiviet,tomtat,noidung,
        danhmuctin_id,baiviet_img)
        VALUES ('$tenbaiviet','$mota','$chitiet','$danhmuc','$hinhanh')");

        move_uploaded_file($hinhanh_tmp,$path.$hinhanh);
    }else if(isset($_POST['suabaiviet'])){
        $id_update = $_POST['id_update'];
        $tenbaiviet = $_POST['tenbaiviet'];
        $hinhanh = $_FILES['hinhanh']['name'];
        $hinhanh_tmp = $_FILES['hinhanh']['tmp_name'];
        $danhmuc = $_POST['danhmuc'];
        $chitiet = $_POST['chitiet'];
        $mota = $_POST['mota'];
        $path = "../uploads/";
       
        if($hinhanh == ''){
            $sql_update_img = "UPDATE tbl_baiviet SET tenbaiviet = '$tenbaiviet', noidung = '$chitiet', tomtat = '$mota',
            danhmuctin_id = '$danhmuc' 
            WHERE baiviet_id = '$id_update'";
        }else{
            move_uploaded_file($hinhanh_tmp,$path.$hinhanh);
            $sql_update_img = "UPDATE tbl_baiviet SET tenbaiviet = '$tenbaiviet', noidung = '$chitiet', tomtat = '$mota',
            baiviet_img = '$hinhanh', danhmuctin_id = '$danhmuc'
            WHERE baiviet_id = '$id_update'";
        }

        mysqli_query($con,$sql_update_img);
    }  
?>
<?php
    if(isset($_GET['xoa'])){
        $id = $_GET['xoa'];
        $sql_xoa_baiviet = mysqli_query($con,"DELETE FROM tbl_baiviet WHERE baiviet_id = '$id'");
        $path_delete = "../uploads/";
    }
?>
<!DOCTYPE html>
<html lang=en>
    <head>
        <meta charset="UTF-8">
        <title>Bài viết</title>
        <link href="../css/bootstrap.css" rel="stylesheet" type="text/css" media="all" />   
    </head>
    <body>
        
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                <li class="nav-item active">
                    <a class="nav-link" href="xulydonhang.php">Đơn hàng <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="xulydanhmuc.php">Danh mục</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="xulydanhmuctin.php">Tin danh mục</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="xulybaiviet.php">Bài viết</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="xulysanpham.php">Sản phẩm</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="xulykhachhang.php">Khách hàng</a>
                </li>
                </ul>
            </div>
        </nav>
        <br><br>
        <div class="container">
            <div class="row">
                <?php
                    if(isset($_GET['quanly']) == 'sua'){
                        $id_sua = $_GET['sua_id'];
                        $sql_sua = mysqli_query($con,"SELECT * FROM tbl_baiviet WHERE baiviet_id = '$id_sua'");
                        $row_sua = mysqli_fetch_array($sql_sua);
                        $id_danhmuctin_sua = $row_sua['danhmuctin_id'];
                        ?>
                        <div class="col-md-4">
                            <h4>Sửa bài viết</h4>
                            <form action="" method="POST" enctype="multipart/form-data"> 
                                <label>Tên bài viết</label>
                                <input type="text" class="form-control" name="tenbaiviet" value="<?php echo $row_sua['tenbaiviet'] ?>"></br>
                                <input type="hidden" class="form-control" name="id_update" value="<?php echo $row_sua['baiviet_id'] ?>"></br>
                                <label>Hình ảnh</label>
                                <input type="file" class="form-control" name="hinhanh" /></br>
                                <img src="../uploads/<?php echo $row_sua['baiviet_img'];?>" width="80" height="100" /></br>
                                <label>Mô tả</label>
                                <textarea class="form-control" rows="10" name="mota" ><?php echo $row_sua['tomtat']; ?></textarea></br>
                                <label>Chi tiết</label>
                                <textarea class="form-control" rows="10" name="chitiet" ><?php echo $row_sua['noidung']; ?></textarea></br>
                                <label>Danh mục</label>
                                <?php
                                    $sql_danhmuctin = mysqli_query($con,"SELECT * FROM tbl_danhmuctin ORDER BY danhmuctin_id DESC");
                                ?>
                                <select name="danhmuc">
                                    <option value="0">------Chọn tin danh mục------</option>
                                    <?php
                                        while($row_danhmuc = mysqli_fetch_array($sql_danhmuctin)){ 
                                            if($id_danhmuctin_sua == $row_danhmuc['danhmuctin_id']){
                                    ?>
                                    <option selected value="<?php echo $row_danhmuc['danhmuctin_id'];?>">
                                    <?php echo $row_danhmuc['tendanhmuc']; ?></option>
                                    <?php
                                    }else{ 
                                    ?>
                                        <option selected value="<?php echo $row_danhmuc['danhmuctin_id'];?>">
                                        <?php echo $row_danhmuc['tendanhmuc']; ?></option>
                                    <?php
                                        }
                                    }    
                                    ?>
                                </select>
                                <input type="submit" name="suabaiviet" value="Sửa bài viết" class="btn btn-default"/>
                            </form>
                            
                        </div>
                        <?php
                            }else{
                        ?>
                        <div class="col-md-4">
                            <h4>Thêm bài viết</h4>
                            
                            <form action="" method="POST" enctype="multipart/form-data"> 
                                <label>Tên bài viết</label>
                                <input type="text" class="form-control" name="tenbaiviet" placeholder="tên bài viết" ></br>
                                <label>Hình ảnh</label>
                                <input type="file" class="form-control" name="hinhanh" /></br>
                                <label>Mô tả</label>
                                <textarea class="form-control" name="mota" ></textarea></br>
                                <label>Chi tiết</label>
                                <textarea class="form-control" name="chitiet" ></textarea></br>
                                <label>Danh mục</label>
                                <?php
                                    $sql_danhmuc = mysqli_query($con,"SELECT * FROM tbl_danhmuctin ORDER BY danhmuctin_id DESC");
                                ?>
                                <select name="danhmuc">
                                    <option value="0">------Chọn tin danh mục------</option>
                                    <?php
                                        while($row_danhmuc = mysqli_fetch_array($sql_danhmuc)){    
                                    ?>
                                    <option value="<?php echo $row_danhmuc['danhmuctin_id'];?>"><?php echo $row_danhmuc['tendanhmuc'];?></option>
                                    <?php
                                        }
                                    ?>
                                </select>
                                <input type="submit" name="thembaiviet" value="Thêm bài viết" class="btn btn-primary"/>
                            </form>
                        </div>                     
                <?php    
                    }
                ?>  
    
                <div class="col-md-8">
                    <h4>Liệt kê bài viết</h4>
                     <?php
                        $sql_select_baiviet = mysqli_query($con,"SELECT * FROM tbl_baiviet,tbl_danhmuctin 
                        WHERE tbl_baiviet.danhmuctin_id = tbl_danhmuctin.danhmuctin_id ORDER BY tbl_baiviet.baiviet_id DESC");
                    ?> 
                    <table class="table table-bordered">
                        <tr>
                            <td>Thứ tự</td>
                            <td>Tên bài viết</td>
                            <td>Hình ảnh</td>       
                            <td>Danh mục</td>
                            <td>Quản lý</td>
                        </tr>
                        <?php
                            $i = 0;
                            while($row_baiviet = mysqli_fetch_array($sql_select_baiviet)){
                                $i++;
                        ?>
                            <tr>
                                <td><?php echo $i;?></td>
                                <td><?php echo $row_baiviet['tenbaiviet'] ;?></td>
                                <td><img src="../uploads/<?php echo $row_baiviet['baiviet_img'] ;?>" height="80" width="80" /></td>
                                <td><?php echo $row_baiviet['tendanhmuc'] ;?></td>
                                <td><a href="xulybaiviet.php?quanly=sua&sua_id=<?php echo $row_baiviet['baiviet_id'] ;?>">Sửa</a> 
                                || <a href="?xoa=<?php echo $row_baiviet['baiviet_id'] ;?>">Xóa</a></td>
                            </tr> 
                         <?php
                            }
                        ?>
                    </table>
                </div>
            </div>
        </div>
    </body>
</html>