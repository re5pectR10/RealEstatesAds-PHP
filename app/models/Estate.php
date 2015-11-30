<?php

namespace Models;


class Estate extends Model{

    public function getEstate($id) {
        $this->db->prepare('select e.id, e.location, e.price, e.area, e.floor, e.is_furnished, e.description, e.phone, c.name as category, s.name as city, e.ad_type, e.main_image_id, i.name as image
            from estates as e
            join categories as c on c.id=e.category_id
            join cities as s on s.id=e.city_id
            left join images as i on i.id=e.main_image_id
            where e.id=?');

        $this->db->execute(array($id));
        return $this->db->fetchRowClass('Models\ViewModels\EstateViewModel');
    }

    public function getEstates(array $categories, array $cities, array $ad_type, $start_price, $end_price, $start_area, $end_area, $start_floor, $end_floor, $location, array $furnished, $has_image,  $order_by){
        $price = array_filter(array('start_price' => $start_price, 'end_price' => $end_price));
        $area = array_filter(array('start_area' => $start_area, 'end_area' => $end_area));
        $floor = array_filter(array('start_floor' => $start_floor, 'end_floor' => $end_floor));
        $location = array_filter(array($location));
        if(isset($location[0])){
            $location[0] = '%' . $location[0] . '%';
        }
        $this->db->prepare($this->buildGetEstatesQuery($categories, $cities, $ad_type, $price, $area, $floor, $location, $furnished, $has_image, $order_by));
        $this->db->execute(array_merge($categories, $cities, $ad_type, array_values($price), array_values($area), array_values($floor), array_values($location), array_values($furnished)));
        return $this->db->fetchAllClass('Models\ViewModels\EstateBasicViewModel');
    }

    private function buildGetEstatesQuery(array $categories, array $cities, array $ad_type, array $price, array $area, array $floor, array $location, array $furnished, $has_image, $order_by){
        $query = 'select e.id,e.price,e.location,e.area,e.ad_type,i.name as image,c.name as city,cat.name as category from estates as e join cities as c on c.id=e.city_id join categories as cat on cat.id=e.category_id left join images as i on i.id=e.main_image_id'
            . (!(empty($categories) && empty($cities) && empty($ad_type) && empty($price) && empty($area) && empty($floor) && empty($location) && empty($furnished) && empty($has_image)) ? ' where ' : '')
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

        if(!empty($area)) {
            $query .= (empty($categories) && empty($cities) && empty($ad_type) && empty($price) ? '' : ' and ');
            $query .= (empty($area['start_area']) ? '' : 'area>?');
            $query .= (count($area) == 2 ? ' and ' : '');
            $query .= (empty($area['end_area']) ? '' : 'area<?');
        }

        if(!empty($floor)) {
            $query .= (empty($categories) && empty($cities) && empty($ad_type) && empty($price) && empty($area) ? '' : ' and ');
            $query .= (empty($floor['start_floor']) ? '' : 'floor>?');
            $query .= (count($floor) == 2 ? ' and ' : '');
            $query .= (empty($floor['end_floor']) ? '' : 'floor<?');
        }

        if(!empty($location)) {
            $query .= (empty($categories) && empty($cities) && empty($ad_type) && empty($price) && empty($area) && empty($floor) ? '' : ' and ');
            $query .= 'location like ?';
        }

        if(!empty($furnished)) {
            $query .= (empty($categories) && empty($cities) && empty($ad_type) && empty($price) && empty($area) && empty($floor) && empty($location) ? '' : ' and ');
            $query .= 'is_furnished = ?';
        }

        if(!empty($has_image)) {
            $query .= (empty($categories) && empty($cities) && empty($ad_type) && empty($price) && empty($area) && empty($floor) && empty($location) && empty($furnished) ? '' : ' and ');
            $query .= 'main_image_id is not null';
        }

        $query .= ' ORDER BY ' . $order_by;
        return $query;
    }

    public function getFavoritesEstates(array $ids) {
        $this->db->prepare('select e.id,e.price,e.location,e.area,e.ad_type,i.name as image,c.name as city,cat.name as category from estates as e join cities as c on c.id=e.city_id join categories as cat on cat.id=e.category_id left join images as i on i.id=e.main_image_id where e.id in ('. join(',', array_fill(0, count($ids), '?')) . ')');
        $this->db->execute($ids);
        return $this->db->fetchAllClass('Models\ViewModels\EstateBasicViewModel');
    }

    public function delete($id) {
        $this->db->prepare('delete from estates where id=?');
        $this->db->execute(array($id));
        return $this->db->getAffectedRows();
    }

    public function add($location, $price, $area, $floor, $isFurnished, $description, $phone, $categoryId, $cityId, $adType, $mainImageId, $createdAt) {
        $this->db->prepare('insert into estates(location, price, area, floor, is_furnished, description, phone, category_id, city_id, ad_type, main_image_id, created_at) values(?,?,?,?,?,?,?,?,?,?,?,?)');
        $this->db->execute(array($location, $price, $area, $floor, $isFurnished, $description, $phone, $categoryId, $cityId, $adType, $mainImageId, $createdAt));
        return $this->db->getLastInsertId();
    }

    public function edit($id, $location, $price, $area, $floor, $isFurnished, $description, $phone, $categoryId, $cityId, $adType, $mainImageId) {
        $this->db->prepare('update estates set location=?,price=?,area=?,floor=?,is_furnished=?,description=?,phone=?,category_id=?,city_id=?,ad_type=?,main_image_id=? where id=?');
        $this->db->execute(array($location, $price, $area, $floor, $isFurnished, $description, $phone, $categoryId, $cityId, $adType, $mainImageId, $id));
        return $this->db->getAffectedRows();
    }

    public function getMainImageId($id){
        $this->db->prepare('select main_image_id from estates where id=? and main_image_id is not null');
        $this->db->execute(array($id));
        return $this->db->fetchRowAssoc();
    }

    public function estateExist($id) {
        $this->db->prepare('select id from estates where id=?');
        $this->db->execute(array($id));
        return $this->db->getAffectedRows();
    }
} 