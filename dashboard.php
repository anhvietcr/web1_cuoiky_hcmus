<!-- GUEST -->
<?php
require_once 'inc/autoload.php';

// Format Helper
$formatHelper = new FormatHelper();
$user = new UserController();

if ($_SERVER['REQUEST_METHOD'] == "POST") {


    if (!empty($_POST['content'])) {
        $user->NewStatus($_COOKIE['login'], $_POST['content']);
        header('Location: '.$_SERVER['PHP_SELF']);
    }
}
if (!empty($_POST['content_comment'])) {
    $user->NewComment($_POST['id_status'],$_COOKIE['login'],$_POST['content_comment']);
  /*  var_dump($_COOKIE['login']);
    var_dump($_POST['id_status']);
    var_dump($_POST['content_comment']);
    //die();
  */
    header('Location: '.$_SERVER['PHP_SELF']);
}
$newsfeed = $user->LoadNewsfeed($_COOKIE['login']);

// DIRECTION
if (!isset($_COOKIE['login'])) {
    header('Location: index.php');
}
?>

<?= $formatHelper->addHeader($_COOKIE['login']) ?>
    <?= $formatHelper->addFixMenu() ?>

    <div class="main">
        <div class="content">
            <?= $formatHelper->addStatus() ?>
            <?= $formatHelper->addNewsfeed($newsfeed) ?>
        </div>

        <?= $formatHelper->addRightMenu() ?>
    </div>
<?= $formatHelper->closeFooter() ?>
