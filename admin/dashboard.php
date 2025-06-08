<?php
    session_start();
    if(!isset($_SESSION['dangnhap'])){
        header("Location: index.php");
    }
    if(isset($_GET['login'])){
        $dangxuat = $_GET['login'];
    }else{
        $dangxuat = '';
    }
    if($dangxuat == 'dangxuat'){
        session_destroy();
        header('location: index.php');
    }
?>
<!DOCTYPE html>
<html lang=en>
    <head>
        <meta charset="UTF-8">
        <title>ADMIN</title>
        <link href="../css/bootstrap.css" rel="stylesheet" type="text/css" media="all" />   
    </head>
    <body>
        <h5 align="center" style="font-size: 50px;">ADMIN</h5>
        <p style="font-size: 30px; margin: 0 50px;"><?php echo $_SESSION['dangnhap'];?> <a style="font-size: 30px;" href="?login=dangxuat">Đăng xuất</a></p>
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
    </body>
</html>