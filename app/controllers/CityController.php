<?php

namespace Controllers;

use FW\Security\Auth;
use FW\Helpers\Redirect;
use FW\Session\Session;
use FW\View\View;
use FW\Security\Validation;

class CityController {

    /**
     * @var \Models\City
     */
    private $city;

    public function index() {
        $result['title'] = 'Cities';
        $result['cities'] = $this->city->getCities();

        View::make('city.index', $result);
        if (Auth::isAuth()) {
            View::appendTemplateToLayout('topBar', 'top_bar/user');
        } else {
            View::appendTemplateToLayout('topBar', 'top_bar/guest');
        }

        View::appendTemplateToLayout('header', 'includes/header')
            ->appendTemplateToLayout('footer', 'includes/footer')
            ->render();
    }

    public function deleteCity($id) {
        if ($this->city->delete($id) !== 1) {
            Session::setError('can not delete this category');
            Redirect::back();
        }

        Session::setMessage('The city is deleted');
        Redirect::to('');
    }

    public function getAdd() {
        $result['title'] = 'Add City';
        $result['action'] = '/admin/city/add';
        $result['submit'] = 'Add';

        View::make('city.add', $result);
        if (Auth::isAuth()) {
            View::appendTemplateToLayout('topBar', 'top_bar/user');
        } else {
            View::appendTemplateToLayout('topBar', 'top_bar/guest');
        }

        View::appendTemplateToLayout('header', 'includes/header')
            ->appendTemplateToLayout('footer', 'includes/footer')
            ->render();
    }

    public function postAdd($name) {
        $validator = new Validation();
        $validator->setRule('required', $name, null, 'Name');
        if (!$validator->validate()) {
            Session::setError($validator->getErrors());
            Redirect::back();
        }

        if ($this->city->add($name) !== 1) {
            Session::setError('something went wrong');
            Redirect::back();
        }

        Session::setMessage('The city is added');
        Redirect::to('');
    }

    public function getEdit($id) {
        $result = array('city' => $this->city->getCity($id));
        $result['title'] = 'Edit city';
        $result['action'] = '/admin/city/' . $result['city']->id . '/edit';
        $result['submit'] = 'Edit';

        View::make('city.add', $result);
        if (Auth::isAuth()) {
            View::appendTemplateToLayout('topBar', 'top_bar/user');
        } else {
            View::appendTemplateToLayout('topBar', 'top_bar/guest');
        }

        View::appendTemplateToLayout('header', 'includes/header')
            ->appendTemplateToLayout('footer', 'includes/footer')
            ->render();
    }

    public function postEdit($id, $name) {
        $validator = new Validation();
        $validator->setRule('required', $name, null, 'Name');
        if (!$validator->validate()) {
            Session::setError($validator->getErrors());
            Redirect::back();
        }

        $this->city->edit($id, $name);

        Session::setMessage('The city is edited');
        Redirect::to('');
    }
} 