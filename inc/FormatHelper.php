<?php
ini_set("display_errors", 1);
error_reporting(E_ALL);
include_once 'autoload.php';

/*
 * Class Help define
 *
 *  template HTML
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

    /**
     * Header
     * @param [type] $title [description]
     */
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
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" integrity="sha384-gfdkjb5BdAXd+lj+gudLWI+BXq4IuLW5IT+brZEZsLFm++aCMlF1V92rMkPaX4PP" crossorigin="anonymous">
    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.1.1.min.js">
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
</head>
<body>
<div class="container-fluid">
HEADER;
        return $this->header;
    }

    /**
     * Footer
     * @return [type] [description]
     */
    public function closeFooter()
    {
        $this->footer =<<<FOOTER
</div>
<div class="copyright">
    <span>HCMUS | Team F5--</span>       
</div>
<script src="asset/js/linkpreview.js" defer></script>
<script src="asset/js/dashboard.js" defer></script>
<script src="asset/js/comment.js" defer></script>
</body>
</html>
FOOTER;
        return $this->footer;
    }

    /**
     * Giao diện Navbar
     */
    public function addFixMenu()
    {
        $user = new UserController();
        $usr = $user->GetUser($_COOKIE['login'], '');
        $id =$usr['id'];
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
        <li><a href="profile.php?id=$id">( $name )</a></li>
        <li><a href="friends.php">Bạn bè $req </a></li>
        <li><a href="logout.php">Đăng xuất</a></li>
    </ul>
    <div class="clear"></div>
</div>
FIXMENU;
        return $this->fixmenu;
    }

    /**
     * Giao diện Menu bên phải
     */
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

    /**
     * Giao diện from Viết status
     */
    public function addStatus()
    {
            // <label for="image">Hình ảnh: </label>
        $this->status =<<<STATUS
<div class="status">
    <form action="" method="POST" enctype="multipart/form-data">
        <textarea rows='6' placeholder='Viết gì đó ...' class="content" name="content"></textarea>

        <div class="status-extra-content">
            <hr>
            <input type="file" name="image" class="form-control" id="status-image">
            <button class="btn btn-default" id="status-image-btn"><i class="far fa-image fa-2x"></i></button>

            <select class="form-control" id="sel1" name = "role">
                <option>Công khai</option>
                <option>Bạn bè</option>
                <option>Chỉ mình tôi</option>
            </select>
            <button name="addStatus" class="btn btn-primary center-block" value="" id="btnSubmit">Đăng</button>
        </div>
    </form>
</div>
STATUS;
        return $this->status;
    }


    /**
     * Thêm newsfeed (status + comment)
     * @param [type] $contents [description]
     * @param [type] $username [description]
     */
    public function addNewsfeed($contents,$username)
    {

        $user = new UserController();
        $comment = new CommentController();
        $currentUser = $user->GetUser($username);


        foreach ($contents as $content) {

            // real-name & avatar
            $usr = $user->GetUser('', $content['id_user']);
            $id_user = $usr['id'];
            $name = empty($usr['realname']) ? $usr['username'] : $usr['realname'];
            $src = !empty($usr['avatar']) ? 'data:image;base64,'.$usr['avatar'] : "asset/img/non-avatar.png";

            // image attach in status
            $imageAttach = !empty($content['image']) ? "<img src=$content[image] class='image_status'><br>" : "";

            //Comment form (Trang)
            $id_status = $content['id'];

            // avatar comment
            $currentAvatar = !empty($currentUser['avatar']) ? 'data:image;base64,'.$currentUser['avatar'] : "asset/img/non-avatar.png";

            // role status
            $role = "";
            if (strcmp($content['role'], 'Công khai') == 0)
                $role = '<span class="fas fa-globe-asia"></span>';
            if (strcmp($content['role'], 'Bạn bè') == 0)
                $role = '<span class="fas fa-user"></span>';
            if (strcmp($content['role'], 'Chỉ mình tôi') == 0)
                $role = '<span class="far fa-eye-slash"></span>';

            // like or unlike
            $like = "";
            $userIsLike = 1;
            if ($userIsLike)
                $like = "<li id='reaction-like'>&nbsp;Like</li>";
            else 
                $like = "<li id='reaction-unlike'>&nbsp;UnLike</li>";

            $comments = $comment->CommentWithIdStatus($id_status);
            $numberComment = count($comments) > 0 ? "(<span id=numcom-$content[id]>". count($comments) ."</span>)" : "<span id=numcom-$content[id]></span>";

            // content status html
            $this->newsfeed .=<<<NEWSFEED
<div class="newsfeed">
    <a name="$content[id]"></a>
    <div class="new" id="$content[id]">

        <!-- Status -->
        <div class='new-title'>
            <img src='$src' alt='logo'> 
            <h4 id='user'><a href="profile.php?id=$id_user">$name</a></h4>
            <span>&nbsp;&nbsp;$role</span>
            <i>$content[created]</i>
        </div>
        <div class='new-content'>$content[content]</div>
        $imageAttach

        <!-- Reaction Button -->
        <hr style="width: 97%">
        <div class="reaction">
            <ul>
                <li class="reaction-like" id="reaction-like-$content[id]">&nbsp;Like</li>
                <li class="reaction-comment" id="reaction-comment-$content[id]">&nbsp;Comment $numberComment</li>
                <li class="reaction-share" id="reaction-share-$content[id]">&nbsp;Share</li>
            </ul>
        </div>

        <!-- Comment -->
        <hr>
        <div class="hide-comment-status" id="comment-status-$content[id]">
            <div class="new-comment">
                <span id="icon"><img src='$currentAvatar' alt='logo'></span>
                <span id="comment">
                    <form action="#" method="POST" class="frmComment" id="frmComment-$content[id]">
                        <input name='username' value='$_COOKIE[login]' hidden>
                        <input name='type' value='new_status' hidden>
                        <input name='id_status' value='$content[id]' hidden>
                        <input type="text" name="content_comment" class="content_comment" placeholder="Viết bình luận ..." value="" id="content_comment_$content[id]">
                        <button name="addComment" class="btn btn-primary center-block" style="display: none">Đăng</button>
                    </form>
                </span>
            </div>
            <div class="show-comment" id="show-comment-$content[id]">
NEWSFEED;

            //show comment
            foreach ($comments as $row)
            {
                $userComment = $user->GetUser('', $row['id_user_comment']);
                $id_user_comment = $userComment['id'];
                $contentComment = $row['content'];
                $avatarUserComment = !empty($userComment['avatar']) ? 'data:image;base64,'.$userComment['avatar'] : "asset/img/non-avatar.png";
                $nameComment = $userComment['realname'];


                // content html comment
                $this->newsfeed .=<<<COMMENTS
    <div class="detail-comment">
        <span id="icon">
            <img src='$avatarUserComment' alt='icon'>
        </span>
        <span id="content">
            <span id="user-comment">
                <a href="profile.php?id=$id_user_comment"> $nameComment </a>
            </span>
            <span id="content-commment">
                $contentComment
            </span> 
        </span>
    </div>
COMMENTS;
            }

            $this->newsfeed.= "</div></div></div></div>";
        }
        return $this->newsfeed;
    }

    public function ListFriendIndex($username)
    {
        $this->friend = "";
        $user = new UserController();
        $users = $user->ListFriends($username, 'followed');


        $this->friend .=<<<FRIENDSINDEX
<div class="listfriend">
    <div class="content">
        <ul>
FRIENDSINDEX;

        foreach ($users as $usr) {
            // real-name & avatar
            $name = !empty($usr['realname']) ? $usr['realname'] : $usr['username'];
            $src = !empty($usr['avatar']) ? 'data:image;base64,'.$usr['avatar'] : "asset/img/non-avatar.png";
            $id = $usr['id'];

            $this->friend .=<<<FRIENDSINDEX
<li>
    <span id="ficon"><img src=$src alt="."></span>
    <span><a href="profile.php?id=$id">$name</a></span>
    <span id="onoff"></a></span>
</li>
FRIENDSINDEX;
        }

        $this->friend .=<<<FRIENDSINDEX
        </ul>
    </div>
</div>
FRIENDSINDEX;


        return $this->friend;
    }


    /**
     * Giao diện liệt kê tất cả user hiện có
     * @param [type] $username [description]
     */
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
            $id = $usr['id'];
            //content list user html
            $this->friend .=<<<LISTUSER
