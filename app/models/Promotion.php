<?php

namespace Models;


class Promotion extends Model {

    public function getPromotions() {
        $this->db->prepare('select p.id,p.product_id,(select name from products where id = p.product_id) as product,category_id,(select name from categories where id = p.category_id) as category,p.criteria,p.exp_date,p.discount from promotoins as p');
        $this->db->execute();
        return $this->db->fetchAllAssoc();
    }

    public function delete($id) {
        $this->db->prepare('delete from promotoins where id=?');
        $this->db->execute(array($id));
        return $this->db->getAffectedRows();
    }

    public function add($discount,$expDate,$category_id,$products_id) {
        $this->db->prepare('insert into promotoins(discount,exp_date,category_id,product_id) values(?,?,?,?)');
        $this->db->execute(array($discount,$expDate,$category_id,$products_id));
        return $this->db->getAffectedRows();
    }

    public function getHighestActivePromotion() {
        $this->db->prepare('select max(discount) as discount from promotoins where product_id is null and category_id is null and criteria is null and exp_date>?');
        $this->db->execute(array(date("Y-m-d")));
        return $this->db->fetchRowAssoc();
    }
} 