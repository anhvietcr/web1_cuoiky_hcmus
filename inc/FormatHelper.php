<?php
ini_set("display_errors", 1);
error_reporting(E_ALL);
include_once 'autoload.php';

/*
 * Class Help define template HTML
 */
class FormatHelper
{
    private $header;
    private $footer;
    private $fixmenu;
    private $rightMenu;
    private $status;
    private $newsfeed;
    private $friend;
    private $frmResetPassword;
    private $frmNewPassword;

    //Function format header with custom title
    public function addHeader($title)
    {
        $this->header =<<<HEADER
<!DOCTYPE html>
<html lang="vn">
<head>
    <title> $title </title>
    <meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="asset/style.css">
	<link rel="stylesheet" type="text/css" href="plugins/bootstrap/css/bootstrap.css">
</head>
<body>
<div class="container">
HEADER;
        return $this->header;
    }

    //Function format footer
    public function closeFooter()
    {
        $this->footer =<<<FOOTER
</div>
<div class="copyright">
    <p>Anhvietcr | 1660765 | HCMUS</p>       
</div>
</body>
</html>
FOOTER;
        return $this->footer;
    }

    public function addFixMenu()
    {
        $user = new UserController();
        $usr = $user->GetUser($_COOKIE['login'], '');
        $name = empty($usr['realname']) ? $usr['username'] : $usr['realname'];

        // new request friend
        $follows = !empty($usr['follows']) ? unserialize($usr['follows']) : [];
        $count = count($follows);
        $req = $count > 0 ? "<span id='new-request'>+$count</span>" : "";

        $this->fixmenu =<<<FIXMENU
<div class="fix-menu">
        <div id="icon">
            <a href="index.php"><img src="asset/img/home.png" alt="home"></a>
        </div>
        <ul id="nav">
            <li><a href="dashboard.php">( $name )</a></li>
            <li><a href="friends.php">Bạn bè $req </a></li>
            <li><a href="logout.php">Đăng xuất</a></li>
        </ul>
        <div class="clear"></div>
    </div>
FIXMENU;
        return $this->fixmenu;
    }

    public function addRightMenu()
    {
        $this->rightMenu = <<<RIGHTMENU
        <div class="right-menu">
            <ul class="sub-menu">
                <strong>Cá nhân</strong>
                <li><span id="licon"></span><a href="change_profile.php">Đổi thông tin</a></li>
                <li><span id="licon"></span><a href="change_password.php">Đổi mật khẩu</a></li>
                <li><span id="licon"></span><a href="logout.php">Đăng xuất</a></li>
            </ul>
        </div>
RIGHTMENU;
        return $this->rightMenu;
    }

    public function addStatus()
    {
        $this->status =<<<STATUS
<div class="status">
    <form action="" method="POST">
        <textarea rows='2' placeholder='Viết gì đó ...' class="content" name="content"></textarea>
        <input type="submit" name="addStatus" class="btn btn-success center-block" value="Đăng">
    </form>
</div>
STATUS;
        return $this->status;
    }

    public function addNewsfeed($contents)
    {
        $user = new UserController();
        foreach ($contents as $content) {
            // real-name & avatar
            $usr = $user->GetUser('', $content['id_user']);
            $name = empty($usr['realname']) ? $usr['username'] : $usr['realname'];
            $src = !empty($usr['avatar']) ? 'data:image;base64,'.$usr['avatar'] : "asset/img/non-avatar.png";

            // content html
            $this->newsfeed .= "<div class='newsfeed'><div class='new'><div class='new-title'>";
            $this->newsfeed .= "<img src='$src' alt='logo'>";
            $this->newsfeed .= "<h4 id='user'>$name</h4>";
            $this->newsfeed .= "<i>$content[created]</i></div>";
            $this->newsfeed .= "<div class='new-content'>$content[content]</div></div></div>";
        }
        return $this->newsfeed;
    }

    public function ListUsers($username)
    {
        $this->friend = "";
        $user = new UserController();
        $users = $user->ListUsers();

        // get list follow of user
        $info = $user->GetUser($username);
        $followed = !empty($info['followed']) ? unserialize($info['followed']) : [];
        $following = !empty($info['following']) ? unserialize($info['following']) : [];
        $follows = !empty($info['follows']) ? unserialize($info['follows']) : [];

        foreach ($users as $usr) {
            if ($username === $usr['username']) continue;

            if (in_array($usr['id'], $followed) || in_array($usr['id'], $follows) || in_array($usr['id'], $following)) continue;

            // real-name & avatar
            $name = !empty($usr['realname']) ? $usr['realname'] : $usr['username'];
            $src = !empty($usr['avatar']) ? 'data:image;base64,'.$usr['avatar'] : "asset/img/non-avatar.png";

            //content html
            $this->friend .= "<li><form action='' method='POST'>";
            $this->friend .= "<img src='$src' alt='avatar'>";
            $this->friend .= "<h2>$name</h2>";
            $this->friend .= "<input name='name' value='$usr[username]' hidden>";
            $this->friend .= "<button class='btn btn-primary' name='addFriend'>Thêm bạn bè</button></form></li>";
        }
        return $this->friend;
    }

