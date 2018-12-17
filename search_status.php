<!-- GUEST -->
<?php
require_once 'inc/autoload.php';

// Format Helper
$formatHelper = new FormatHelper();
$posts = null;
//$_SERVER['REQUEST_METHOD' => Xác định request gửi đến server con đường nào (post,get,patch,delete)
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $user = new UserController();
    $message = "";//Thông báo KQ từ server trả về

    if (isset($_POST['searchStatus'])) {
        $posts = $user->SearchPosts($_COOKIE['login'], $_POST['permissions'], (!isset($_POST['keyWord']) ? null : $_POST['keyWord']));
        // echo($users);

    }
    if (count($posts) == 0) {
        $message = "Not found posts";
        $display = "style='display: block; text-align: center;'";
    }
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
        <div class="alert alert-info" <?= @$display ? : "style='display:none; text-align: center;'" ?>><center>
                <?= @$message ? : "" ?>
            </center>
        </div>
        <div class="searchbar">
                <form class="form" method="POST">
                    <!-- SEARCH -->
                    <div class="form-group" >
                        <div class="col-sm-7 form-group NoPadding">
                            <input type="search" class="form-control" placeholder="Nhập từ khóa" name="keyWord">
                        </div>
                        <!-- CATEGORIES -->
                        <div class="col-sm-3 form-group NoPadding">
                            <select name="permissions" class="form-control">
                                <option value="1">Công khai</option>
                                <option value="2">Bạn bè</option>
                                <option value="3">Chỉ tôi</option>
                            </select>
                        </div>
                        <!-- BUTTON -->
                        <div class="col-sm-2 frm-group ">
                            <input type="submit" class="form-control btn-success" name ="searchStatus">
                        </div>
                </form>
            </div>
        </div>    
        <div class="container" style="padding-top: 10%; width: 100%;">
        <?php if ($posts != null) {
            echo ($formatHelper->addNewsfeed($posts));
        } ?>
    </div>
    </div>
    <?= $formatHelper->addRightMenu() ?>
</div>
<?= $formatHelper->closeFooter() ?>