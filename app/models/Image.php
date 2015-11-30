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
        $this->db->prepare('select id, name from images as i join estate_images as e on i.id=e.image_id where e.estate_id=?');
        $this->db->execute(array($estateId));
        return $this->db->fetchAllClass('Models\ViewModels\ImageViewModel');
    }

    public function delete($id) {
        $this->db->prepare('delete from images where id=?');
        $this->db->execute(array($id));
        return $this->db->getAffectedRows();
    }

    public function getById($id) {
        $this->db->prepare('select name from images where id=?');
        $this->db->execute(array($id));
        return $this->db->fetchRowClass('Models\ViewModels\ImageViewModel');
    }

    public function deleteMultiple(array $ids) {
        $ids = array_filter($ids);
        if (empty($ids)) {
            return 1;
        }
        $this->db->prepare('delete from images where id in (' . join(',', array_fill(0, count($ids), '?')) . ')');
        $this->db->execute($ids);
        return $this->db->getAffectedRows();
    }
} 