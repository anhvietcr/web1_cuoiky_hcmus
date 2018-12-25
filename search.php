<!-- GUEST -->
<?php
require_once 'inc/autoload.php';

// Format Helper
$formatHelper = new FormatHelper();
$user = new UserController();
$comment = new CommentController();

$currentTab = "All";
$postEntities = null;
$posts = null;
$users = null;

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $message = "";

    if (isset($_POST['keyword']) || isset($_POST['search'])) {
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
        <div id="btnFilters">
            <button class="btn active" onclick="filterSelection('all')"> Show all</button>
            <button class="btn" onclick="filterSelection('users')"> Users</button>
            <button class="btn" onclick="filterSelection('status')"> Status</button>
        </div>
    </div>

    <div class="content" id="users" style="padding: 20px;">
        <?php if ($users != null) {
            echo $users;
        } ?>
    </div>

    <div class="content" id="status" style="padding: 20px;">
        <?php if ($posts != null) {
            echo $posts;
        } ?>
    </div>

</div>

</div>
<?= $formatHelper->ListFriendIndex($_COOKIE['login']) ?>
</div>
<?= $formatHelper->closeFooter() ?>