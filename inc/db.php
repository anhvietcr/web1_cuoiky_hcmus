<?php

class db
{
    /*
     *
     * Nếu xây dựng các query trong class db thì chuyển $connection vể private
     * Hiện tại để public là để các class khác có thể truy cập và thực thi
     * query từ các class đó.
     *
     * */
    private static $option;
    public static $connection;

    public static function connect($host, $dbname, $username, $password)
    {
        self::$option = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ];
        try {
            if (!isset(self::$connection)) {
                self::$connection = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8",
                    $username,
                    $password,
                    self::$option);
            }
        } catch (PDOException $ex) {
            throw new PDOException($ex->getMessage());
        }
    }
}
