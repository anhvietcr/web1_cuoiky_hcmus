<!-- GUEST -->
<?php
require_once 'inc/autoload.php';

// Format Helper
$formatHelper = new FormatHelper();
$user = new UserController();
$currentTab = "All";
$keyWord = '';
$posts = null;
$users = null;

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $message = "";//Thông báo KQ từ server trả về


    if (isset($_POST['keyword'])) {
        if ($currentTab === "All") {
            $users = $formatHelper->SearchUser((!isset($_POST['keyword']) ? null : $_POST['keyword']));
            $posts = $user->SearchPosts($_COOKIE['login'], (!isset($_POST['keyword']) ? null : $_POST['keyword']));
        } else if ($currentTab === "Status") {
            $posts = $user->SearchPosts($_COOKIE['login'], (!isset($_POST['keyword']) ? null : $_POST['keyword']));
        } else if ($currentTab === "Users") {
            $users = $formatHelper->SearchUser((!isset($_POST['keyword']) ? null : $_POST['keyword']));
        }
    }

    if (count($posts) == 0 && count($users) == 0) {
        $message = "Not found!!";
        $display = "style='display: block; text-align: center;'";
    }


    if (!empty($_POST['content_comment'])) {
        $user->NewComment($_POST['id_status'], $_COOKIE['login'], $_POST['content_comment']);
        header('Location: ' . $_SERVER['PHP_SELF']);
        $posts = $user->SearchPosts($_COOKIE['login'], (!isset($_POST['keyWord']) ? null : $_POST['keyWord']));
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
        <div class="w3-bar w3-white" style="left: 10%; color: rgb(255, 255, 255);">
                <button class="w3-bar-item w3-button tablink w3-red" onclick="openSearchFilter(event,'All')">All</button>
                <button class="w3-bar-item w3-button tablink" onclick="openSearchFilter(event,'Status')">Status</button>
                <button class="w3-bar-item w3-button tablink" onclick="openSearchFilter(event,'Users')">Users</button>
        </div>
    </div>

    <div id="All" class="w3-container w3-border filter" style="display:none">
        <div class="content" style="padding: 20px;">
            <?php if ($users != null) { ?>
                <?= $users ?>
                <?php 
            } ?>
        </div>

        <div class="content" style="padding: 20px;">
            <?php if ($posts != null) {
                echo ($formatHelper->addNewsfeed($posts, $_COOKIE['login']));
            } ?>
        </div>
    </div>
    <div id="Users" class="w3-container w3-border filter" style="display:none">
        <div class="content" style="padding: 20px;">
            <?php if ($users != null) { ?>
                <?= $users ?>
                <?php 
            } ?>
        </div>
    </div>
    <div id="Status" class="w3-container w3-border filter" style="display:none">
        <div class="content" style="padding: 20px;">
            <?php if ($posts != null) {
                echo ($formatHelper->addNewsfeed($posts, $_COOKIE['login']));
            } ?>
        </div>
    </div>

</div>

</div>
<?= $formatHelper->ListFriendIndex($_COOKIE['login']) ?>
</div>
<?= $formatHelper->closeFooter() ?>