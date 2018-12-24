<!-- GUEST -->
<?php
require_once 'inc/autoload.php';

// Format Helper
$formatHelper = new FormatHelper();
$user = new UserController();
$comment = new CommentController();

$currentTab = "All";
$keyWord = '';
$postEntities = null;
$posts = null;
$users = null;

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $message = "";//Thông báo KQ từ server trả về


    if (isset($_POST['keyword'])) {
        $users = $formatHelper->SearchUser((!isset($_POST['keyword']) ? null : $_POST['keyword']));
        $postEntities = $user->SearchPosts($_COOKIE['login'], (!isset($_POST['keyword']) ? null : $_POST['keyword']));
        $posts = $formatHelper->addNewsfeed($postEntities, $_COOKIE['login']);
    }

    if (count($posts) == 0 && count($users) == 0) {
        $message = "Not found!!";
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
        <div class="w3-bar w3-white">
                <button class="w3-bar-item w3-button tablink w3-red" onclick="openSearchFilter(event,'All')">All</button>
                <button class="w3-bar-item w3-button tablink" onclick="openSearchFilter(event,'Status')">Status</button>
                <button class="w3-bar-item w3-button tablink" onclick="openSearchFilter(event,'Users')">Users</button>
        </div>
    </div>

    <div id="All" class="w3-container w3-border filter">
        <div class="content" style="padding: 20px;">
            <?php if ($users != null) {
                echo $users;
            } ?>
        </div>

        <div class="content" style="padding: 20px;">
            <?php if ($posts != null) {
                echo $posts;
            } ?>
        </div>
    </div>
    <div id="Users" class="w3-container w3-border filter" style="display:none">
        <div class="content" style="padding: 20px;">
            <?php if ($users != null) {
                echo $users;
            } ?>
        </div>
    </div>
    <div id="Status" class="w3-container w3-border filter" style="display:none">
        <div class="content" style="padding: 20px;">
            <?php if ($posts != null) {
                echo $posts;
            } ?>
        </div>
    </div>

</div>

</div>
<?= $formatHelper->ListFriendIndex($_COOKIE['login']) ?>
</div>
<?= $formatHelper->closeFooter() ?>