<?php

namespace Models;


class Message extends Model{

    public function add($first_name, $last_name, $email, $phone, $content, $about, $created_at, $is_read) {
        $this->db->prepare('insert into messages(first_name,last_name,email,phone,content,for_estate,created_at,is_read) values(?,?,?,?,?,?,?,?)');
        $this->db->execute(array($first_name, $last_name, $email, $phone, $content, $about, $created_at, $is_read));
        return $this->db->getAffectedRows();
    }

    public function getAll() {
        $this->db->prepare('select id,first_name,last_name,email,phone,created_at,is_read from messages');
        $this->db->execute(array());
        return $this->db->fetchAllAssoc();
    }

    public function getById($id) {
        $this->db->prepare('select first_name,last_name,email,phone,content,for_estate,created_at,is_read from messages where id=?');
        $this->db->execute(array($id));
        return $this->db->fetchRowAssoc();
    }

    public function markAsRead($id) {
        $this->db->prepare('update messages set is_read=true where id=?');
        $this->db->execute(array($id));
        return $this->db->getAffectedRows();
    }
} 