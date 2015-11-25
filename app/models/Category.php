<?php

namespace Models;

class Category extends Model {

    public function getCategory($id) {
        $this->db->prepare('select id,name from categories where id=?');
        $this->db->execute(array($id));
        return $this->db->fetchRowAssoc();
    }

    public function getCategories() {
        $this->db->prepare('select id,name from categories');
        $this->db->execute();
        return $this->db->fetchAllAssoc();
    }

    public function delete($id) {
        $this->db->prepare('delete from categories where id=?');
        $this->db->execute(array($id));
        return $this->db->getAffectedRows();
    }

    public function add($name) {
        $this->db->prepare('insert into categories(name) values(?)');
        $this->db->execute(array($name));
        return $this->db->getAffectedRows();
    }

    public function edit($id, $name) {
        $this->db->prepare('update categories set name=? where id=?');
        $this->db->execute(array($name, $id));
        return $this->db->getAffectedRows();
    }
} 