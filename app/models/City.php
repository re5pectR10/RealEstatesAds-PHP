<?php

namespace Models;

class City extends Model{

    public function getCity($id) {
        $this->db->prepare('select id,name from cities where id=?');
        $this->db->execute(array($id));
        return $this->db->fetchRowAssoc();
    }

    public function getCities() {
        $this->db->prepare('select id,name from cities');
        $this->db->execute();
        return $this->db->fetchAllAssoc();
    }

    public function delete($id) {
        $this->db->prepare('delete from cities where id=?');
        $this->db->execute(array($id));
        return $this->db->getAffectedRows();
    }

    public function add($name) {
        $this->db->prepare('insert into cities(name) values(?)');
        $this->db->execute(array($name));
        return $this->db->getAffectedRows();
    }

    public function edit($id, $name) {
        $this->db->prepare('update cities set name=? where id=?');
        $this->db->execute(array($name, $id));
        return $this->db->getAffectedRows();
    }
} 