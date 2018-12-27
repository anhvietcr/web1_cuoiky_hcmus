<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

//autoloader
require dirname(dirname(__FILE__)) .'/vendor/autoload.php';
include_once 'autoload.php';

date_default_timezone_set('Asia/Ho_Chi_Minh');

/*
 * Class control User
 */
class UserController
{
    private $request;

    public function __construct()
    {
        db::connect();
    }

    private function setCookie($username, $realname, $remember = "on")
    {
        if ($remember == 'on') {
            $time = 3600 * 24; // 24 hours
        } else {
            $time = 60*10; // 10 minutes
        }

        setcookie('login', $username , time() + $time);
        setcookie('realname', $realname , time() + $time);
        return 1;
    }
//Trả về user theo id và username
    public function GetUser($username = '', $id = '')
    {
        // valid params
        if (((int)$id < 1 || empty($id)) && empty($username)) {
            return "Tài khoản không hợp lệ";
        }

        try {
            // prepare string select username
            $sqlSelect = "SELECT * FROM users WHERE id = ? OR username = ?";
            $data = db::$connection->prepare($sqlSelect);
            if ($data->execute([$id, $username])) {
                return $data->fetch(PDO::FETCH_ASSOC);
            }
            return "Có lỗi xảy ra";
        } catch (PDOException $ex) {
            throw new PDOException($ex->getMessage());
        }
    }
//...=> truyền vào mảng đối số mà k biết trước số lượng
    public function login(...$args)
    {
        $this->request = $args[0];

        // Valid params
        if (empty($this->request['username']) || empty($this->request['password'])) {
            return "Nhập đầy đủ dữ liệu";
        }
//Kiểm tra đầu vào username (nó là email) có đúng định dạng hay không
        if (!preg_match('/^[0-9a-zA-Z._]+\@[a-zA-Z]+\..*$/', $this->request['username'])) {
            return "Tên đăng nhập phải đúng định dạng email";
        }

        try {
            $usr = $this->GetUser($this->request['username']);
            if ($usr['username'] != $this->request['username']) {
                return "Không khớp tài khoản và mật khẩu";
            }

            if (!password_verify($this->request['password'], $usr['password'])) {
                return "Không khớp tài khoản và mật khẩu";
            }

            // Update last login time
            $sqlUpdate = "UPDATE users SET last_login = now() WHERE username = ?";
            $data = db::$connection->prepare($sqlUpdate);
            if ($data->execute([$usr['username']])) {

                if ($this->setCookie($usr['username'], $usr['realname'])) {
                    return 1;
                }
            }
            return "Đăng nhập thất bại";
        } catch (PDOException $ex) {
            throw new PDOException($ex->getMessage());
        }
    }

    public function SendEmail($email,$token)
    {
        
        $mail = new PHPMailer(true);
        try {
            //Server settings
            $mail->isSMTP();                                      // Set mailer to use SMTP
            $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
            $mail->SMTPAuth = true;                               // Enable SMTP authentication
            $mail->Username = 'lqv1119961@gmail.com';                 // SMTP username
            $mail->Password = '01673226121';                           // SMTP password
            $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
            $mail->Port = 587;                                    // TCP port to connect to
        
            //Recipients
            $mail->setFrom('lqv1119961@gmail.com', 'Web 1');
            $mail->addAddress($email);     //
            //Content
            $mail->isHTML(true);
            $mail->Subject = 'Confirm Register account from website F5 Team';
            $mail->Body    = "Nhấn vào đường dẫn sau để xác nhận đăng ký tài khoản: <a href='http://$_SERVER[HTTP_HOST]/confirm.php?email=$email&token=$token'>http://$_SERVER[HTTP_HOST]/confirm.php?email=$email&token=$token</a>";

            $mail->send();
            return 1;
        } catch (Exception $e) {
            return 'Không thể gửi mail. Mailser Error: '. $mail->ErrorInfo;
        }
    }

