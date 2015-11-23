<?php

namespace Controllers;

use FW\View\View;
use FW\Security\Auth;
use FW\Security\Validation;
use FW\Session\Session;
use FW\Helpers\Redirect;
use Models\EstateAdBindingModel;

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

    public function index(){
        $result['title']='Estates';
        $result['isAdmin'] = Auth::isUserInRole(array('admin'));

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
            $result['categories'][] = $currentCategory;
        }

        $cities = $this->city->getCities();
        foreach($cities as $c) {
            $currentCity = array();
            $currentCity['text'] = $c['name'];
            $currentCity['options'] = array('value' => $c['id']);
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
        $validator = new Validation();
        $validator->setRule('required', $estate->location, null, 'location');
        $validator->setRule('required', $estate->area, null, 'area');
        $validator->setRule('required', $estate->phone, null, 'phone');
        if (!$validator->validate()) {
            Session::setError($validator->getErrors());
            Redirect::back();
        }

        if ($this->estate->add($name) !== 1) {
            Session::setError('something went wrong');
            Redirect::back();
        }

        Session::setMessage('done');
        Redirect::to('');
    }
} 