<?php
    include("../db/connect.php");
?>
<?php
    if(isset($_POST['themdanhmuc'])){
        $tendanhmuc = $_POST['danhmuc'];
        $sql_insert = mysqli_query($con,"INSERT INTO tbl_category(category_name) VALUES ('$tendanhmuc')");
    }else if(isset($_POST['suadanhmuc'])){
        $id_post_sua = $_POST['id_danhmuc'];
        $tendanhmuc = $_POST['danhmuc'];
        $sql_update = mysqli_query($con,"UPDATE tbl_category SET category_name = '$tendanhmuc' WHERE category_id = '$id_post_sua'");
    }
    if(isset($_GET['xoa'])){
        $id = $_GET['xoa'];
        $sql_xoa = mysqli_query($con,"DELETE FROM tbl_category WHERE category_id = '$id'");
    }
?>
<!DOCTYPE html>
<html lang=en>
    <head>
        <meta charset="UTF-8">
        <title>DANH MỤC</title>
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
                        $id_sua = $_GET['id'];
                        $sql_sua = mysqli_query($con,"SELECT * FROM tbl_category WHERE category_id = '$id_sua'");
                        $row_sua = mysqli_fetch_array($sql_sua);
                        ?>
                        <div class="col-md-4">
                            <h4>Sửa danh mục</h4>
                            <label>Tên danh mục</label>
                            <form action="" method="POST">
                                <input type="text" class="form-control" name="danhmuc" placeholder="tên danh mục"
                                value="<?php echo $row_sua['category_name'];?>" />
                                <br>
                                <input type="hidden" class="form-control" name="id_danhmuc" placeholder="tên danh mục"
                                value="<?php echo $row_sua['category_id'];?>" />
                                <input type="submit" name="suadanhmuc" value="Sửa danh mục" class="btn btn-default"/>
                            </form>
                        </div>
                        <?php
                    }else{
                        ?>
                        <div class="col-md-4">
                            <h4>Thêm danh mục</h4>
                            <label>Tên danh mục</label>
                            <form action="" method="POST">
                                <input type="text" class="form-control" name="danhmuc" placeholder="tên danh mục" /><br>
                                <input type="submit" name="themdanhmuc" value="Thêm danh mục" class="btn btn-default"/>
                            </form>
                        </div>                     
                <?php    
                    }
                ?>  
    
                <div class="col-md-8">
                    <h4>Liệt kê danh mục</h4>
                    <?php
                        $sql_select = mysqli_query($con,"SELECT * FROM tbl_category ORDER BY category_id DESC");
                    ?>
                    <table class="table table-bordered">
                        <tr>
                            <td>Thứ tự</td>
                            <td>Tên danh mục</td>
                            <td>Quản lý</td>
                        </tr>
                        <?php
                        $i = 0;
                            while($row_category = mysqli_fetch_array($sql_select)){
                                $i++
                        ?>
                            <tr>
                                <td><?php echo $i ;?></td>
                                <td><?php echo $row_category['category_name'] ;?></td>
                                <td><a href="?quanly=sua&id=<?php echo $row_category['category_id']?>">Sửa</a> || <a href="?xoa=<?php echo $row_category['category_id']?>">Xóa</a></td>
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