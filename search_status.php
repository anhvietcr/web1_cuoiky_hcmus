<!-- GUEST -->
<?php
require_once 'inc/autoload.php';

// Format Helper
$formatHelper = new FormatHelper();
$searchBarType = 1;
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
        <div class="alert alert-info" <?=@$display ? : "style='display:none; text-align: center;'" ?>><center>
                <?= @$message ? : "" ?>
            </center>
        </div>
        <div class="searchbar">
                <form class="form" method="POST">
                    <!-- SEARCH -->
                    <div class="form-group" >
                        <div class="col-sm-7 form-group NoPadding">
                            <input type="search" class="form-control" placeholder="Nhập từ khóa">
                        </div>
                        <!-- CATEGORIES -->
                        <div class="col-sm-3 form-group NoPadding">
                            <select name="Categories" class="form-control">
                                <option value="0">Tất cả trạng thái</option>
                                <option value="1">Công khai</option>
                                <option value="2">Bạn bè</option>
                                <option value="3">Chỉ tôi</option>
                            </select>
                        </div>
                        <!-- BUTTON -->
                        <div class="col-sm-2 frm-group ">
                            <input type="submit" class="form-control btn-success">
                        </div>
                </form>
            </div>
        </div>
    </div>
    <?= $formatHelper->addRightMenu() ?>
</div>
<?= $formatHelper->closeFooter() ?>