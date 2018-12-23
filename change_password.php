<!-- GUEST -->
<?php
require_once 'inc/autoload.php';

// Format Helper
$formatHelper = new FormatHelper();

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $user = new UserController();
    $message = $user->ChangePassword($_COOKIE['login'], $_POST);
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
        <form class="frmUpdate" action="" method="POST">
            <div class="form-group">
                <label for="old-password">Mật khẩu cũ</label>
                <input type="text" name="old-password" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="new-password">Mật khẩu mới:</label>
                <input type="text" name="new-password" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="renew-password">Nhập lại mật khẩu mới:</label>
                <input type="text" name="renew-password" class="form-control" required>
            </div>
            <div class="submit-group">
                <button type="submit" class="btn btn-primary">Cập nhật</button>
            </div>
        </form>
    </div>

    <?= $formatHelper->ListFriendIndex($_COOKIE['login']) ?>
</div>
<?= $formatHelper->closeFooter() ?>
