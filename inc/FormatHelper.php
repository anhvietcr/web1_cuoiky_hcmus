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
    private $users;
    private $posts;
    private $frmResetPassword;
    private $frmNewPassword;
    private $commentForm;


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
    <link rel="stylesheet" type="text/css" href="asset/search/searchBar.css">
    <link rel="stylesheet" type="text/css" href="plugins/bootstrap/css/bootstrap.css">
    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
    <script src="https://use.fontawesome.com/1e803d693b.js"></script>
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
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
    <p>FIT @ F5-- @ HCMUS</p>       
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
//Here doc viết html vẫn giữ format
        $this->fixmenu =<<<FIXMENU
<div class="fix-menu">
        <div id="icon">
            <a href="index.php"><img src="asset/img/home.png" alt="home"></a>
        </div>
        <ul id="nav">
            <li><a href="search_status.php">Tìm status</a></li>
            <li><a href="search_user.php">Tìm user </a></li>
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

          <select class="form-control" id="sel1" name = "role">
            <option>Công khai</option>
            <option>Bạn bè</option>
            <option>Chỉ mình tôi</option>
          </select>
    </form>
</div>
STATUS;
        return $this->status;
    }
//Trang làm nè: tạo Giao diện để comment
    public function addCommentForm($id_status)
    {
        $this->commentForm =<<<COMMENT
<div class="comment-form">
    <form action="" method="POST">
        <input name='id_status' value='$id_status' hidden>
        <textarea rows='2' name="content_comment" placeholder='Viết bình luận ...'></textarea>
        <input type="submit" name="addComment" class="btn btn-primary center-block" value="Đăng">
    </form>
</div>
COMMENT;
        return $this->commentForm;
    }

    public function addNewsfeed($contents,$username)
    {

        $user = new UserController();
        $comment = new CommentController();
        $currentUser = $user->GetUser($username);
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
            $this->newsfeed .= "<div class='new-content'>$content[content]</div>";
            //$this->newsfeed .= "<div class='newsfeed'><div class='new'><div class='new-title'>";
            //Comment form (Trang)
            $id_status = $content['id'];
            $commentForm = $this->addCommentForm($id_status);

            $currentAvatar = !empty($currentUser['avatar']) ? 'data:image;base64,'.$currentUser['avatar'] : "asset/img/non-avatar.png";
            $this->newsfeed .= "<img src='$currentAvatar' alt='logo' width='30px' height='30px'>";
            $this->newsfeed.= $commentForm."</div></div>";

            //show comment Giao diện (Trang)
            $comments = $comment->CommentWithIdStatus($id_status);
            foreach ($comments as $row)
            {
               $userComment = $user->GetUser('',$row['id_user_comment']);

                $contentComment = $row['content'];

                $avatarUserComment = !empty($userComment['avatar']) ? 'data:image;base64,'.$userComment['avatar'] : "asset/img/non-avatar.png";
                $nameComment = $userComment['realname'];
                $this->newsfeed .= "<div class='show-comment'>";
                $this->newsfeed.= "<img src='$avatarUserComment' alt='logo' width='30px' height='30px'>";
                $this->newsfeed.= "$nameComment";
                $this->newsfeed.= " $contentComment.</div>";
            }

        }
        return $this->newsfeed;
    }
    //Trang
    public function showComment($contents)
    {

        foreach ($contents as $content) {
            // real-name & avatar
            var_dump($content);

        }
        die();
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

    public function SearchUser($nameKey) {
        $user = new UserController();
        $listUser = $user->ListUsers();
        if(count($listUser) == 0) {
            return null;
        }

        foreach ($listUser as $usr) {
            
            if ($nameKey !== '') {
                if (strpos($usr['realname'], $nameKey) === false && strpos($usr['username'], $nameKey) === false) {
                    continue;
                }
            }

            // real-name & avatar
            $name = !empty($usr['realname']) ? $usr['realname'] : $usr['username'];
            $src = !empty($usr['avatar']) ? 'data:image;base64,'.$usr['avatar'] : "asset/img/non-avatar.png";
            $this->users .= '<tr><td width="10">';
            $this->users .= '<img class="pull-left img-circle nav-user-photo" width="50" src="'. $src .'" /> ';
            $this->users .= '</td><td>';
            $this->users .= $name ;
            $this->users .= '</td><td align="left">';
            $this->users .= $usr['username'] . '</td>';
            $this->users .= '<td>Tham gia: <i>'. $usr['created'] .'</i></td>';
            $this->users .= '</tr>';
        }
        return $this->users;
    }

    public function SearchStatus($username, $keyWord) {

    }
}