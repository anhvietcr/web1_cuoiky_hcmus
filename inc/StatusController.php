<?php
include_once 'autoload.php';

date_default_timezone_set('Asia/Ho_Chi_Minh');

/*
 * Class control Status
 */

class StatusController
{
    protected $request;
    
    public function __construct()
    {
        db::connect();
    }

    public function NewStatus($id_user, ...$args)
    {
        $this->request = $args[0];

    	$content = htmlspecialchars($this->request['content']);


        if (isset($this->request['image'])) {

            // upload and get path file
            $token = '';
            $prepare = "0123456789qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM";
            $len = strlen($prepare) - 1;
            for ($c = 0; $c < 20; $c++)
            {
                $token .= $prepare[rand(0, $len)];
            }
            $file_name = $this->request['image']['name'];
            $ext = strrchr($file_name,'.');
            $target_path_local = __DIR__."/upload/". $id_user . $token . $ext;
            $target_path_db = "inc/upload/". $id_user . $token . $ext;
            move_uploaded_file($this->request["image"]["tmp_name"], $target_path_local);
        } else {
            $target_path_db = "";
        }

        try {
            // prepare string insert status
            $sqlInsert = "INSERT INTO status(id_user, content, role, image, created) VALUES(?,?,?,?,now())";
            $data = db::$connection->prepare($sqlInsert);
            if ($data->execute(
                [
                    $id_user, 
                    $content, 
                    $this->request['role'],
                    $target_path_db
                ])) {
                return db::$connection->lastInsertId();
            }
            return 0;
        } catch (PDOException $ex) {
            throw new PDOexception($ex->getMessage());
        }
    }

    public function StatusById($id_user, $limit = 10)
    {
        try {
            $sqlSelect = "SELECT * FROM status WHERE id_user = ? ORDER BY created DESC LIMIT ?";
            $data = db::$connection->prepare($sqlSelect);
            if ($data->execute([$id_user, $limit])) {
                $row = $data->fetchAll(PDO::FETCH_ASSOC);
                return $row;
            }
            return null;
        } catch (PDOException $ex) {
            throw new PDOException($ex->getMessage());
        }
    }


    public function StatusByKeyWordAndId($keyword, $id)
    {
        try {
            $sqlSelect = 'SELECT * FROM status AS s
                          where s.content LIKE ? AND (s.id_user = ? OR s.role = "Công khai")
                          ORDER BY s.created';
            $data = db::$connection->prepare($sqlSelect);
            if ($data->execute(array('%'.$keyword.'%', $id))) {
                $row = $data->fetchAll(PDO::FETCH_ASSOC);
                return $row;
            }
            return null;
        } catch (PDOException $ex) {
            throw new PDOException($ex->getMessage());
        }
    }

    public function StatusByFriendId($keyword, $userId, $friendId)
    {
        try {
            $sqlSelect = 'SELECT * FROM status as s, users as u
                          where s.content LIKE ? 
                          AND (s.id_user = u.id 
                          AND u.id = ? 
                          AND ? IN (u.followed) 
                          AND role = "Công khai")
                          ORDER BY s.created';
            $data = db::$connection->prepare($sqlSelect);
            if ($data->execute(array('%'.$keyword.'%', $friendId, $userId))) {
                $row = $data->fetchAll(PDO::FETCH_ASSOC);
                return $row;
            }
            return null;
        } catch (PDOException $ex) {
            throw new PDOException($ex->getMessage());
        }
    }

    public function StatusRandom()
    {
        try {
            $sqlSelect = "SELECT DISTINCT(id_user), content, created, id, role FROM status WHERE role = 'Công khai' ORDER BY created LIMIT 10";
            $data = db::$connection->prepare($sqlSelect);
            if ($data->execute()) {
                $row = $data->fetchAll(PDO::FETCH_ASSOC);
                return $row;
            }
            return null;
        } catch (PDOException $ex) {
            throw new PDOException($ex->getMessage());
        }
    }
    //public  function Comment($)
    //Trang
    public function StatusPublic($id_user2)
    {
        //User1 vào xem profile của user2
        try {
            $sqlSelect = "SELECT * FROM status where id_user = ? and role = 'Công khai' ORDER BY created DESC";
            $data = db::$connection->prepare($sqlSelect);
            if ($data->execute([$id_user2])) {
                $row = $data->fetchAll(PDO::FETCH_ASSOC);
                return $row;
            }
            return null;
        } catch (PDOException $ex) {
            throw new PDOException($ex->getMessage());
        }
    }
    //Trang
    public function StatusFriend($id_user2)
    {
        //User1 vào xem profile của user2
        try {
            $sqlSelect = "SELECT * FROM status where id_user = ? and role = 'Bạn bè' ORDER BY created DESC";
            $data = db::$connection->prepare($sqlSelect);
            if ($data->execute([$id_user2])) {
                $row = $data->fetchAll(PDO::FETCH_ASSOC);
                return $row;
            }
            return null;
        } catch (PDOException $ex) {
            throw new PDOException($ex->getMessage());
        }
    }
    //Trang
    //userA xem profile userB
    public function ShowStatusWithRelationship($id_userA,$id_userB)
    {
        try {
            if($id_userA==$id_userB)
            {
                return $this->StatusById($id_userA);
            }

            $user = new UserController();
            $usrA = $user->GetUser('',$id_userA);
            if ($usrA['id'] != $id_userA) {
                echo "Id A";
                var_dump($id_userA);
                echo "ID B";
                var_dump($usrA['id']);
                die();
                return "Không tồn tại id";
            }
            $usrB = $user->GetUser('',$id_userB);
            if ($usrB['id'] != $id_userB) {
                return "Không tồn tại id";
            }
            $arrStatus = [];
            $sttPublic = $this->StatusPublic($id_userB);
            if ($sttPublic != null) {
                $arrStatus = array_merge($arrStatus, $sttPublic);
            }

            //Kiểm tra A và b có phải là bạn bè?
            $followedA = !empty($usrA['followed']) ? unserialize($usrA['followed']) : [];
            $followedB = !empty($usrB['followed']) ? unserialize($usrB['followed']) : [];
            if (in_array($id_userA, $followedB) && in_array($id_userB, $followedA)) {
                $sttFriend = $this->StatusFriend($id_userB);
                $arrStatus = array_merge($arrStatus, $sttFriend);
            }
            return $arrStatus;
        } catch (PDOException $ex) {
            throw new PDOException($ex->getMessage());
        }
    }
    
