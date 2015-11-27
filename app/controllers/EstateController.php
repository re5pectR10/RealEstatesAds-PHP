<?php

namespace Controllers;

use FW\Helpers\Common;
use FW\Input\InputData;
use FW\Security\IValidator;
use FW\View\View;
use FW\Security\Auth;
use FW\Security\Validation;
use FW\Session\Session;
use FW\Helpers\Redirect;
use Models\EstateAdBindingModel;
use Models\SearchBindingModel;

class EstateController {

    private $fileDit;
    /**
     * @var \Models\Estate
     */
    private $estate;
    /**
     * @var \Models\Category
     */
    private $category;
    /**
     * @var \Models\City
     */
    private $city;
    /**
     * @var \Models\Image
     */
    private $image;
    /**
     * @var \Models\User
     */
    private $user;

    public function __construct() {
        $this->fileDit = Common::getPublicFilesDir() . 'images/';
    }

    public function index(SearchBindingModel $searchCriteria){
        $result['title']='Estates';
        $result['isAdmin'] = Auth::isUserInRole(array('admin'));

        $result['estates'] = array();
        $result['categories'] = $this->category->getCategories();
        $result['cities'] = $this->city->getCities();
        $result['search'] = $searchCriteria;

        if($searchCriteria->sort_type !== null){
            switch($searchCriteria->sort_type){
                case 0:
                    $orderCriteria = 'price';
                    break;
                case 1:
                    $orderCriteria = 'area/price';
                    break;
                default:
                    $orderCriteria = 'created_at';
            }

            switch($searchCriteria->furnished){
                case 1:
                    $is_furnished = array(0);
                    break;
                case 2:
                    $is_furnished = array(1);
                    break;
                default:
                    $is_furnished = array();
            }

            $result['estates'] = $this->estate->getEstates(
                isset($searchCriteria->category_id) ? $searchCriteria->category_id : array(),
                isset($searchCriteria->city_id) ? $searchCriteria->city_id : array(),
                isset($searchCriteria->ad_type) ? $searchCriteria->ad_type : array(),
                $searchCriteria->start_price,
                $searchCriteria->end_price,
                $searchCriteria->start_area,
                $searchCriteria->end_area,
                $searchCriteria->start_floor,
                $searchCriteria->end_floor,
                $searchCriteria->location,
                $is_furnished,
                $searchCriteria->has_image,
                $orderCriteria
            );

            for($i = 0; $i < count($result['estates']); $i++) {
                $result['estates'][$i]['name'] = isset($result['estates'][$i]['name']) ? $result['estates'][$i]['name'] : 'No_image_available.jpg';
            }
        }

        $result['ad_type'] = array(
            array(
                'id' => 0,
                'name' => 'For Rent'
            ),
            array(
                'id' => 1,
                'name' => 'For Sale'
            )
        );

        $result['sort_type'] = array(
            array(
                'text' => 'Price',
                'options' => array(
                    'value' => 0
                )
            ),
            array(
                'text' => 'm2 / Price',
                'options' => array(
                    'value' => 1
                )
            ),
            array(
                'text' => 'Date',
                'options' => array(
                    'value' => 2,
                    'selected' => true
                )
            )
        );

        if(Auth::isAuth()) {
            $favorites = ($this->user->getFavourites(Auth::getUserId()));
            foreach($favorites as $f) {
                $result['userFavourite'][] = $f['estate_id'];
            }
        } else {
            $result['userFavourite'] = Session::get('favourites');
        }

        $result['userFavourite'] = is_array($result['userFavourite']) ? $result['userFavourite'] : array();

        View::make('index', $result);
        if (Auth::isAuth()) {
            View::appendTemplateToLayout('topBar', 'top_bar.user');
        } else {
            View::appendTemplateToLayout('topBar', 'top_bar.guest');
        }

        View::appendTemplateToLayout('header', 'includes.header')
            ->appendTemplateToLayout('footer', 'includes.footer')
            ->render();
    }

    public function details($id) {
        $result['estate'] = $this->estate->getEstate($id);
        $result['estate']['images'] = $this->image->getImagesByEstate($id);
        $result['estate']['main_image'] = isset($result['estate']['main_image']) ? $result['estate']['main_image'] : 'No_image_available.jpg';

        View::make('estate.details', $result);
        if (Auth::isAuth()) {
            View::appendTemplateToLayout('topBar', 'top_bar.user');
        } else {
            View::appendTemplateToLayout('topBar', 'top_bar.guest');
        }

        View::appendTemplateToLayout('header', 'includes.header')
            ->appendTemplateToLayout('footer', 'includes.footer')
            ->render();
    }

