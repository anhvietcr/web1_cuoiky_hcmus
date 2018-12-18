<!-- MEMBER -->
<?php
require_once 'inc/autoload.php';

// Format Helper
$formatHelper = new FormatHelper();

// Form Request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user = new UserController();
    $message = $user->register($_POST);
    $display = "style='display: block; text-align: center;'";

    //if ($message == 1) header('Location: dashboard.php');
}

// DIRECTION
// if (isset($_COOKIE['login'])) {
//     header('Location: dashboard.php');
// }
?>

<?= $formatHelper->addHeader('Đăng ký'); ?>
<div class="alert alert-danger" <?= @$display ?: "style='display:none;text-align: center;'"?>> <?= @$message?: "" ?> </div>

<!-- REGISTER -->
<?php      ?>
<form class="frmReg" action="" method="POST">
    <div class="form-group">
        <label for="usename">Email:</label>
        <input type="email" name="username" class="form-control" maxlength="255" required>
    </div>
    <div class="form-group">
        <label for="password">Mật khẩu:</label>
        <input type="password" name="password" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="password">Nhập lại mật khẩu:</label>
        <input type="password" name="re-password" class="form-control" required>
    </div>
    <div class="submit-group">
        <button type="submit" class="btn btn-warning">Đăng ký</button>
        <a href="login.php" title="Đăng nhập hệ thống" target="_parent">Đăng nhập</a>
    </div>
</form>
<?= $formatHelper->closeFooter(); ?>