<li>
    <form action="" method="POST">
        <img src=$src alt="avatar">
        <h2><a href="profile.php?id=$id">$name</a></h2>
        <input name="name" value=$usr[username] hidden>
        <button class='btn btn-primary' name='addFriend'>Thêm bạn bè</button>
    </form>
</li>
LISTUSER;
        }

        return $this->friend;
    }

    /**
     * Giao diện liệt kê tất cả Friend hiện có
     * @param [type] $username [description]
     */
    public function ListFriends($username)
    {
        $this->friend = "";
        $user = new UserController();
        $users = $user->ListFriends($username, 'followed');

        foreach ($users as $usr) {
            // real-name & avatar
            $name = !empty($usr['realname']) ? $usr['realname'] : $usr['username'];
            $src = !empty($usr['avatar']) ? 'data:image;base64,'.$usr['avatar'] : "asset/img/non-avatar.png";
            $id = $usr['id'];
            //content list friends html
            $this->friend .=<<<LISTFRIEND
<li>
    <form action="" method="POST">
        <img src=$src alt="avatar">
        <h2><a href="profile.php?id=$id">$name</a></h2>
        <input name="name" value=$usr[username] hidden>
        <button class='btn btn-danger center-block' name='unFriend'>Bỏ kết bạn</button>
    </form>
</li>
LISTFRIEND;
        }

        return $this->friend;
    }

    /**
     * Giao diện liệt kê tất cả Người đang theo dõi mình
     * @param [type] $username [description]
     */
    public function ListFollows($username)
    {
        $this->friend = "";
        $user = new UserController();
        $users = $user->ListFriends($username, 'follows');

        foreach ($users as $usr) {
            // real-name & avatar
            $name = !empty($usr['realname']) ? $usr['realname'] : $usr['username'];
            $src = !empty($usr['avatar']) ? 'data:image;base64,'.$usr['avatar'] : "asset/img/non-avatar.png";
            $id = $usr['id'];
            //content list Follows html
            $this->friend .=<<<LISTFRIEND
<li>
    <form action="" method="POST">
        <img src=$src alt="avatar">
        <h2><a href="profile.php?id=$id">$name</a></h2>
        <input name="name" value=$usr[username] hidden>
        <div class='submit-group'>
            <button class='btn btn-success btn-block' name='acceptFriend'>Chấp nhận</button>
            <button class='btn btn-danger btn-block' name='declineFriend'>Từ chối</button>
        </div>
    </form>
</li>
LISTFRIEND;
        }

        return $this->friend;
    }

    /**
     * Giao diện liệt kê tất cả người mà mình đang theo dõi
     * @param [type] $username [description]
     */
    public function ListFollowing($username)
    {
        $this->friend = "";
        $user = new UserController();
        $users = $user->ListFriends($username, 'following');

        foreach ($users as $usr) {
            // real-name & avatar
            $name = !empty($usr['realname']) ? $usr['realname'] : $usr['username'];
            $src = !empty($usr['avatar']) ? 'data:image;base64,'.$usr['avatar'] : "asset/img/non-avatar.png";
            $id = $usr['id'];
            //content list Following html
            $this->friend .=<<<LISTFRIEND
<li>
    <form action="" method="POST">
        <img src=$src alt="avatar">
        <h2><a href="profile.php?id=$id">$name</a></h2>
        <input name="name" value=$usr[username] hidden>
        <button class='btn btn-warning' name='unFollowing'>Bỏ theo dõi</button></form></li>
    </form>
</li>
LISTFRIEND;
        }

        return $this->friend;
    }

    /**
     * Giao diện Lấy lại mật khẩu
     */
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

    /**
     * Giao diện nhập mật khẩu mới khi Lấy lại mật khẩu
     */
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

    /**
     * Giao diện để show thông báo
     * @param string $display [description]
     * @param string $style   [description]
     * @param string $message [description]
     */
    public function addAlert($display = 'none', $style = 'danger', $message = '')
    {
        return "<div class='alert alert-$style' style='$display'><center>$message</center></div>";
    }

    /**
     * Giao diện Tìm kiếm 1 tài khoản
     * @param [type] $nameKey [description]
     */
    public function SearchUser($name) {
        $user = new UserController();
        $listUser = $user->SearchUsersByName($name);
        if(count($listUser) == 0) {
            return null;
        }

        foreach ($listUser as $usr) {

            // real-name & avatar
            $name = !empty($usr['realname']) ? $usr['realname'] : $usr['username'];
            $src = !empty($usr['avatar']) ? 'data:image;base64,'.$usr['avatar'] : "asset/img/non-avatar.png";

            $this->users .=<<<SEARCHUSER
    <tr>
    <td width="10">
        <img class="pull-left img-circle nav-user-photo" width="50" src="$src" /> 
    </td>
    <td>
    <a href="profile.php?id=$usr[id]">
        $name
    </a>
    </td>
    <td align="left">
        $usr[username]</td>
    <td>Tham gia: <i>$usr[created]</i>
    </td>
</tr>

SEARCHUSER;
        }
        
        return $this->users;
    }
}