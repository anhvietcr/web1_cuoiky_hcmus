<!-- MEMBER -->
<?php
require_once 'inc/autoload.php';

// Format HTML
$formatHelper = new FormatHelper();

// Form REQUEST
if ($_SERVER['REQUEST_METHOD'] == 'POST')  {
    $user = new UserController();
    $message = $user->login($_POST);
    $display = "style='display: block; text-align: center;'";

    if ($message == 1) header('Location: dashboard.php');
}

// DIRECTION
if (isset($_COOKIE['login'])) {
    header('Location: dashboard.php');
}
?>

<?= $formatHelper->addHeader('Đăng nhập') ?>
<div class="alert alert-danger" <?= @$display ?: "style='display:none; text-align: center;'"?>><center><?= @$message?: "" ?></center></div>

<!-- LOGIN -->
<form class="frmLogin" action="" method="POST">
    <div class="form-group">
        <label for="usename">Email:</label>
        <input type="email" name="username" class="form-control" maxlength="255" required>
    </div>
	<div class="form-group">
		<label for="password">Mật khẩu:</label>
		<input type="password" name="password" class="form-control" required>
	</div>
	<div class="form-group">
		<input type="checkbox" name="remember" class="form-check-input" id="remember">
		<label for="remember">Nhớ mật khẩu</label>
	</div>
       <div class="submit-group">
           <button type="submit" class="btn btn-success">Đăng nhập</button>
           <a href="register.php" title="Đăng ký tài khoản" target="_parent">Đăng ký</a> <br>
           <a href="forgot_password.php" title="Quên mật khẩu" target="_parent">Quên mật khẩu ?</a>
       </div>
</form>
<?= $formatHelper->closeFooter() ?>