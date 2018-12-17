<?php

/**
 * Created by PhpStorm.
 * User: khanhphan
 * Date: 12/17/18
 * Time: 11:46 AM
 */
class CommentController
{
    public function __construct()
    {}
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
    public function CommentWithIdStatus($id_status)
    {
        try {
            $sqlSelect = "SELECT * FROM comments WHERE id_status = ? ORDER BY created DESC";
            $data = db::$connection->prepare($sqlSelect);
            if ($data->execute([$id_status])) {
                $row = $data->fetchAll(PDO::FETCH_ASSOC);
                return $row;
            }
            return null;
        } catch (PDOException $ex) {
            throw new PDOException($ex->getMessage());
        }
    }

}