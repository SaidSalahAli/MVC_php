<?php 
// require_once('Models/Database.php');
class DB {
    protected static $db;

    public static function connect() {
        if (!isset(self::$db)) {
            self::$db = new Database(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        }
        return self::$db;
    }
}