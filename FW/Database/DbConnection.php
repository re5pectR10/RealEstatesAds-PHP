<?php

namespace FW\Database;


use FW\App;

class DbConnection {

    private $dbConnections = array();
    private static  $instance = null;

    public function getDBConnection($connection = 'default') {
        if ($this->dbConnections[$connection]) {
            return $this->dbConnections[$connection];
        }
        $dbConfig = App::getInstance()->getConfig()->database;
        if (!$dbConfig[$connection]) {
            throw new \Exception('No valid connection identificator is provided', 500);
        }
        $dbh = new \PDO($dbConfig[$connection]['connection_uri'], $dbConfig[$connection]['username'],
            $dbConfig[$connection]['password'], $dbConfig[$connection]['pdo_options']);
        $this->dbConnections[$connection] = $dbh;
        return $dbh;
    }

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new DbConnection();
        }
        return self::$instance;
    }
} 