    public function getAdd() {
        $result['title']='Add';
        $result['action'] = '/admin/estate/add';
        $result['submit'] = 'add';
        $categories = $this->category->getCategories();
        foreach($categories as $c) {
            $currentCategory = array();
            $currentCategory['text'] = $c['name'];
            $currentCategory['options'] = array('value' => $c['id']);
            if(isset(Session::oldInput()['category_id']) && Session::oldInput()['category_id'] == $c['id']){
                $currentCategory['options']['selected'] = 'true';
            }

            $result['categories'][] = $currentCategory;
        }

        $result['categories'] = isset($result['categories']) ? $result['categories'] : array();
        $cities = $this->city->getCities();
        foreach($cities as $c) {
            $currentCity = array();
            $currentCity['text'] = $c['name'];
            $currentCity['options'] = array('value' => $c['id']);
            if(isset(Session::oldInput()['city_id']) && Session::oldInput()['city_id'] == $c['id']){
                $currentCity['options']['selected'] = 'true';
            }

            $result['cities'][] = $currentCity;
        }

        $result['cities'] = isset($result['cities']) ? $result['cities'] : array();
        View::make('estate.add', $result);
        if (Auth::isAuth()) {
            View::appendTemplateToLayout('topBar', 'top_bar/user');
        } else {
            View::appendTemplateToLayout('topBar', 'top_bar/guest');
        }

        View::appendTemplateToLayout('header', 'includes/header')
            ->appendTemplateToLayout('footer', 'includes/footer')
            ->render();
    }

    public function postAdd(EstateAdBindingModel $estate) {
        $estate->images = $this->reArrayFiles($estate->images);
        $validator = $this->validateEstateAd(new Validation(), $estate);

        if (!$validator->validate()) {
            Session::setError($validator->getErrors());
            Redirect::back();
        }

        $imageId = null;
        if(isset($estate->main_image) && !empty($estate->main_image['name'])) {
            $imageId = $this->saveFile($estate->main_image);
        }

        $estateId =$this->estate->add($estate->location,
            $estate->price,
            $estate->area,
            $estate->floor,
            $estate->is_furnished,
            $estate->description,
            $estate->phone,
            $estate->category_id,
            $estate->city_id,
            $estate->ad_type,
            $imageId,
            date("Y-m-d H:i:s"));

        if (empty($estateId)) {
            Session::setError('something went wrong');
            Redirect::back();
        }

        foreach($estate->images as $image) {
            $imageId = $this->saveFile($image);
            if(!empty($imageId)) {
                $this->image->addImageToEstate($estateId, $imageId);
            }
        }

        Session::setMessage('Estate Ad is added successfully');
        Redirect::to('');
    }

    function reArrayFiles(&$file_post) {

        $file_ary = array();
        $file_count = count($file_post['name']);
        $file_keys = array_keys($file_post);

        for ($i=0; $i<$file_count; $i++) {
            foreach ($file_keys as $key) {
                $file_ary[$i][$key] = $file_post[$key][$i];
            }
        }

        return $file_ary;
    }

    private function saveFile($file){
        $fileName = trim(com_create_guid(), '{}');
        $filePath = $this->fileDit . $fileName;
        if(move_uploaded_file($file['tmp_name'], $filePath . '.' . pathinfo($file['name'], PATHINFO_EXTENSION))) {
            return $this->image->add($fileName . '.' . pathinfo($file['name'], PATHINFO_EXTENSION));
        }

        return false;
    }

    public function getEdit($id){
        $estate = $this->estate->getEstate($id);
        if($estate==null){
            Session::setError('The estate id is incorrect');
            Redirect::to('');
        }

        $result['estate'] = $estate;
        $result['estate']['images'] = $this->image->getImagesByEstate($id);
        $result['title']='Edit';
        $result['action'] = '/admin/estate/' . $estate['id'] . '/edit';
        $result['submit'] = 'edit';
        $categories = $this->category->getCategories();
        foreach($categories as $c) {
            $currentCategory = array();
            $currentCategory['text'] = $c['name'];
            $currentCategory['options'] = array('value' => $c['id']);
            if(isset($result['estate']) && $result['estate']['category_id'] == $c['id']){
                $currentCategory['options']['selected'] = 'true';
            } else if(isset(Session::oldInput()['category_id']) && Session::oldInput()['category_id'] == $c['id']){
                $currentCategory['options']['selected'] = 'true';
            }

            $result['categories'][] = $currentCategory;
        }

        $result['categories'] = isset($result['categories']) ? $result['categories'] : array();
        $cities = $this->city->getCities();
        foreach($cities as $c) {
            $currentCity = array();
            $currentCity['text'] = $c['name'];
            $currentCity['options'] = array('value' => $c['id']);
            if(isset($result['estate']) && $result['estate']['city_id'] == $c['id']){
                $currentCity['options']['selected'] = 'true';
            } else if(isset(Session::oldInput()['city_id']) && Session::oldInput()['city_id'] == $c['id']){
                $currentCity['options']['selected'] = 'true';
            }

            $result['cities'][] = $currentCity;
        }

        $result['cities'] = isset($result['cities']) ? $result['cities'] : array();
        View::make('estate.add', $result);
        if (Auth::isAuth()) {
            View::appendTemplateToLayout('topBar', 'top_bar/user');
        } else {
            View::appendTemplateToLayout('topBar', 'top_bar/guest');
        }

        View::appendTemplateToLayout('header', 'includes/header')
            ->appendTemplateToLayout('footer', 'includes/footer')
            ->render();
    }