    public function register(...$args)
    {
        $this->request = $args[0];

        

        if (!preg_match('/^[0-9a-zA-Z._]+\@[a-zA-Z]+\..*$/', $this->request['username'])) {
            return "Tên đăng nhập phải đúng định dạng email";
        }

        if (empty($this->request['realname'])) {
            return "Tên người dùng không được để trống";
        }

        if (strcmp($this->request['password'], $this->request['re-password']) != 0) {
            return "Mật khẩu nhập lại không khớp nhau";
        }

        if (strlen($this->request['password']) < 6) {
            return "Mật khẩu độ dài tối thiểu 6 ký tự";
        }

        if (empty($this->request['username']) || empty($this->request['password']) || empty($this->request['re-password'])) {
            return "Nhập đầy đủ dữ liệu";
        }        

        try {
            $usr = $this->GetUser($this->request['username']);
            if ($usr['username'] == $this->request['username']) {
                return "Đã tồn tại email!";
            }

            $token = '';
            $prepare = "0123456789qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM";
            $len = strlen($prepare) - 1;
            for ($c = 0; $c < 25; $c++)
            {
                $token .= $prepare[rand(0, $len)];
            }
            $passwordHash = password_hash($this->request['password'], PASSWORD_DEFAULT);
            $strInsert = "INSERT INTO register (username, password, token, realname) VALUES(?, ?, ?, ?)";
            $data = db::$connection->prepare($strInsert);
            if ($data->execute([$this->request['username'], $passwordHash,$token, $this->request['realname']])) {

                return $this->SendEmail($this->request['username'],$token);
            }
        } catch (PDOException $ex) {
            throw new PDOException($ex->getMessage());
        }
    }

    public function FindRegisterByEmailAndToken($email,$token)
    {
        // valid params
        if (empty($email) || empty($token))
            return null;
        
        try {
            // prepare string select username
            $sqlSelect = "SELECT * FROM register WHERE username=? AND token=?";
            $data = db::$connection->prepare($sqlSelect);
            
            if ($data->execute([$email, $token])) {
                return $data->fetch(PDO::FETCH_ASSOC);
            }

            return null;
        } catch (PDOException $ex) {
            throw new PDOException($ex->getMessage());
        }
    }

    public function DeleteRegisterByEmail($email)
    {
            $sqlSelect = "DELETE FROM register WHERE username = ?";
            $data = db::$connection->prepare($sqlSelect);
            $data->execute([$email]);           
    }

    public function confirm(...$args)
    {
        $this->request = $args[0];
        $token=$this->request['token'];
        $email=$this->request['email'];
        if(isset($token) && isset($email)){
            //return "đã ở đây";
            $check=$this->FindRegisterByEmailAndToken($email,$token);
            
            if($check){
                //return $check;
                $strInsert = "INSERT INTO users(username, password, realname, created, last_login) VALUES(?, ?, ?, now(), now())";
                $data = db::$connection->prepare($strInsert);
                if ($data->execute([$email, $check['password'], $check['realname']])) {
                    $this->DeleteRegisterByEmail($email);
                    return 1;     
                }
            }
        }
        return "Đăng ký thất bại";     
    }

    public function ChangePassword($username, ...$args)
    {
        $this->request = $args[0];

        // valid params
        if (empty($this->request['old-password']) || empty($this->request['new-password']) || empty($this->request['renew-password'])) {
            return  "Nhập đầy đủ dữ liệu";
        }

        if (strcmp($this->request['new-password'], $this->request['renew-password'])) {
            return "Nhập lại mật khẩu mới không khớp";
        }

        if (!strcmp($this->request['old-password'], $this->request['new-password'])) {
            return "Mật khẩu mới giống mật khẩu hiện tại";
        }

        try {
            $usr = $this->GetUser($username);
            if ($usr['username'] != $username) {
                return "Không tồn tại tên đăng nhập";
            }

            if (!password_verify($this->request['old-password'], $usr['password'])) {
                return "Mật khẩu hiện tại không chính xác";
            }

            // Hash password
            $passwordHash = password_hash($this->request['new-password'], PASSWORD_DEFAULT);

            // prepare string update password
            $sqlUpdate = "UPDATE users SET password = ? WHERE username = ?";
            $data = db::$connection->prepare($sqlUpdate);
            if ($data->execute([$passwordHash, $username])) {
                return "Đổi mật khẩu thành công";
            }
            return "Có lỗi xảy ra";
        } catch (PDOException $ex) {
            throw new PDOException($ex->getMessage());
        }
    }

