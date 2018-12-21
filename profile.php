<?php
require_once 'inc/autoload.php';
//Trang
// Format Helper
$formatHelper = new FormatHelper();
$user = new UserController();
$status = new StatusController();
if (!isset($_COOKIE['login'])) {
    header('Location: index.php');
}

$user1 =$user->GetUser($_COOKIE['login']);
$id_user2 = $_GET['id'];
$id_user1 = $user1['id'];

//Xác định mối quan hệ gì
$noRelationship = false;
$following= false;
$follows= false;
$followed= false;

$followedA = !empty($usrA['followed']) ? unserialize($usrA['followed']) : [];
$followedB = !empty($usrB['followed']) ? unserialize($usrB['followed']) : [];

$followingA = !empty($A['following']) ? unserialize($A['following']) : [];
$followsA = !empty($A['follows']) ? unserialize($A['follows']) : [];

$followsB = !empty($B['follows']) ? unserialize($B['follows']) : [];
$followingB = !empty($B['following']) ? unserialize($B['following']) : [];

//nếu là chính mình
if($id_user1==$id_user2)
{

}
//Nếu là bạn bè
else if (in_array($id_user1, $followedB) && in_array($id_user2, $followedA)) {
    $followed=true;
}
//Nếu A đang theo dõi B
else if (in_array($id_user2, $followingA) || in_array($id_user1, $followsB)) {
    $following = true;
}
//Nếu B đang theo dõi A
else if(in_array($id_user1, $followingB) || in_array($id_user2, $followsA))
{
    $follows=true;
}
else{
    $noRelationship=true;
}
$user2 = $user->GetUser('',$id_user2);
$user2_avatar = !empty($user2['avatar']) ? 'data:image;base64,'.$user2['avatar'] : "asset/img/non-avatar.png";
$statusOfUserB = $status->ShowStatusWithRelationship($user1['id'],$id_user2);
?>
<?= $formatHelper->addHeader($_COOKIE['login']) ?>
<?= $formatHelper->addFixMenu() ?>
<div class="main">
    <div class="content">
        <form action="friends.php" method="post">
            <div class="card" style="width: 35rem;">
                <input type="hidden" name = "name" value="<?=$user2['username']?>">
                <img class="card-img-top" height="350"src="<?=$user2_avatar?>">
                <div class="card-body">
                    <h4 class="card-title">Thông tin</h4>
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item"><strong>Họ tên: </strong> <?=$user2['realname']?></li>
                </ul>

                <div class="card-body">
                    <?php
                    if($id_user2!==$id_user1) {
                        if ($noRelationship) {
                            ?>
                            <button type="submit" class="btn btn-success" name="addFriend">Thêm bạn bè</button>
                            <?php
                        }
                        ?>
                        <?php
                        if ($follows) {
                            ?>
                            <button type="submit" class="btn btn-primary" name="acceptFriend">Chấp nhận</button>
                            <button type="submit" class="btn btn-secondary" name="declineFriend">Xóa</button>
                            <?php
                        }
                        ?>
                        <?php
                        if ($followed) {
                            ?>
                            <button type="submit" class="btn btn-light" name="unFriend">Hủy kết bạn</button>
                            <?php
                        }
                        if ($following) {
                            ?>
                            <button type="submit" class="btn btn-light" name="delete-friend">Hủy lời mời</button>
                            <?php
                        }
                    }
                    ?>

                </div>
            </div>
        </form>
    </div>

    <?= $formatHelper->addRightMenu() ?>

    <?= $formatHelper->addNewsfeed($statusOfUserB,$user1['username'])?>
</div>
<?= $formatHelper->closeFooter() ?>
