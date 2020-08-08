<?php
if (session_status() == PHP_SESSION_NONE) { session_start(); }
ob_start();
error_reporting(E_ALL);
ini_set('display_errors', true);
ini_set('display_startup_errors', true);

require_once('base_url.php');

if(file_exists('db.php')) {
    unlink('db.php');
}

extract($_REQUEST);

function errorAction($error) {
    if(isset($error) && !empty($error)) {
        ob_get_clean();
        echo json_encode($error);
        return false;
    }
}

if(filter_var(gethostbyname($dbhost), FILTER_VALIDATE_IP)) {
    $connectCheck = new mysqli($dbhost, $dbusername, $dbpassword);
    if($connectCheck->connect_errno || !empty($connectCheck->connect_error)) {
        $dbhost = 'localhost';
        $connectCheck = new mysqli($dbhost, $dbusername, $dbpassword);
    }

    if($connectCheck->connect_errno || !empty($connectCheck->connect_error)) {
        errorAction(['dbusername'=>'Invalid database username or password.']);
    }elseif(mysqli_select_db($connectCheck, $dbname)) {
        $action = true;
    }else {
        errorAction(['dbname'=>'Invalid database name.']);
    }
}else {
    errorAction(['dbhost'=>'Invalid host.']);
}

if(isset($action) && $action === true) {
    /*
    to create database
    */
    class Connect_db
    {
        private $database; // database connection
        private $dbhost; // host name
        private $dbusername; // database user name
        private $dbpassword; // database user password
        private $dbname;  // database name


        // to connect database
        public function connect()
        {
            $db_connect = new mysqli($this->dbhost, $this->dbusername, $this->dbpassword, $this->dbname);
            if (mysqli_connect_error()) {
                trigger_error("Field to connect to MySQl :" . mysqli_connect_error());
            } else {
                return $db_connect;
            }
        }

        public function __construct($dbhost,$dbusername,$dbpassword,$dbname)
        {
            $this->dbhost = $dbhost;
            $this->dbusername = $dbusername;
            $this->dbpassword = $dbpassword;
            $this->dbname = $dbname;
            $this->database = new mysqli($this->dbhost, $this->dbusername, $this->dbpassword);
            $this->createDatabse();
            $this->connect();
        }

        /*
        * create database tables
        */
        public function createDatabse() {
            $db = $this->database->query("CREATE DATABASE IF NOT EXISTS $this->dbname");
            if($db === true) {
                $tables = [
                    "CREATE TABLE `fms_admin` (
                    `id` int(11) NOT NULL,
                    `fullName` varchar(100) NOT NULL,
                    `email` varchar(100) NOT NULL,
                    `userName` varchar(100) DEFAULT NULL,
                    `password` varchar(75) DEFAULT NULL,
                    `userInfo_sc` varchar(1000) DEFAULT NULL,
                    `percentage` varchar(11) NOT NULL,
                    `photo` varchar(75) NOT NULL,
                    `birthday` varchar(20) NOT NULL,
                    `gender` varchar(10) NOT NULL,
                    `userRoll` varchar(30) NOT NULL,
                    `a_date` varchar(20) DEFAULT NULL,
                    `u_date` varchar(20) DEFAULT NULL,
                      PRIMARY KEY (id)
                    ) ENGINE=InnoDB DEFAULT CHARSET=latin1",
                    "CREATE TABLE `fms_bank` (
                      `bankId` int(11) NOT NULL AUTO_INCREMENT,
                      `id` int(11) NOT NULL,
                      `earnSource` varchar(300) NOT NULL,
                      `amount` int(11) NOT NULL,
                      `currency` varchar(10) NOT NULL,
                      `ba_date` varchar(20) NOT NULL,
                      `bu_date` varchar(20) NOT NULL,
                      PRIMARY KEY (bankId)
                    ) ENGINE=InnoDB DEFAULT CHARSET=latin1",
                    "CREATE TABLE `fms_cost` (
                      `cst_id` int(11) NOT NULL AUTO_INCREMENT,
                      `id` int(11) NOT NULL,
                      `cst_amount` int(11) NOT NULL,
                      `cst_currency` varchar(4) NOT NULL,
                      `cost_details` varchar(2000) NOT NULL,
                      `cst_a_date` varchar(20) NOT NULL,
                      `cst_u_date` varchar(20) NOT NULL,
                      PRIMARY KEY (cst_id)
                    ) ENGINE=InnoDB DEFAULT CHARSET=latin1",
                    "CREATE TABLE `company_settings` (
                      `id` int(11) NOT NULL AUTO_INCREMENT,
                      `companyName` varchar(150) NOT NULL,
                      `companyLogo` varchar(75) NOT NULL,
                      `userCurrency` varchar(10) NOT NULL,
                      `smtpHost` varchar(100) NOT NULL,
                      `smtpPort` varchar(10) NOT NULL,
                      `smtpAuth` varchar(20) NOT NULL,
                      `contactEmail` varchar(120) NOT NULL,
                      `emailPassword` varchar(75) NOT NULL,
                      `startingDate` varchar(20) NOT NULL,
                      `secureAuth` varchar(75) NOT NULL,
                      PRIMARY KEY (id)
                    ) ENGINE=InnoDB DEFAULT CHARSET=latin1"
                ];

                foreach($tables as $sql) {
                    $this->connect()->query($sql);
                }
            }
        }


        /*
        * check database connection and tables is exists
        */
        public function check_db() {
            $db_exists = $this->connect()->query("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '$this->dbname'");
            return $db_exists->num_rows;
        }
    }
    $db_success = new Connect_db($dbhost,$dbusername,$dbpassword,$dbname);
    $db_exists = $db_success->check_db();

    $file = 'db.php';  // database connection file

    //  check database connection file and database is exists
    if(!file_exists($file) && $db_exists === 1) {

        // create database connection file

$content = '<?php
if (session_status() === PHP_SESSION_NONE) { session_start();}

class Db
{
    private $db;
    private $dbHost = \''.$dbhost.'\';
    private $dbUser = \''.$dbusername.'\';
    private $dbPass = \''.$dbpassword.'\';
    private $dbName = \''.$dbname.'\';
    public static $url = \''.$base_url.'\';
    private static $instance = null;

    public function __construct()
    {
        // Connect to database //
        $this->db = new mysqli($this->dbHost, $this->dbUser, $this->dbPass, $this->dbName);
    }

    public static function connect()
    {
        if(self::$instance === null) {
            $connect = new Db;
            return self::$instance = $connect->db;
        }
        return self::$instance;
    }
}';

        $file = 'db.php';
        $fp = fopen($file,"wb") or die('Cannot open file:  '.$file);
        fwrite($fp, $content);
        fclose($fp);
        if(file_exists($file)) {
            ob_get_clean();
            echo 'connection is ok';
        }
    }

}
