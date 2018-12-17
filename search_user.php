<!-- GUEST -->
<?php
require_once 'inc/autoload.php';

// Format Helper
$formatHelper = new FormatHelper();
$users = null;
//$_SERVER['REQUEST_METHOD' => Xác định request gửi đến server con đường nào (post,get,patch,delete)
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $user = new UserController();
    $message = "";//Thông báo KQ từ server trả về

    if (isset($_POST['searchUser'])) {
        $users = $formatHelper->SearchUser((!isset($_POST['name']) ? null: $_POST['name']));
        // echo($users);
    }
    if (count($users) == 0 ){
        $message = "Not found user";
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
        <div class="alert alert-info" <?=@$display ? : "style='display:none; text-align: center;'" ?>><center>
                <?= @$message ? : "" ?>
            </center>
        </div>
        <div class="searchbar">
                <form class="form" method="POST">
                    <!-- SEARCH -->
                    <div class="form-group">
                        <div class="col-sm-10 form-group NoPadding">
                            <input type="search" class="form-control"  placeholder="Nhập tên user" name="name">
                        </div>
                        <!-- BUTTON -->
                        <div class="col-sm-2 frm-group ">
                            <input type="submit" class="form-control btn-success" name = "searchUser">
                        </div>
    
                </form>
            </div>
    </div>
    <?php if ($users != null) {?>
    <div class="container" style="padding-top: 5%; width: 196%;">
	<div class="row">
        <div class="panel panel-default user_panel">
            <div class="panel-heading">
                <h3 class="panel-title">User List</h3>
            </div>
            <div class="panel-body">
				<div class="table-container">
                    <table class="table-users table" border="0">
                           <?=$users?>
                    </table>
                </div>
            </div>
        </div>

    </div>
    </div>
    <?php } ?>
</div>
    <?= $formatHelper->addRightMenu() ?>
</div>
<?= $formatHelper->closeFooter() ?>