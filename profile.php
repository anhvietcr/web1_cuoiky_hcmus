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

$user2 = $user->GetUser('',$id_user2);

$user2_avatar = !empty($user2['avatar']) ? 'data:image;base64,'.$user2['avatar'] : "asset/img/non-avatar.png";
$statusOfUserB = $status->ShowStatusWithRelationship($user1['id'],$id_user2);
?>
<?= $formatHelper->addHeader($_COOKIE['login']) ?>
<?= $formatHelper->addFixMenu() ?>

<div class="main">

    <div class="content">
        <form action="friend.php" method="post">
            <div class="myView">
                <div class="card" style="width: 35rem;">
                    <input type="hidden" name = "id-user2" value="<?=$id_user2?>">
                    <img class="card-img-top" height="350"src=".<?=$user2_avatar?>">
                    <div class="card-body">
                        <h5 class="card-title">Thông tin</h5>
                    </div>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><strong>Họ tên: </strong> <?=$user2['realname']?></li>
                    </ul>

                </div>
            </div>
        </form>
    </div>

    <?= $formatHelper->addRightMenu() ?>

    <?= $formatHelper->addNewsfeed($statusOfUserB,$user1['username'])?>
</div>
<?= $formatHelper->closeFooter() ?>