    public function UpdateProfile($username, $avatar, ...$args)
    {
        $this->request = $args[0];
        $phone = $name = $img = "";

        // Update phone-number
        // 0123456789 || +84123456789
        if (!empty($this->request['phone'])) {

            if (!preg_match('/0{1}[0-9]{9}$|\+[0-9]{2}[0-9]{9}$/', $this->request['phone'])) {
                return "Định dạng số điện thoại không chính xác";
            }
            $phone = $this->request['phone'];
        }

        // Update real-name
        if (!empty($this->request['realname'])) {

            $temp_name = htmlspecialchars($this->request['realname']);

            if (strlen($temp_name) > 50 || strlen($temp_name) < 2) {
                return "Tên dài từ 2 đến 50 ký tự";
            }
            $name = $temp_name;
        }

        // Update avatar
        if (!empty($avatar['avatar']['name']) && $avatar['avatar']['size'] > 0) {

            if (getimagesize($avatar['avatar']['tmp_name']) === false) {
                return "Không đúng định dạng hình ảnh";
            }
            $img = base64_encode(file_get_contents($avatar['avatar']['tmp_name']));
        }

        try {
            $usr = $this->GetUser($username);
            if ($usr['username'] != $username) {
                return "Không tồn tại tên đăng nhập";
            }

            if (empty($phone)) $phone = $usr['phone'];
            if (empty($name)) $name = $usr['realname'];
            if (empty($img)) $img = $usr['avatar'];


            // prepare string update profile
            $sqlUpdate = "UPDATE users SET realname = ?, phone = ?, avatar = ? WHERE username = ?";
            $data = db::$connection->prepare($sqlUpdate);
            if ($data->execute([$name, $phone, $img, $username])) {
                return "Cập nhật thành công";
            }
            return "Cập nhật thất bại, có lỗi xảy ra";
        } catch (PDOException $ex) {
            throw new PDOException($ex->getMessage());
        }
    }

    public function NewStatus($username, $attach, ...$args)
    {
        $this->request = $args[0];

        // Attach image
        if (!empty($attach['image']['name']) && $attach['image']['size'] > 0) {

            if (getimagesize($attach['image']['tmp_name']) === false) {
                return "Không đúng định dạng hình ảnh.";
            }

            $this->request['image'] = $attach['image'];
        }


        // valid content status
        if (!isset($this->request['image']) && empty($this->request['content'])) {
            return "Chưa viết gì hết";
        }


        try {
            $usr = $this->GetUser($username);
            if ($usr['username'] != $username) {
                return "Không tồn tại tên đăng nhập";
            }

            $status = new StatusController();
            $id = $status->NewStatus(
                $usr['id'], 
                $this->request
            );

            return $id ? $id : "Đăng status thất bại, có lỗi xảy ra";
        } catch (PDOException $ex) {
            throw new PDOException($ex->getMessage());
        }
    }


    //Trang làm nè: Kiểm tra username gọi đến controller newcomment
    public function NewComment($id_status,$username, $content)
    {
        // valid params
        if (empty($content)) {
            return "Chưa viết gì hết";
        }

        try {
            $usr = $this->GetUser($username);
            if ($usr['username'] != $username) {
                return "Không tồn tại tên đăng nhập";
            }
            $comment = new CommentController();
            $id = $comment->NewComment($id_status,$usr['id'],$content);
            return $id ? $id : "Đăng comment thất bại, có lỗi xảy ra";
        } catch (PDOException $ex) {
            throw new PDOException($ex->getMessage());
        }
    }
    public function LoadNewsfeed($username)
    {
        try {
            $usr = $this->GetUser($username);
            if ($usr['username'] != $username) {
                return "Không tồn tại tên đăng nhập";
            }

            $id = $usr['id'];
            $following = $usr['following'];
            $arrStatus = [];

            /*
             * Getting status from myself
             *
             * Exist friend
             * -> Append status friend
             *
             * Else NOT exists friend (empty following) + Friend haven't status
             * -> Get random status
             *
             */
            $status = new StatusController();
            // getting status from myself
            $stt = $status->StatusById($id);
            if ($stt != null) {
                $arrStatus = array_merge($arrStatus, $stt);
            }

            if (!empty($following)) {
                $idFriends = unserialize($following);
                foreach ($idFriends as $idf) {

                    // getting status from friend
                    $stt = $status->ShowStatusWithRelationship($id, $idf);
                    if ($stt != null) $arrStatus = array_merge($arrStatus, $stt);
                }
            }

            if (count($arrStatus) < 1) {
                $arrStatus = $status->StatusRandom();
            }

            return $arrStatus;
        } catch (PDOException $ex) {
            throw new PDOException($ex->getMessage());
        }
    }


