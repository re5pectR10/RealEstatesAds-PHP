<?php

namespace Models;


class Estate extends Model{

    public function getEstate($id) {
        $this->db->prepare('select id,name from cities where id=?');
        $this->db->execute(array($id));
        return $this->db->fetchRowAssoc();
    }

    public function getEstates(array $categories, array $cities, array $ad_type, array $price, $order_by){
        $price = array_filter($price);
        $this->db->prepare($this->buildGetEstatesQuery($categories, $cities, $ad_type, $price, $order_by));
        $this->db->execute(array_merge($categories, $cities, $ad_type, array_values($price)));
        return $this->db->fetchAllAssoc();
    }

    private function buildGetEstatesQuery(array $categories, array $cities, array $ad_type, array $price, $order_by){
        $query = 'select e.id,e.price,e.location,e.area,e.ad_type,e.main_image_id,c.name,cat.name from estates as e join cities as c on c.id=e.city_id join categories as cat on cat.id=e.category_id'
            . (!(empty($categories) && empty($cities) && empty($ad_type) && empty($price)) ? ' where ' : '')
            . (empty($categories) ? '' : 'category_id in (' . join(',', array_fill(0, count($categories), '?')) . ')')
            . (empty($categories) || empty($cities) ? '' : ' and ')
            . (empty($cities) ? '' : 'city_id in (' . join(',', array_fill(0, count($cities), '?')) . ')')
            . ((empty($categories) && empty($cities)) || empty($ad_type) ? '' : ' and ')
            . (empty($ad_type) ? '' : 'ad_type in (' . join(',', array_fill(0, count($ad_type), '?')) . ')');

        if(!empty($price)) {
            $query .= (empty($categories) && empty($cities) && empty($ad_type) ? '' : ' and ');
            $query .= (empty($price['start_price']) ? '' : 'price>?');
            $query .= (count($price) == 2 ? ' and ' : '');
            $query .= (empty($price['end_price']) ? '' : 'price<?');
        }

        $query .= ' ORDER BY ' . $order_by;
        return $query;
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
        $this->db->prepare('insert into estates(location, price, area, floor, is_furnished, description, phone, category_id, city_id, ad_type, main_image_id, created_at) values(?,?,?,?,?,?,?,?,?,?,?,?)');
        $this->db->execute(array($location, $price, $area, $floor, $isFurnished, $description, $phone, $categoryId, $cityId, $adType, $mainImageId, $createdAt));
        return $this->db->getAffectedRows();
    }

    public function edit($id, $name) {
        $this->db->prepare('update cities set name=? where id=?');
        $this->db->execute(array($name, $id));
        return $this->db->getAffectedRows();
    }
} 