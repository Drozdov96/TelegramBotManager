<?php


namespace Models;


class Database
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

    public static function getInstance()
    {
        $cls = static::class;
        if(isset(self::$dbInstance[$cls])){
            return self::$dbInstance[$cls];
        }else{
            Database::$dbInstance[$cls] = new static();
            Database::$dbInstance[$cls]->dbConnection = new \PDO("pgsql:host=localhost port=5432 
        dbname=battleship user=www-data password=5621");
            return self::$dbInstance[$cls];
        }
    }
}