    public function ListFriends($username, $follow = 'followed')
    {
        // valid params
        if (empty($username)) {
            return "Nhập đầy đủ thông tin";
        }

        try {
            $usr = $this->GetUser($username);
            if ($usr['username'] != $username) {
                return "Không tồn tại tên đăng nhập";
            }

            $followsID = !empty($usr[$follow]) ? unserialize($usr[$follow]) : [];
            $friends = [];

            // get user info by id
            foreach($followsID as $id) {

                // prepare string select list friends
                $sqlSelect = "SELECT * FROM users WHERE id = ?";
                $data = db::$connection->prepare($sqlSelect);
                if ($data->execute([$id])) {
                    $row = $data->fetchAll();
                    $friends = array_merge($friends, $row);
                }
            }
            return $friends;
        } catch (PDOException $ex) {
            throw new PDOException($ex->getMessage());
        }
    }

    public function AddFriend($userA, $userB)
    {
        //
        // A ----request-----> B
        //

        // valid params
        if (empty($userA) || empty($userB)) {
            return "Định dạng không chính xác";
        }

        if (strcmp($userA, $userB) == 0) {
            return "Không thể gửi yêu cầu cho chính mình";
        }

        try {
            $A = $this->GetUser($userA);
            $B = $this->GetUser($userB);
            if ($A['username'] != $userA || $B['username'] != $userB) {
                return "Không tồn tại tên đăng nhập";
            }

            // checked exists friend request
            //unserialize là hàm chuyển đổi dữ liệu từ database sang array
            //Gán $followingA = thuộc tính following của Tài khoản A
            $followingA = !empty($A['following']) ? unserialize($A['following']) : [];
            $followsA = !empty($A['follows']) ? unserialize($A['follows']) : [];
            $followsB = !empty($B['follows']) ? unserialize($B['follows']) : [];
            $followingB = !empty($B['following']) ? unserialize($B['following']) : [];
            $idA = $A['id'];
            $idB = $B['id'];
            //Nếu A đang theo dỏi B or B đang theo dõi A => k thể kết bạn
            if (in_array($idB, $followingA) || in_array($idB, $followsA)
                || in_array($idA, $followingB) || in_array($idA, $followsB)) {
                return "Không thể gửi yêu cầu kết bạn";
            }

            // add id to list follow
            // ghi nhận A đang theo dỏi B
            array_push($followingA, $idB);
            array_push($followsB, $idA);

            // prepare string update
            $sqlUpdateA = "UPDATE users SET following = ? WHERE username = ?";
            $sqlUpdateB = "UPDATE users SET follows = ? WHERE username = ?";

            $data = db::$connection->prepare($sqlUpdateA);
            if (!$data->execute([serialize($followingA), $userA])) {
                return "Không thể gửi yêu cầu kết bạn, có lỗi xảy ra";
            }

            $data = db::$connection->prepare($sqlUpdateB);
            if (!$data->execute([serialize($followsB), $userB])) {
                return "Không thể gửi yêu cầu kết bạn, có lỗi xảy ra";
            }
            return "Đã gửi lời kết bạn !";
        } catch (PDOException $ex) {
            throw new PDOException($ex->getMessage());
        }
    }

