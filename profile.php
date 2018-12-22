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
$id_user2 = $_GET['id'];
$user2 = $user->GetUser('',$id_user2);
$user1 =$user->GetUser($_COOKIE['login']);
$id_user1 = $user1['id'];
//Xác định mối quan hệ gì
$noRelationship = false;
$following= false;
$follows= false;
$followed= false;

$followedA = !empty($user1['followed']) ? unserialize($user1['followed']) : [];
$followedB = !empty($user2['followed']) ? unserialize($user2['followed']) : [];

$followingA = !empty($user1['following']) ? unserialize($user1['following']) : [];
$followsA = !empty($user1['follows']) ? unserialize($user1['follows']) : [];

$followsB = !empty($user2['follows']) ? unserialize($user2['follows']) : [];
$followingB = !empty($user2['following']) ? unserialize($user2['following']) : [];

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

//Comment
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (isset($_POST['addComment']) && !empty($_POST['content_comment'])) {
        $user->NewComment($_POST['id_status'],$_COOKIE['login'],$_POST['content_comment']);
        header('Location: '.$_SERVER['PHP_SELF']);

    }
}

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
                            <button type="submit" class="btn btn-primary" name="addFriend">Thêm bạn bè</button>
                            <?php
                        }
                        ?>
                        <?php
                        if ($follows) {
                            ?>
                            <button type="submit" class="btn btn-success" name="acceptFriend">Chấp nhận</button>
                            <button type="submit" class="btn btn-danger" name="declineFriend">Từ chối</button>
                            <?php
                        }
                        ?>
                        <?php
                        if ($followed) {
                            ?>
                            <button type="submit" class="btn btn-danger" name="unFriend">Hủy kết bạn</button>
                            <?php
                        }
                        if ($following) {
                            ?>
                            <button type="submit" class="btn btn-warning" name="delete-friend">Bỏ theo dõi</button>
                            <?php
                        }
                    }
                    ?>

                </div>
            </div>
        </form>
        <?= $formatHelper->addNewsfeed($statusOfUserB,$user1['username'])?>
    </div>
    <?= $formatHelper->ListFriendIndex($_COOKIE['login']) ?>
</div>
<?= $formatHelper->closeFooter() ?>
