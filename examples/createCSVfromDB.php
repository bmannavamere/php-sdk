<?php

// DATABASE CONNEC.
class Db {
    // https://gist.github.com/skhani/5aebd11015881fb3d288
    private $_connection;
    private static $_instance; //The single instance
    private $_host = 'localhost';
    // private $_host = '132.148.17.227';
    private $_username = 'avamerem_forms';
    private $_password = '23m2D09ZhkP4eWnnNr';
    private $_database = 'avamerem_web_forms';
    /*
    Get an instance of the Database
    @return Instance
    */
    public static function getInstance()
    {
        if (!self::$_instance) { // If no instance then make one
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    // Constructor
    private function __construct()
    {
        try {
            $this->_connection  = new \PDO("mysql:host=$this->_host;dbname=$this->_database", $this->_username, $this->_password);
            /*** echo a message saying we have connected ***/
            // echo 'Connected to database';
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
    // Magic method clone is empty to prevent duplication of connection
    private function __clone()
    {
    }
    // Get mysql pdo connection
    public function getConnection()
    {
        return $this->_connection;
    }
}


// Query DB tables to create CSV files
class createCSV {
  public function __construct(){
    $db = Db::getInstance();
    $this->_dbh = $db->getConnection();
  }
  function contactFormCSV() {
    // $sql = "TRUNCATE TABLE jobcategories; TRUNCATE TABLE jobs; TRUNCATE TABLE jobdetails";
    $sql = "SELECT page, name, email, time_stamp FROM contact_form WHERE time_stamp IS NOT NULL ORDER BY time_stamp DESC";
    foreach ($this->_dbh->query($sql) as $row) {
                echo '<pre>';
                  // print_r($row['page']);
                  print_r($row);
                echo '</pre>';
            }
  }
  // do same but for tour
}
$tablesObj = new createCSV();
$tablesObj->contactFormCSV();
