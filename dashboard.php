<!-- GUEST -->
<?php
require_once 'inc/autoload.php';

// Format Helper
$formatHelper = new FormatHelper();
$user = new UserController();
$comment = new CommentController();
// DIRECTION
if (!isset($_COOKIE['login'])) {
    header('Location: index.php');
}
if ($_SERVER['REQUEST_METHOD'] == "POST") {

    if (isset($_POST['addStatus'])) {
        $user->NewStatus($_COOKIE['login'], $_FILES, $_POST);
        header('Location: '.$_SERVER['PHP_SELF']);
    }
}

$newsfeed = $user->LoadNewsfeed($_COOKIE['login']);

?>

<?= $formatHelper->addHeader($_COOKIE['login']) ?>
    <?= $formatHelper->addFixMenu() ?>

    <div class="main">
        <div class="content">
            <?= $formatHelper->addStatus() ?>
            <?= $formatHelper->addNewsfeed($newsfeed,$_COOKIE['login']) ?>
        </div>

        <?= $formatHelper->addRightMenu() ?>
    </div>
<?= $formatHelper->closeFooter() ?>
