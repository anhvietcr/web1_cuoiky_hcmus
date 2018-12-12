<!-- MEMBER -->
<?php
require_once 'inc/autoload.php';

$display = 'display: none';
$message = '';
$style = 'danger';

// Format HTML
$formatHelper = new FormatHelper();
$contentHTML = $formatHelper->addResetPassword();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['username'])) {
    /*
     * Request forgot password
     * */
    $display = "display: block; text-align: center;";

    //check exists username
    $users = new UserController();
    $user = $users->GetUser($_POST['username']);

    if (!$user) {
        $message = "Không tồn tại email trên hệ thống";
    } else {
        $work = new ForgotPasswordController();
        $message = $work->SendPasswordToEmail($_POST['username']);
        $style = 'success';
        $contentHTML = '';
    }
} else if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['token'])) {
    /*
     * Request from token
     * */
    $token = filter_input(INPUT_GET, 'token');

    $work = new ForgotPasswordController();
    $message = $work->ValidateToken($token);

    if ($message === true) {
        $contentHTML = $formatHelper->addNewPassword();
        $display = 'display: none';
    } else {
        $contentHTML = '';
        $display = "display: block; text-align: center;";
        $style = "warning";
    }
} else if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['password']) && isset($_GET['token'])) {
    /*
     * Request new password
     * */
    $work = new ForgotPasswordController();
    $message = $work->ChangePassword($_GET['token'], $_POST['password']);

    header('Location: login.php');
    die();
}
// DIRECTION
if (isset($_COOKIE['login'])) {
    header('Location: dashboard.php');
}
?>

<?=
    $formatHelper->addHeader('Quên mật khẩu');
    echo $formatHelper->addAlert($display, $style, $message);
    echo $contentHTML;
    $formatHelper->closeFooter();
?>
