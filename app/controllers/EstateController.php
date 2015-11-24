<?php

namespace Controllers;

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

    public function index(SearchBindingModel $searchCriteria){
        $result['title']='Estates';
        $result['isAdmin'] = Auth::isUserInRole(array('admin'));

        $result['estates'] = array();
        $result['categories'] = $this->category->getCategories();
        $result['cities'] = $this->city->getCities();
        var_dump($searchCriteria);

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

            $result['estates'] = $this->estate->getEstates(
                isset($searchCriteria->category_id) ? $searchCriteria->category_id : array(),
                isset($searchCriteria->city_id) ? $searchCriteria->city_id : array(),
                isset($searchCriteria->ad_type) ? $searchCriteria->ad_type : array(),
                $orderCriteria
            );
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
        $validator = $this->validateEstateAd(new Validation(), $estate);

        if (!$validator->validate()) {
            Session::setError($validator->getErrors());
            Redirect::back();
        }

        if ($this->estate->add($estate->location,
                $estate->price,
                $estate->area,
                $estate->floor,
                $estate->is_furnished,
                $estate->description,
                $estate->phone,
                $estate->category_id,
                $estate->city_id,
                $estate->ad_type,
                null,
                date("Y-m-d")) !== 1) {
            Session::setError('something went wrong');
            Redirect::back();
        }

        Session::setMessage('Estate Ad is added successfully');
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

        return $validator;
    }
} 