    public function AddWhoLiked($row,$id_user,$id_status)
    {
        try 
        {
            
            if (empty($row['wholiked']))
                {
                    $arr = array();
                    array_push($arr,$id_user);
                    $convert = serialize($arr);
                    $sqlSelect = "UPDATE status SET wholiked=? WHERE id = ?";
                    $data = db::$connection->prepare($sqlSelect);
                    if ($data->execute([$convert,$id_status]))
                    {
                        return 'Thành công';
                    }
                    return 'Thất bại';
                }
            $wholiked = unserialize($row['wholiked']);
            if (in_array($id_user,$wholiked,true))
                {
                    return "Đã like rồi";
                }
            array_push($wholiked,$id_user);
            $convert = serialize($wholiked);
            $sqlSelect = "UPDATE status SET wholiked=? WHERE id = ?";
            $data = db::$connection->prepare($sqlSelect);
            if ($data->execute([$convert,$id_status]))
            {
                return 'Thành công';
            }
            return 'Thất bại';
        } catch (PDOException $ex) {
            throw new PDOException($ex->getMessage());
        }
    }

    public function LikeForStatus($id_user,$id_status)
    {
        try
        {
            $sqlSelect = "SELECT * FROM status WHERE id = ?";
            $data = db::$connection->prepare($sqlSelect);
            if ($data->execute([$id_status])) {
                $row = $data->fetch(PDO::FETCH_ASSOC);
                return $this->AddWhoLiked($row,$id_user,$id_status);
                
            }
        
            return 'Thất bại';
        } catch (PDOException $ex) {
            throw new PDOException($ex->getMessage());
        }
    }

    public function RemoveWhoLiked($row,$id_user,$id_status)
    {
        try 
        {
            if (empty($row['wholiked']))
                {
                    return 'Thành công';
                }
            
            $wholiked = unserialize($row['wholiked']);
            foreach ($wholiked as $key => $value) 
            {
                if($value==$id_user)
                {
                   unset($wholiked[$key]);
                }
            }
            $convert = serialize($wholiked);
            $sqlSelect = "UPDATE status SET wholiked=? WHERE id = ?";
            $data = db::$connection->prepare($sqlSelect);
            if ($data->execute([$convert,$id_status]))
            {
                return 'Thành công';
            }
            return 'Thất bại';
        } catch (PDOException $ex) {
            throw new PDOException($ex->getMessage());
        }
    }

    public function UnLikeForStatus($id_user,$id_status)
    {
        try
        {
            $sqlSelect = "SELECT * FROM status WHERE id = ?";
            $data = db::$connection->prepare($sqlSelect);
            if ($data->execute([$id_status])) 
            {
                $row = $data->fetch(PDO::FETCH_ASSOC);
                return $this->RemoveWhoLiked($row,$id_user,$id_status);
            }
        
            return 'Thất bại';
        } catch (PDOException $ex) {
            throw new PDOException($ex->getMessage());
        }
    }
    public function AmountOfLiked($id_status)
    {
        try
        {
            $sqlSelect = "SELECT * FROM status WHERE id = ?";
            $data = db::$connection->prepare($sqlSelect);
            if ($data->execute([$id_status])) {
                $row = $data->fetch(PDO::FETCH_ASSOC);
                if(empty($row['wholiked'])) return 0;
                
                $array = unserialize($row['wholiked']);
                $count = count($array);
                return $count;
            }
        
            return 0;
        } catch (PDOException $ex) {
            throw new PDOException($ex->getMessage());
        }
    }

    public function IsLiked($id_user, $id_status)
    {
        try 
        {
            $sqlSelect = "SELECT * FROM status WHERE id = ?";
            $data = db::$connection->prepare($sqlSelect);
            if ($data->execute([$id_status])) 
            {
                $row = $data->fetch(PDO::FETCH_ASSOC);
                
                if (!empty($row['wholiked']))
                {
                    $wholiked = unserialize($row['wholiked']);
                    foreach ($wholiked as $key => $value) 
                    {
                        if($value==$id_user)
                        {
                            return true;
                        }
                    }
                }
                return false;
            }
            return false;
        } catch (PDOException $ex) {
            throw new PDOException($ex->getMessage());
        }
    }
}