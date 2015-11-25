<?php

namespace Models;


class Image extends Model{

    public function add($path) {
        $this->db->prepare('insert into images(path) values(?)');
        $this->db->execute(array($path));
        return $this->db->getLastInsertId();
    }
} 