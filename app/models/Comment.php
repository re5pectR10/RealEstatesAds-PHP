<?php

namespace Models;


class Comment extends Model {

    public function getCommentsByProduct($id) {
        $this->db->prepare('select c.id,c.content,c.posted_on,c.user_id,u.username from comments as c join users as u on u.id=c.user_id where c.product_id=?');
        $this->db->execute(array($id));
        return $this->db->fetchAllAssoc();
    }

    public function add($user_id, $product_id, $text) {
        $this->db->prepare('insert into comments(user_id,product_id,content,posted_on) values(?,?,?,?)');
        $this->db->execute(array($user_id, $product_id, $text, date("Y-m-d")));var_dump($this->db);
        return $this->db->getAffectedRows();
    }

    public function getComment($id) {
        $this->db->prepare('select id,content,posted_on,user_id from comments where id=?');
        $this->db->execute(array($id));
        return $this->db->fetchRowAssoc();
    }

    public function delete($id) {
        $this->db->prepare('delete from comments where id=?');
        $this->db->execute(array($id));
        return $this->db->getAffectedRows();
    }
} 