    public function AcceptFriend($userA, $userB)
    {
        //
        // A ----accept-----> B
        //

        // valid params
        if (empty($userA) || empty($userB)) {
            return "Định dạng không chính xác";
        }

        if (strcmp($userA, $userB) == 0) {
            return "Không thể gửi yêu cầu cho chính mình";
        }

        try {
            $A = $this->GetUser($userA);
            $B = $this->GetUser($userB);
            if ($A['username'] != $userA || $B['username'] != $userB) {
                return "Không tồn tại tên đăng nhập";
            }

            // checked exists friend request
            //
            $followedB = !empty($B['followed']) ? unserialize($B['followed']) : [];
            $followsA = !empty($A['follows']) ? unserialize($A['follows']) : [];
            $followedA = !empty($A['followed']) ? unserialize($A['followed']) : [];
            $followingA = !empty($A['following']) ? unserialize($A['following']) : [];

            $idA = $A['id'];
            $idB = $B['id'];

            if (in_array($idA, $followedB) || in_array($idB, $followedA) || !in_array($idB, $followsA)) {
                return "Không thể chấp nhận yêu cầu kết bạn";
            }

            // add id to list follow
            array_push($followedA, $idB);
            array_push($followedB, $idA);
            array_push($followingA, $idB);

            // Delete id B in follows A
            $followsA = array_filter($followsA, function($e) use ($idB) {
                return ($e !== $idB);
            });

            // prepare string update
            $sqlUpdateA = "UPDATE users SET followed = ?, follows = ?, following = ? WHERE username = ?";
            $sqlUpdateB = "UPDATE users SET followed = ? WHERE username = ?";

            $data = db::$connection->prepare($sqlUpdateA);
            if (!$data->execute([serialize($followedA), serialize($followsA), serialize($followingA), $userA])) {
                return "Không thể chấp nhận kết bạn, có lỗi xảy ra";
            }

            $data = db::$connection->prepare($sqlUpdateB);
            if (!$data->execute([serialize($followedB), $userB])) {
                return "Không thể chập nhận kết bạn, có lỗi xảy ra";
            }
            return "Thêm bạn bè thành công !";
        } catch (PDOException $ex) {
            throw new PDOException($ex->getMessage());
        }
    }

    public function DeclineFriend($userA, $userB)
    {
        //
        // A ----decline-----> B
        //

        // valid params
        if (empty($userA) || empty($userB)) {
            return "Định dạng không chính xác";
        }

        if (strcmp($userA, $userB) == 0) {
            return "Không thể gửi yêu cầu cho chính mình";
        }

        try {
            $A = $this->GetUser($userA);
            $B = $this->GetUser($userB);
            if ($A['username'] != $userA || $B['username'] != $userB) {
                return "Không tồn tại tên đăng nhập";
            }

            // checked exists friend request
            $followsA = !empty($A['follows']) ? unserialize($A['follows']) : [];
            $followingB = !empty($B['following']) ? unserialize($B['following']) : [];
            $idA = $A['id'];
            $idB = $B['id'];

            if (!in_array($idA, $followingB) || !in_array($idB, $followsA)) {
                return "Không thể từ chối yêu cầu";
            }

            // remove id from follow list
            $followsA = array_filter($followsA, function($e) use ($idB) {
               return ($e !== $idB);
            });

            $followingB = array_filter($followingB, function($e) use ($idA) {
               return ($e !== $idA);
            });

            // prepare sting update
            $sqlUpdateA = "UPDATE users SET follows = ? WHERE username = ?";
            $sqlUpdateB = "UPDATE users SET following = ? WHERE username = ?";
            $data = db::$connection->prepare($sqlUpdateA);
            if (!$data->execute([serialize($followsA), $userA])) {
                return "Không thể từ chối yêu cầu, có lỗi xảy ra";
            }

            $data = db::$connection->prepare($sqlUpdateB);
            if (!$data->execute([serialize($followingB), $userB])) {
                return "Không thể từ chối yêu cầu, có lỗi xảy ra";
            }
            return "Từ chối lời mời thành công !";
        } catch (PDOException $ex) {
            throw new PDOException($ex->getMessage());
        }
    }

