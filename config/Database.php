<?php
class Database
{
    private static $DBhost = '127.0.0.1';
    private static $DBname = 'dnd_db';
    private static $DBuser = 'root'; // Default XAMPP MySQL user
    private static $DBpassword = ''; // Default XAMPP MySQL password is blank
    private static $PDO = null;

    private function __construct() {}

    public static function getConnection()
    {
        if (self::$PDO === null) {
            $DBlink = 'mysql:host=' . self::$DBhost . ';dbname=' . self::$DBname;
            try {
                self::$PDO = new PDO($DBlink, self::$DBuser, self::$DBpassword);
                self::$PDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die('Connection failed: ' . $e->getMessage());
            }
        }
        return self::$PDO;
    }
}
