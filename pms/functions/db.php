<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
class Db
{
    private $db;
    private $dbHost = 'localhost';
    private $dbUser = 'user';
    private $dbPass = '123';
    private $dbName = 'skpms';
    public static $url = 'http://localhost/skpms/';
    private static $instance = null;

    public function __construct()
    {
        // Connect to database //
        $this->db = new mysqli($this->dbHost, $this->dbUser, $this->dbPass, $this->dbName);
    }

    public static function connect()
    {
        if (self::$instance === null) {
            $connect = new Db;
            return self::$instance = $connect->db;
        }
        return self::$instance;
    }
}
