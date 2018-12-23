<!-- GUEST -->
<?php
require_once 'inc/autoload.php';

// Format Helper
$formatHelper = new FormatHelper();

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $user = new UserController();
    $message = $user->UpdateProfile($_COOKIE['login'], $_FILES, $_POST);
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
        <!-- CHANGE PASSWORD -->
        <form class="frmUpdate" action="" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="phone">Số điện thoại: </label>
                <input type="text" name="phone" class="form-control">
            </div>
            <div class="form-group">
                <label for="realname">Họ tên: </label>
                <input type="text" name="realname" class="form-control">
            </div>
            <div class="form-group">
                <label for="avatar">Ảnh đại diện: </label>
                <input type="file" name="avatar" class="form-control">
            </div>
            <div class="submit-group">
                <button type="submit" class="btn btn-primary">Cập nhật</button>
            </div>
        </form>
    </div>

    <?= $formatHelper->ListFriendIndex($_COOKIE['login']) ?>
</div>
<?= $formatHelper->closeFooter() ?>
