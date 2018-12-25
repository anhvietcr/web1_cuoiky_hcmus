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

$user2_avatar = !empty($user2['avatar']) ? 'data:image;base64,'.$user2['avatar'] : "asset/img/non-avatar.png";
$statusOfUserB = $status->ShowStatusWithRelationship($user1['id'],$id_user2);
?>
<?= $formatHelper->addHeader($_COOKIE['login']) ?>
<?= $formatHelper->addFixMenu() ?>
<div class="main">
    <div class="content">
        <div class="user-info">
            <div class="info-title">
                <span><img src="<?= $user2_avatar?>"/></span>
                <span id="name"><?=$user2['realname']?></span>
            </div>
            <div class="info-body">
                <form action="friends.php" method="post">
                    <input type="hidden" name="name" value="<?=$user2['username']?>">
                    <?php
                    if($id_user2 != $id_user1) {
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
                    } else {
                        ?>
                        <select id="options_setting" class="form-control" onchange="location = this.value;">
                            <option value="settings" selected> Cài đặt </option>
                            <option disabled>---------------</option>
                            <option value="change_profile.php">Đổi thông tin</option>
                            <option value="change_password.php">Đổi mật khẩu</option>
                            <option disabled>---------------</option>
                            <option value="logout.php">Đăng xuất</option>
                        </select>
                        <?php
                    }
                    ?>
                </form>
            </div>
        </div>
        <?= $formatHelper->addNewsfeed($statusOfUserB,$user1['username'])?>
    </div>
    <?= $formatHelper->ListFriendIndex($_COOKIE['login']) ?>
</div>
<?= $formatHelper->closeFooter() ?>