    public function DeleteFriend($userA, $userB)
    {
        //
        // A ----delete-----> B
        //

        // valid params
        if (empty($userA) || empty($userB)) {
            return "Định dạng không chính xác";
        }

        if (strcmp($userA, $userB) == 0) {
            return "Không thể xóa kết bạn chính mình";
        }

        try {
            $A = $this->GetUser($userA);
            $B = $this->GetUser($userB);
            if ($A['username'] != $userA || $B['username'] != $userB) {
                return "Không tồn tại tên đăng nhập";
            }

            // checked exists friend request
            $followedA = !empty($A['followed']) ? unserialize($A['followed']) : [];
            $followedB = !empty($B['followed']) ? unserialize($B['followed']) : [];
            $followingA = !empty($A['following']) ? unserialize($A['following']) : [];
            $followingB = !empty($B['following']) ? unserialize($B['following']) : [];
            $idA = $A['id'];
            $idB = $B['id'];

            if (!in_array($idA, $followedB) || !in_array($idB, $followedA)) {
                return "Không thể xóa bạn bè khi chưa là bạn";
            }

            // remove id from follow list
            $followedA = array_filter($followedA, function($e) use ($idB) {
                return ($e !== $idB);
            });

            $followedB = array_filter($followedB, function($e) use ($idA) {
                return ($e !== $idA);
            });

            $followingA = array_filter($followingA, function($e) use ($idB) {
                return ($e !== $idB);
            });

            $followingB = array_filter($followingB, function($e) use ($idA) {
                return ($e !== $idA);
            });

            // prepare string update
            $sqlUpdate = "UPDATE users SET followed = ?, following = ? WHERE username = ?";
            $data = db::$connection->prepare($sqlUpdate);
            if (!$data->execute([serialize($followedA), serialize($followingA),$userA])) {
                return "Không thể xóa bạn bè, có lỗi xảy ra";
            }

            $data = db::$connection->prepare($sqlUpdate);
            if (!$data->execute([serialize($followedB), serialize($followingB), $userB])) {
                return "Không thể xóa bạn bè, có lỗi xảy ra";
            }
            return "Hủy làm bạn thành công !";
        } catch (PDOException $ex) {
            throw new PDOException($ex->getMessage());
        }
    }
//Trang
    public function unFollowing($userA, $userB)
    {
        //
        // A ----unFollowing-----> B
        //
        $this->DeclineFriend($userB,$userA);
    }

    public function ListUsers()
    {
        try {
            // prepare string select username
            $sqlSelect = "SELECT id, username, realname, avatar, following, followed, follows, created FROM users LIMIT 100";
            $data = db::$connection->prepare($sqlSelect);
            if ($data->execute()) {
                return $data->fetchAll(PDO::FETCH_ASSOC);
            }
            return "Có lỗi xảy ra";
        } catch (PDOException $ex) {
            throw new PDOException($ex->getMessage());
        }
    }

    public function SearchUsersByName($name)
    {
        try {
            // prepare string select username
            $sqlSelect = "SELECT *
                          FROM users
                          WHERE username LIKE ?
                          OR realname LIKE ?
                          LIMIT 100";
            $data = db::$connection->prepare($sqlSelect);
            if ($data->execute(array('%'.$name.'%', '%'.$name.'%'))) {
                return $data->fetchAll(PDO::FETCH_ASSOC);
            }
            return "Có lỗi xảy ra";
        } catch (PDOException $ex) {
            throw new PDOException($ex->getMessage());
        }
    }

    public function SearchPosts($username, $keyword)
    {
        try {
            $usr = $this->GetUser($username);
            if ($usr['username'] != $username) {
                return "Không tồn tại tên đăng nhập";
            }

            $id = $usr['id'];
            $following = $usr['following'];
            $resultStatus = [];

            $status = new StatusController();

            // Retrieve all posts
            $stt = $status->StatusByKeyWordAndId($keyword, $id);
            $idFriends = unserialize($following);

            if ($stt != null) {
                $resultStatus = array_merge($resultStatus, $stt);
            }

            
            foreach ($idFriends as $idFriend) {
                $stt = $status->StatusByFriendId($keyword, $id, $idFriend);
                if ($stt != null) {
                    $resultStatus = array_merge($resultStatus, $stt);
                }
            }


            return $resultStatus;
        } catch (PDOException $ex) {
            throw new PDOException($ex->getMessage());
        }
    }
    
}


//$work = new UserController();


//echo "<pre>";
//print_r($work->ListUsers());
//echo "</pre>";



//$message = $work->AddFriend('danIT', 'admin');
//
//if ($message === 1)
//{
//    echo "Gửi kết bạn thành công";
//} else {
//    echo $message;
//}



//$message = $work->AcceptFriend('admin', 'anhvietcr');
//
//if ($message === 1)
//{
//    echo "Chấp nhận kết bạn thành công";
//} else {
//    echo $message;
//}



//$message = $work->DeclineFriend('danIT', 'admin');
//
//if ($message === 1)
//{
//    echo "Từ chối yêu cầu kết bạn thành công";
//} else {
//    echo $message;
//}

//$message = $work->DeleteFriend('anhvietcr', 'admin');
//
//if ($message === 1)
//{
//    echo "Xóa bạn bè thành công";
//} else {
//    echo $message;
//}

//$message = $work->ListFriends('admin', 'following');
//
//if (count($message) > 0) {
//    echo "<pre>";
//    print_r($message);
//    echo "</pre>";
//} else {
//    echo "Not found";
//}