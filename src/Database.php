<?php
namespace App;

use PDO;
use Exception;

class Database {

    private $connection;
    private static $instance = null;

    private function __construct()
    {
        global $DATABASE_CONFIG;

        $dsn = "mysql:host={$DATABASE_CONFIG["db_host"]};port={$DATABASE_CONFIG["db_port"]};dbname={$DATABASE_CONFIG["db_name"]};charset={$DATABASE_CONFIG["db_charset"]}";
        $user = $DATABASE_CONFIG["db_user"];
        $pass = $DATABASE_CONFIG["db_password"];
        
        try {
            $this->connection = new PDO($dsn, $user, $pass);
        } catch (Exception $e){
            throw new Exception($e->getMessage());
        }
    }

    public static function instance(){
        if(self::$instance === null){
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function getConnection(){
        return $this->connection;
    }
}

