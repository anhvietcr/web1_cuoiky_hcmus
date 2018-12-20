<?php
require_once 'init.php';
$page = 'profile';
$data=[];
if (!isset($_SESSION['logged_in'])) {
    header('Location:login.php');
}
$id=$currentUser[0]['id'];
$data=findUserById($id);

if(isset($_POST['fullname'])and isset($_POST['email']))
{
    updateAccount($id,$_POST['email'],$_POST['fullname']);
    //Lấy file ảnh ra
    if(isset($_FILES['image'])) {
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if($check === false) {
            header("Location: index.php");
        }
        else{
            //tạo đường dẫn
            $dir = dirname(__FILE__);
            $fileName = $_FILES['image']['name'];
            $typeImg = explode(".",$fileName);
            $end = end($typeImg);
            $path = "$dir/img/profile/$id.$end";
            //Lưu xuống db
            createPathImage($id,"/img/profile/$id.$end");
            $fileSize = $_FILES['image']['size'];
            $fileTemp = $_FILES['image']['tmp_name'];
            //di chuyển ảnh từ file tạm vào file cần lưu
            $result = move_uploaded_file($fileTemp,$path);
            var_dump($result);
            //Thu nhỏ ảnh
            if($result)
            {
                $img= new Imagick($path);
                $img->thumbnailImage(256,256);
                $img->writeImage($path);
                //$resizeImage = resizeImage($path,256,256);
                //Lưu ảnh xuống
                //imagejpeg($resizeImage,$path);
            }
            header("Location: index.php");
        }
    }
}
?>
<?php include 'header.php';?>
<div class="container" >

    <div class="col-md-6">
        <h2>Cập nhật thông tin cá nhân</h2>
        <form method="post" action="profile.php" enctype="multipart/form-data">
            <?php
            foreach ($data as $row) {
                ?>
                <div>
                    <label>Họ và tên</label>
                    <input class="form-control" type="text" name = "fullname" value="<?=$row['fullname']?>">

                </div>
                <div>
                    <label>Email</label>
                    <input class="form-control" type="text" name = "email" value="<?= $row['email']?>">

                </div>
                <div>
                    <input class="choose-file" type="file" name="image">
                </div>
               <button type="submit" class="btn btn-primary">Cập nhật</button>

                </div>
                <?php
            }
            ?>

        </form>
    </div>
</div>
<?php include 'footer.php';?>