    public function postEdit($id, EstateAdBindingModel $estate) {
        $estate->images = $this->reArrayFiles($estate->images);
        $validator = $this->validateEstateAd(new Validation(), $estate);

        if (!$validator->validate()) {
            Session::setError($validator->getErrors());
            Redirect::back();
        }

        $imageId = $this->estate->getMainImageId($id)['main_image_id'];
        if(isset($estate->main_image) && !empty($estate->main_image['name'])) {
            if(empty($imageId)) {
                $imageId = $this->saveFile($estate->main_image);
            } else {
                Session::setError('You have to delete the existed main image');
                Redirect::back();
            }
        }

        $this->estate->edit($id, $estate->location,
            $estate->price,
            $estate->area,
            $estate->floor,
            $estate->is_furnished,
            $estate->description,
            $estate->phone,
            $estate->category_id,
            $estate->city_id,
            $estate->ad_type,
            $imageId);

        foreach($estate->images as $image) {
            $imageId = $this->saveFile($image);
            if(!empty($imageId)) {
                $this->image->addImageToEstate($id, $imageId);
            }
        }

        Session::setMessage('Estate Ad is edited successfully');
        Redirect::to('');
    }

    /**
     * @param IValidator $validator
     * @param EstateAdBindingModel $estate
     * @return IValidator
     */
    private function validateEstateAd(IValidator $validator, EstateAdBindingModel $estate){
        $validator->setRule('required', $estate->area, null, 'Area');
        $validator->setRule('gtOrEqual', $estate->area, 5, 'Area');
        $validator->setRule('ltOrEqual', $estate->area, 500, 'Area');
        $validator->setRule('int', $estate->area, null, 'Area');

        $validator->setRule('required', $estate->price, null, 'Price');
        $validator->setRule('gtOrEqual', $estate->price, 1, 'Price');
        $validator->setRule('ltOrEqual', $estate->price, 10000000, 'Price');
        $validator->setRule('int', $estate->price, null, 'Price');

        $validator->setRule('required', $estate->location, null, 'Location');
        $validator->setRule('maxlength', $estate->location, 30, 'Location');
        $validator->setRule('minlength', $estate->location, 3, 'Location');

        $validator->setRule('required', $estate->floor, null, 'Floor');
        $validator->setRule('gtOrEqual', $estate->floor, 0, 'Floor');
        $validator->setRule('ltOrEqual', $estate->floor, 100, 'Floor');
        $validator->setRule('int', $estate->floor, null, 'Floor');

        $validator->setRule('required', $estate->phone, null, 'Phone');
        $validator->setRule('maxlength', $estate->phone, 20, 'Phone');

        $validator->setRule('required', $estate->description, null, 'Description');
        $validator->setRule('minlength', $estate->description, 5, 'Description');
        $validator->setRule('maxlength', $estate->description, 5000, 'Description');

        $validator->setRule('required', $estate->is_furnished, null, 'Is Furnished');

        $validator->setRule('required', $estate->ad_type, null, 'Ad Type');

        $validator->setRule('required', $estate->category_id, null, 'Category');

        $validator->setRule('required', $estate->city_id, null, 'City');

        $validator->setRule('mimeTypes', $estate->main_image, 'jpg,gif', 'Main Image');
        $validator->setRule('lt', $estate->main_image['size'], 20971520, 'Main Image');

        foreach($estate->images as $image) {
            $validator->setRule('mimeTypes', $image, 'jpg,gif', 'Main Image');
            $validator->setRule('lt', $image['size'], 20971520, 'Main Image');
        }

        return $validator;
    }
} 