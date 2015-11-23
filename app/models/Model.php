<?php

namespace Models;


use FW\Database\DB;

class Model {

    /**
     * @var \FW\Database\DB
     */
    protected $db;

    public function  __construct() {
        $this->db = new DB();
    }

    public function startTran() {
        $this->db->prepare('START TRANSACTION');
        $this->db->execute();
    }

    public function commit() {
        $this->db->prepare('COMMIT');
        $this->db->execute();
    }

    public function rollback() {
        $this->db->prepare('ROLLBACK');
        $this->db->execute();
    }
} 