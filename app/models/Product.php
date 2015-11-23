<?php

namespace Models;


use FW\DB;

class Product extends Model {

    public function __construct()
    {
        parent::__construct();
    }

    public function getProducts() {
        $this->db->prepare('select p.id,name,quantity,price,description,category_id,(select count(*) from comments where product_id=p.id) as comments_count,(select max(discount) from promotoins where product_id=p.id and exp_date>?) as discount,(select max(discount) from promotoins where category_id=p.category_id and exp_date>?) as category_discount from products as p where quantity>0 and is_deleted=false');
        $this->db->execute(array(date("Y-m-d H:i:s"), date("Y-m-d H:i:s")));
        return $this->db->fetchAllAssoc();
    }

    public function getProductsWitnUnavailable() {
        $this->db->prepare('select p.id,name,quantity,price,description,category_id,(select count(*) from comments where product_id=p.id) as comments_count,(select max(discount) from promotoins where product_id=p.id and exp_date>?) as discount,(select max(discount) from promotoins where category_id=p.category_id and exp_date>?) as category_discount from products as p where is_deleted=false');
        $this->db->execute(array(date("Y-m-d H:i:s"), date("Y-m-d H:i:s")));
        return $this->db->fetchAllAssoc();
    }

    public function getPromotion($id) {
        $this->db->prepare('select max(discount) as disc from promotoins where product_id=? and exp_date>?');
        $this->db->execute(array($id, date("Y-m-d H:i:s")));
        return $this->db->fetchRowAssoc()['disc'];
    }

    public function getProduct($id) {
        $this->db->prepare('select p.id,p.name,p.price,p.category_id,p.description,p.quantity,(select max(discount) from promotoins where product_id=p.id and exp_date>?) as discount,(select max(discount) from promotoins where category_id=p.category_id and exp_date>?) as category_discount from products as p where is_deleted=false and quantity>0 and p.id=?');
        $this->db->execute(array(date("Y-m-d H:i:s"), date("Y-m-d H:i:s"), $id));
        return $this->db->fetchRowAssoc();
    }

    public function getProductWitnUnavailable($id) {
        $this->db->prepare('select p.id,p.name,p.price,p.category_id,p.description,p.quantity,(select max(discount) from promotoins where product_id=p.id and exp_date>?) as discount,(select max(discount) from promotoins where category_id=p.category_id and exp_date>?) as category_discount from products as p where is_deleted=false and p.id=?');
        $this->db->execute(array(date("Y-m-d H:i:s"), date("Y-m-d H:i:s"), $id));
        return $this->db->fetchRowAssoc();
    }

    public function changeQuantity($id,$quantity) {
        $this->db->prepare('update products set quantity=quantity-? where is_deleted=false and quantity>=? and id=?');
        $this->db->execute(array($quantity, $quantity, $id));
        return $this->db->getAffectedRows();
    }

    public function addQuantity($id,$quantity) {
        $this->db->prepare('update products set quantity=quantity+? where is_deleted=false and id=?');
        $this->db->execute(array($quantity, $id));
        return $this->db->getAffectedRows();
    }

    public function getProductsForCategory($id) {
        $this->db->prepare('select p.id,p.name,p.quantity,p.price,p.description,(select count(*) from comments where product_id=p.id) as comments_count,(select max(discount) from promotoins where product_id=p.id and exp_date>?) as discount,(select max(discount) from promotoins where category_id=p.category_id and exp_date>?) as category_discount from products as p where quantity>0 and  p.is_deleted=false and p.category_id=?');
        $this->db->execute(array(date("Y-m-d H:i:s"), date("Y-m-d H:i:s"), $id));
        return $this->db->fetchAllAssoc();
    }

    public function getProductsForCategoryWitnUnavailable($id) {
        $this->db->prepare('select p.id,p.name,p.quantity,p.price,p.description,(select count(*) from comments where product_id=p.id) as comments_count,(select max(discount) from promotoins where product_id=p.id and exp_date>?) as discount,(select max(discount) from promotoins where category_id=p.category_id and exp_date>?) as category_discount from products as p where p.is_deleted=false and p.category_id=?');
        $this->db->execute(array(date("Y-m-d H:i:s"), date("Y-m-d H:i:s"), $id));
        return $this->db->fetchAllAssoc();
    }

    public function add($name,$description,$price,$quantity,$category_id) {
        $this->db->prepare('insert into products(name,description,price,quantity,category_id) values(?,?,?,?,?)');
        $this->db->execute(array($name,$description,$price,$quantity,$category_id));
        return $this->db->getAffectedRows();
    }

    public function edit($id, $name,$description,$price,$quantity,$category_id) {
        $this->db->prepare('update products set name=?, description=?, price=?, quantity=?, category_id=? where id=?');
        $this->db->execute(array($name, $description,$price,$quantity,$category_id, $id));
        return $this->db->getAffectedRows();
    }

    public function delete($id) {
        $this->db->prepare('update products set is_deleted=true where id=?');
        $this->db->execute(array($id));
        return $this->db->getAffectedRows();
    }
} 