<?php

namespace Models;


class Estate extends Model{

    public function getEstate($id) {
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

    public function add($location, $price, $area, $floor, $isFurnished, $description, $phone, $categoryId, $cityId, $adType, $mainImageId, $createdAt) {
        $this->db->prepare('insert into estates(location, price, area, floor, is_furnished, description, phone, category_id, city_id, ad_type, main_image_id, created_at) values(?,?,?,?,?,?,?,?,?,?,?,?,?)');
        $this->db->execute(array($location, $price, $area, $floor, $isFurnished, $description, $phone, $categoryId, $cityId, $adType, $mainImageId, $createdAt));
        return $this->db->getAffectedRows();
    }

    public function edit($id, $name) {
        $this->db->prepare('update cities set name=? where id=?');
        $this->db->execute(array($name, $id));
        return $this->db->getAffectedRows();
    }
} 