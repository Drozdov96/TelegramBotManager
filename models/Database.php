<?php


namespace Models;


class Database implements storage
{
    private static $dbInstance = [];
    private $dbConnection;

    public function __construct()
    {
    }

    public function __clone()
    {
    }

    public function __wakeup()
    {
    }

    public static function getInstance(string $connectionString)
    {
        $cls = static::class;
        if(isset(self::$dbInstance[$cls])){
            return self::$dbInstance[$cls];
        }else{
            Database::$dbInstance[$cls] = new static();
            Database::$dbInstance[$cls]->dbConnection = new \PDO($connectionString);
            return self::$dbInstance[$cls];
        }
    }
}