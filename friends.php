<!-- GUEST -->
<?php
require_once 'inc/autoload.php';

// Format Helper
$formatHelper = new FormatHelper();
//$_SERVER['REQUEST_METHOD' => Xác định request gửi đến server con đường nào (post,get,patch,delete)
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $user = new UserController();
    $message = "";//Thông báo KQ từ server trả về

    if (isset($_POST['addFriend'])) {

        $message = $user->AddFriend($_COOKIE['login'], $_POST['name']);
    } else if (isset($_POST['declineFriend'])) {

        $message = $user->DeclineFriend($_COOKIE['login'], $_POST['name']);
    } else if (isset($_POST['acceptFriend'])) {

        $message = $user->AcceptFriend($_COOKIE['login'], $_POST['name']);
    } else if (isset($_POST['unFriend'])) {

        $message = $user->DeleteFriend($_COOKIE['login'], $_POST['name']);
    } else {

        $message = $user->unFollowing($_COOKIE['login'], $_POST['name']);
    }

    $display = "style='display: block; text-align: center;'";
}
// DIRECTION
if (!isset($_COOKIE['login'])) {
    header('Location: index.php');
}
?>

<?= $formatHelper->addHeader($_COOKIE['login']) ?>
<?= $formatHelper->addFixMenu() ?>

<div class="main">
    <div class="content">
        <div class="alert alert-info" <?= @$display ?: "style='display:none; text-align: center;'"?>><center><?= @$message?: "" ?></center></div>

        <!-- FRIENDS LIST & REQUEST -->
        <div class="tabs">
            <div class="tab">
                <input type="radio" id="followed" class="tab-item" name="friend" checked>
                <label for="followed" class="tab-title">Bạn bè</label>
                <div class="tab-content">
                    <ul class="global">
                        <?= $formatHelper->ListFriends($_COOKIE['login']) ?>
                    </ul>
                </div>
            </div>
            <div class="tab">
                <input type="radio" id="follows" class="tab-item" name="friend">
                <label for="follows" class="tab-title">Đang chờ</label>
                <div class="tab-content">
                    <ul class="global">
                        <?= $formatHelper->ListFollows($_COOKIE['login']) ?>
                    </ul>
                </div>
            </div>
            <div class="tab">
                <input type="radio" id="following" class="tab-item" name="friend">
                <label for="following" class="tab-title">Theo dõi</label>
                <div class="tab-content">
                    <ul class="global">
                        <?= $formatHelper->ListFollowing($_COOKIE['login']) ?>
                    </ul>
                </div>
            </div>
            <div class="tab">
                <input type="radio" id="global" class="tab-item" name="friend">
                <label for="global" class="tab-title">Bạn có biết</label>
                <div class="tab-content">
                    <ul class="global">
                        <?= $formatHelper->ListUsers($_COOKIE['login']) ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <?= $formatHelper->addRightMenu() ?>
</div>
<?= $formatHelper->closeFooter() ?>
