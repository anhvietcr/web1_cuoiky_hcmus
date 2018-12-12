<?php
include_once 'autoload.php';

/*
 * Class control Status
 */

class StatusController
{
    public function __construct()
    {}

    public function NewStatus($id_user, $content)
    {
    	$content = htmlspecialchars($content);
        try {
            // prepare string insert status
            $sqlInsert = "INSERT INTO status(id_user, content, created) VALUES(?,?,now())";
            $data = db::$connection->prepare($sqlInsert);
            if ($data->execute([$id_user, $content])) {
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
            $sqlSelect = "SELECT id_user, content, created FROM status WHERE id_user = ? ORDER BY created DESC LIMIT ?";
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
            $sqlSelect = "SELECT DISTINCT(id_user), content, created FROM status LIMIT 10";
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
}