    public function ListFriends($username)
    {
        $this->friend = "";
        $user = new UserController();
        $users = $user->ListFriends($username, 'followed');

        foreach ($users as $usr) {
            // real-name & avatar
            $name = !empty($usr['realname']) ? $usr['realname'] : $usr['username'];
            $src = !empty($usr['avatar']) ? 'data:image;base64,'.$usr['avatar'] : "asset/img/non-avatar.png";

            //content html
            $this->friend .= "<li><form action='' method='POST'>";
            $this->friend .= "<img src='$src' alt='avatar'>";
            $this->friend .= "<h2>$name</h2>";
            $this->friend .= "<input name='name' value='$usr[username]' hidden>";
            $this->friend .= "<button class='btn btn-danger center-block' name='unFriend'>Bỏ kết bạn</button></form></li>";
        }
        return $this->friend;
    }

    public function ListFollows($username)
    {
        $this->friend = "";
        $user = new UserController();
        $users = $user->ListFriends($username, 'follows');

        foreach ($users as $usr) {
            // real-name & avatar
            $name = !empty($usr['realname']) ? $usr['realname'] : $usr['username'];
            $src = !empty($usr['avatar']) ? 'data:image;base64,'.$usr['avatar'] : "asset/img/non-avatar.png";

            //content html
            $this->friend .= "<li><form action='' method='POST'>";
            $this->friend .= "<img src='$src' alt='avatar'>";
            $this->friend .= "<h2>$name</h2>";
            $this->friend .= "<input name='name' value='$usr[username]' hidden>";
            $this->friend .= "<div class='submit-group'><button class='btn btn-success btn-block' name='acceptFriend'>Chấp nhận</button>";
            $this->friend .= "<button class='btn btn-danger btn-block' name='declineFriend'>Từ chối</button></div></form></li>";
        }
        return $this->friend;
    }

    public function ListFollowing($username)
    {
        $this->friend = "";
        $user = new UserController();
        $users = $user->ListFriends($username, 'following');

        foreach ($users as $usr) {
            // real-name & avatar
            $name = !empty($usr['realname']) ? $usr['realname'] : $usr['username'];
            $src = !empty($usr['avatar']) ? 'data:image;base64,'.$usr['avatar'] : "asset/img/non-avatar.png";

            //content html
            $this->friend .= "<li><form action='' method='POST'>";
            $this->friend .= "<img src='$src' alt='avatar'>";
            $this->friend .= "<h2>$name</h2>";
            $this->friend .= "<input name='name' value='$usr[username]' hidden>";
            $this->friend .= "<button class='btn btn-warning' name='unFollowing'>Bỏ theo dõi</button></form></li>";
        }
        return $this->friend;
    }

    public function addResetPassword()
    {
        $this->frmResetPassword =<<<FORM_RESET_PASSWORD
<form class="frmLogin" action="" method="POST">
    <div class="form-group">
        <label for="usename">Gửi mật khẩu qua mail:</label>
        <input type="email" name="username" class="form-control" maxlength="50" required>
    </div>
    <div class="submit-group">
        <button type="submit" class="btn btn-primary">Gửi</button>
        <a href="login.php" title="Đăng nhập" target="_parent">Đăng nhập</a>
    </div>
</form>

FORM_RESET_PASSWORD;
        return $this->frmResetPassword;
    }

    public function addNewPassword()
    {
        $this->frmNewPassword =<<<FORM_NEW_PASSWORD
<!-- NEW PASSWORD -->
<form class="frmLogin" action="" method="POST" name="new">
    <div class="form-group">
        <label for="pasword">Mật khẩu mới:</label>
        <input type="password" name="password" class="form-control" maxlength="255" required>
    </div>
    <div class="submit-group">
        <button type="submit" class="btn btn-primary">Đổi mật khẩu</button>
    </div>
</form>

FORM_NEW_PASSWORD;
        return $this->frmNewPassword;
    }

    public function addAlert($display = 'none', $style = 'danger', $message = '')
    {
        return "<div class='alert alert-$style' style='$display'><center>$message</center></div>";
    }
}