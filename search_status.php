<!-- GUEST -->
<?php
require_once 'inc/autoload.php';

// Format Helper
$formatHelper = new FormatHelper();  
$user = new UserController();

$keyWord = '';

// $posts = null;
//$_SERVER['REQUEST_METHOD' => Xác định request gửi đến server con đường nào (post,get,patch,delete)
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $message = "";//Thông báo KQ từ server trả về

    // if (isset($_POST['searchStatus'])) {
    //     // echo($users);
    // }


    if (!empty($_POST['content_comment'])) {
        $user->NewComment($_POST['id_status'],$_COOKIE['login'],$_POST['content_comment']);
        header('Location: '.$_SERVER['PHP_SELF']);
        $posts = $user->SearchPosts($_COOKIE['login'], (!isset($_POST['keyWord']) ? null : $_POST['keyWord']));
    }
}

$posts = $user->SearchPosts($_COOKIE['login'], (!isset($_POST['keyWord']) ? null : $_POST['keyWord']));
// if (count($posts) == 0) {
//     $message = "Not found posts";
//     $display = "style='display: block; text-align: center;'";
// }

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
                        <div class="col-sm-10 form-group NoPadding">
                            <input type="search" class="form-control" placeholder="Nhập từ khóa" name="keyWord" value="<?= $keyWord; ?>">
                        </div>
                        <!-- BUTTON -->
                        <div class="col-sm-2 frm-group ">
                            <input type="submit" class="form-control btn-success" name ="searchStatus">
                        </div>
                </form>
            </div>
        </div>    
        <div class="content" style="width: 100%;">
        <?php if ($posts != null) {
            echo ($formatHelper->addNewsfeed($posts, $_COOKIE['login']));
        } ?>
    </div>
    </div>
    <?= $formatHelper->addRightMenu() ?>
</div>
<?= $formatHelper->closeFooter() ?>