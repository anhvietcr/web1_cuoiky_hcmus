<?php
include_once 'autoload.php';

/*
 * Class control Status
 */

class StatusController
{
    public function __construct()
    {}

    public function NewStatus($id_user, $content,$role)
    {
    	$content = htmlspecialchars($content);
        try {
            // prepare string insert status
            $sqlInsert = "INSERT INTO status(id_user, content,role, created) VALUES(?,?,?,now())";
            $data = db::$connection->prepare($sqlInsert);
            if ($data->execute([$id_user, $content,$role])) {
                return db::$connection->lastInsertId();
            }
            return 0;
        } catch (PDOException $ex) {
            throw new PDOexception($ex->getMessage());
        }
    }

    //Trang làm nè : lưu comment xuống databasr
    public function NewComment($id_status,$id_user, $content)
    {
        //var_dump($id_status);
        //die();
        try {
            // prepare string insert status
            $sqlInsert = "INSERT INTO comments(id_status,id_user_comment, content, created) VALUES(?,?,?,now())";
            $data = db::$connection->prepare($sqlInsert);
            if ($data->execute([$id_status,$id_user, $content])) {
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

    public function StatusRandom()
    {
        try {
            $sqlSelect = "SELECT DISTINCT(id_user), content, created, id,role FROM status LIMIT 10";
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
}