<?php

namespace Controllers;

use FW\Helpers\Common;
use FW\Helpers\DependencyProvider;
use FW\Security\IValidator;
use FW\View\View;
use FW\Security\Auth;
use FW\Security\Validation;
use FW\Session\Session;
use FW\Helpers\Redirect;
use Models\BindingModels\EstateAdBindingModel;
use Models\SearchModel;
use Models\ViewModels\CategoryViewModel;
use Models\ViewModels\CityViewModel;
use Models\ViewModels\EstateViewModel;
use Models\ViewModels\ImageViewModel;

class EstateController {

    const IMAGE_THUMBNAIL_PREFIX = 'thumb';
    const IMAGE_THUMBNAIL_WIDTH = 300;
    const IMAGE_THUMBNAIL_HEIGHT = 200;
    const IMAGE_MAX_WIDTH_WITHOUT_RESIZE = 400;
    const IMAGE_MAX_HEIGHT_WITHOUT_RESIZE = 300;
    const IMAGE_DIR = 'images';
    const DEFAULT_IMAGE_NAME = 'No_image_available.jpg';
    const TWEAK_FACTOR = 1.8;

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

    function  __construct() {
        ini_set('memory_limit', '512M');
    }

    public function index(SearchModel $searchCriteria){
        $result['title'] = 'Estates';
        $result['estates'] = array();
        $result['categories'] = $this->category->getCategories();
        $result['cities'] = $this->city->getCities();
        $result['search'] = $searchCriteria;

        if($searchCriteria->sort_type !== null){
            $orderCriteria = $this->getOrderCriteria($searchCriteria);

            $is_furnished = $this->getIsFurnishedAsArray($searchCriteria);

            /* @var $estates \Models\ViewModels\EstateBasicViewModel[] */
            $estates = $this->estate->getEstates(
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

            foreach($estates as $estate) {
                $estate->image = $this->setEstateMainImage($estate);
                $estate->thumbnailName = $this->setImageThumb($estate->image);
            }

            $result['estates'] = $estates;
        }

        $result['ad_type'] = $this->setAdTypes();

        $result['sort_type'] = $this->setSortType();

        if($searchCriteria->sort_type!=null){
            $result['sort_type'][$searchCriteria->sort_type]['options']['selected'] = true;
        }else {
            $result['sort_type'][2]['options']['selected'] = true;
        }
        $result['userFavourite'] = $this->setUserFavorites();

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
        $result['title'] = 'Details';
        /* @var $estate \Models\ViewModels\EstateViewModel */
        $estate = $this->estate->getEstate($id);
        if (empty($estate)) {
            Session::setError('Wrong estate id');
            Redirect::to('');
        }
        $estate->images = $this->image->getImagesByEstate($id);
        foreach($estate->images as $i) {
            $i->thumbnailName = $this->setImageThumb($i->name);
        }
        $estate->image = $this->setEstateMainImage($estate);
        $estate->thumbnailName = $this->setImageThumb($estate->image);
        $result['estate'] = $estate;

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
        $result['title'] = 'Add';
        $result['action'] = '/admin/estate/add';
        $result['submit'] = 'Add';

        $result['categories'] = $this->setCategoryFormOptions($this->category->getCategories());
        $result['cities'] = $this->setCityFormOptions($this->city->getCities());

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
            $imageId = $this->addEstateMainImage($estate);
        }

        $estateId = $this->estate->add($estate->location,
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

        $this->addEstateAdditionalImages($estateId, $estate);

        Session::setMessage('Estate Ad is added successfully');
        Redirect::to('');
    }

    public function getEdit($id){
        $result['title'] = 'Edit';

        /* @var $estate \Models\ViewModels\EstateViewModel */
        $estate = $this->estate->getEstate($id);
        if($estate==null){
            Session::setError('The estate id is incorrect');
            Redirect::to('');
        }

        $estate->images = $this->image->getImagesByEstate($id);
        foreach($estate->images as $i) {
            $i->thumbnailName = $this->setImageThumb($i->name);
        }
        $result['estate'] = $estate;
        $result['action'] = '/admin/estate/' . $estate->id . '/edit';
        $result['submit'] = 'Edit';

        $result['categories'] = $this->setCategoryFormOptions($this->category->getCategories(), $estate);
        $result['cities'] = $this->setCityFormOptions($this->city->getCities(), $estate);

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
        if($this->estate->estateExist($id) !== 1) {
            Session::setError('The estate id is invalid');
            Redirect::back();
        }

        $estate->images = $this->reArrayFiles($estate->images);
        $validator = $this->validateEstateAd(new Validation(), $estate);

        if (!$validator->validate()) {
            Session::setError($validator->getErrors());
            Redirect::back();
        }

        $imageId = $this->estate->getMainImageId($id)['main_image_id'];
        if(isset($estate->main_image) && !empty($estate->main_image['name'])) {
            if(empty($imageId)) {
                $imageId = $this->addEstateMainImage($estate);
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

        $this->addEstateAdditionalImages($id, $estate);

        Session::setMessage('Estate Ad is edited successfully');
        Redirect::to('');
    }

    public function delete($id) {
        /* @var $estate \Models\ViewModels\EstateViewModel */
        $estate = $this->estate->getEstate($id);
        /* @var $images \Models\ViewModels\ImageViewModel[] */
        $images = $this->image->getImagesByEstate($id);
        if ($this->estate->delete($id) !== 1) {
            Session::setError('something went wrong. try again');
            Redirect::back();
        }

        /* @var $imageController \Controllers\ImageController */
        $imageController = DependencyProvider::injectDependenciesToController(new ImageController());
        if (isset($estate->main_image_id)) {
            $mainImg = new ImageViewModel();
            $mainImg->id = $estate->main_image_id;
            $mainImg->name = $estate->image;
            $images[] = $mainImg;
        }

        $imageController->removeMultiple($images);

        Session::setMessage('The estate is deleted');
        Redirect::to('');
    }

    /**
     * @param EstateAdBindingModel $estate
     * @return null|string
     */
    public function addEstateMainImage(EstateAdBindingModel $estate) {
        $imageId = null;
        $imageName = $this->saveFile($estate->main_image, EstateController::IMAGE_DIR . DIRECTORY_SEPARATOR);
        if (!empty($imageName)) {
            $imageId = $this->image->add($imageName);
            if ($this->validateImageDimensions(EstateController::IMAGE_DIR . DIRECTORY_SEPARATOR . $imageName, EstateController::IMAGE_MAX_WIDTH_WITHOUT_RESIZE, EstateController::IMAGE_MAX_HEIGHT_WITHOUT_RESIZE)) {
                $this->createImageThumbnail($imageName,
                    EstateController::IMAGE_THUMBNAIL_WIDTH,
                    EstateController::IMAGE_THUMBNAIL_HEIGHT,
                    EstateController::IMAGE_THUMBNAIL_PREFIX,
                    EstateController::IMAGE_DIR
                );
            }
        }

        return $imageId;
    }

    /**
     * @param $id
     * @param EstateAdBindingModel $estate
     */
    public function addEstateAdditionalImages($id, EstateAdBindingModel $estate) {
        foreach($estate->images as $image) {
            $imageName = $this->saveFile($image, EstateController::IMAGE_DIR . DIRECTORY_SEPARATOR);
            if(!empty($imageName)) {
                $imageId = $this->image->add($imageName);
                $this->image->addImageToEstate($id, $imageId);
                if ($this->validateImageDimensions(EstateController::IMAGE_DIR . DIRECTORY_SEPARATOR . $imageName, EstateController::IMAGE_MAX_WIDTH_WITHOUT_RESIZE, EstateController::IMAGE_MAX_HEIGHT_WITHOUT_RESIZE)) {
                    $this->createImageThumbnail($imageName,
                        EstateController::IMAGE_THUMBNAIL_WIDTH,
                        EstateController::IMAGE_THUMBNAIL_HEIGHT,
                        EstateController::IMAGE_THUMBNAIL_PREFIX,
                        EstateController::IMAGE_DIR
                    );
                }
            }
        }
    }

    public function checkImageResizeNotExceedMemoryLimit($size) {
        return (($size[0] * $size[1] * 3) * EstateController::TWEAK_FACTOR) +
            ((EstateController::IMAGE_THUMBNAIL_WIDTH * EstateController::IMAGE_THUMBNAIL_HEIGHT * 3) * EstateController::TWEAK_FACTOR)
            + 5 * 1024 * 1024 < Common::getMemoryLimit();
    }

    public function validateImageDimensions($filename, $width, $height) {
        $size = getimagesize($filename);
        return $this->checkImageResizeNotExceedMemoryLimit($size) && ($size[0] > $width || $size[1] > $height);
    }

    public function createImageThumbnail($imageName, $width, $height, $prefix, $imagesDir) {
        list($width_orig, $height_orig) = getimagesize($imagesDir . DIRECTORY_SEPARATOR . $imageName);

        $ratio_orig = $width_orig/$height_orig;

        if ($width/$height > $ratio_orig) {
            $width = $height*$ratio_orig;
        } else {
            $height = $width/$ratio_orig;
        }
        $image_p = imagecreatetruecolor($width, $height);
        $type = exif_imagetype($imagesDir . DIRECTORY_SEPARATOR . $imageName);

        $image = $type == 1 ? imagecreatefromgif($imagesDir . DIRECTORY_SEPARATOR . $imageName) : imagecreatefromjpeg($imagesDir . DIRECTORY_SEPARATOR . $imageName);
        imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
        $type == 1 ? imagegif($image_p, $imagesDir . DIRECTORY_SEPARATOR . $prefix . $imageName, 100) : imagejpeg($image_p, $imagesDir . DIRECTORY_SEPARATOR . $prefix . $imageName, 100);
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

    private function saveFile($file, $dir){
        $fileName = uniqid();//trim(com_create_guid(), '{}');
        $filePath = $dir . $fileName;
        if(move_uploaded_file($file['tmp_name'], $filePath . '.' . pathinfo($file['name'], PATHINFO_EXTENSION))) {
            return $fileName . '.' . pathinfo($file['name'], PATHINFO_EXTENSION);
        }

        return false;
    }

    /**
     * @param CategoryViewModel[] $categories
     * @param EstateViewModel $estate
     * @return array
     */
    public function setCategoryFormOptions(array $categories, EstateViewModel $estate = null) {
        $options = array();
        foreach($categories as $c) {
            $currentCategory = array();
            $currentCategory['text'] = $c->name;
            $currentCategory['options'] = array('value' => $c->id);
            if(isset($estate) && $estate->category == $c->id){
                $currentCategory['options']['selected'] = 'true';
            } else if(isset(Session::oldInput()['category_id']) && Session::oldInput()['category_id'] == $c->id){
                $currentCategory['options']['selected'] = 'true';
            }

            $options[] = $currentCategory;
        }

        return $options;
    }

    /**
     * @param CityViewModel[] $cities
     * @param EstateViewModel $estate
     * @return array
     */
    public function setCityFormOptions(array $cities, EstateViewModel $estate = null) {
        $options = array();
        foreach($cities as $c) {
            $currentCity = array();
            $currentCity['text'] = $c->name;
            $currentCity['options'] = array('value' => $c->id);
            if(isset($estate) && $estate->city == $c->id){
                $currentCity['options']['selected'] = 'true';
            } else if(isset(Session::oldInput()['city_id']) && Session::oldInput()['city_id'] == $c->id){
                $currentCity['options']['selected'] = 'true';
            }

            $options[] = $currentCity;
        }

        return $options;
    }

    /**
     * @param SearchModel $search
     * @return string
     */
    public function getOrderCriteria(SearchModel $search) {
        switch($search->sort_type){
            case 0:
                return 'price';
                break;
            case 1:
                return 'price/area';
                break;
            default:
                return 'created_at desc';
        }
    }

    /**
     * @param SearchModel $search
     * @return array
     */
    public function getIsFurnishedAsArray(SearchModel $search) {
        switch($search->furnished){
            case 1:
                return array(0);
                break;
            case 2:
                return array(1);
                break;
            default:
                return array();
        }
    }

    /**
     * @param $estate \Models\ViewModels\EstateBasicViewModel
     * @return string
     */
    public static function setEstateMainImage($estate) {
        if(isset($estate->image)) {
            return $estate->image;
        }

        return EstateController::DEFAULT_IMAGE_NAME;
    }

    public static function setImageThumb($imageName) {
        if(file_exists(EstateController::IMAGE_DIR . DIRECTORY_SEPARATOR . EstateController::IMAGE_THUMBNAIL_PREFIX . $imageName) && $imageName != EstateController::DEFAULT_IMAGE_NAME) {
            return EstateController::IMAGE_THUMBNAIL_PREFIX . $imageName;
        }

        return null;
    }
    /**
     * @return array
     */
    public function setUserFavorites() {
        $userFavorites = array();
        if(Auth::isAuth()) {
            $favorites = ($this->user->getFavourites(Auth::getUserId()));
            foreach($favorites as $f) {
                $userFavorites[] = $f['estate_id'];
            }
        } else {
            $userFavorites = Session::get('favourites');
        }

        return is_array($userFavorites) ? $userFavorites : array();
    }

    /**
     * @return array
     */
    public function setAdTypes() {
        return array(
            array(
                'id' => 0,
                'name' => 'For Rent'
            ),
            array(
                'id' => 1,
                'name' => 'For Sale'
            )
        );
    }

    /**
     * @return array
     */
    public function setSortType() {
        return array(
            array(
                'text' => 'Price',
                'options' => array(
                    'value' => 0
                )
            ),
            array(
                'text' => 'Price / m2',
                'options' => array(
                    'value' => 1
                )
            ),
            array(
                'text' => 'Date',
                'options' => array(
                    'value' => 2
                )
            )
        );
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

        $validator->setRule('postMaxSize', ini_get('post_max_size'), null, null);

        foreach($estate->images as $image) {
            $validator->setRule('mimeTypes', $image, 'jpg,gif', 'Main Image');
            $validator->setRule('lt', $image['size'], 20971520, 'Main Image');
        }

        return $validator;
    }
} 