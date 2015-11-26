<?php

namespace Models;


class Image extends Model{

    public function add($path) {
        $this->db->prepare('insert into images(name) values(?)');
        $this->db->execute(array($path));
        return $this->db->getLastInsertId();
    }

    public function addImageToEstate($estateId, $imageId) {
        $this->db->prepare('insert into estate_images(estate_id,image_id) values(?,?)');
        $this->db->execute(array($estateId, $imageId));
        return $this->db->getAffectedRows();
    }

    public function getImagesByEstate($estateId) {
        $this->db->prepare('select id, name as image from images as i join estate_images as e on i.id=e.image_id where e.estate_id=?');
        $this->db->execute(array($estateId));
        return $this->db->fetchAllAssoc();
    }

    public function delete($id) {
        $this->db->prepare('delete from images where id=?');
        $this->db->execute(array($id));
        return $this->db->getAffectedRows();
    }

    public function getById($id) {
        $this->db->prepare('select name from images where id=?');
        $this->db->execute(array($id));
        return $this->db->fetchRowAssoc();
    }
} 