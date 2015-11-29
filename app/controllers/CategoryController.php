<?php

namespace Controllers;


use FW\Security\Auth;
use FW\Helpers\Redirect;
use FW\Session\Session;
use FW\View\View;
use FW\Security\Validation;

class CategoryController {

    /**
     * @var \Models\Category
     */
    private $category;

    public function index() {
        $result['title'] = 'Categories';

        $result['categories'] = $this->category->getCategories();

        View::make('category.index', $result);
        if (Auth::isAuth()) {
            View::appendTemplateToLayout('topBar', 'top_bar/user');
        } else {
            View::appendTemplateToLayout('topBar', 'top_bar/guest');
        }

        View::appendTemplateToLayout('header', 'includes/header')
            ->appendTemplateToLayout('footer', 'includes/footer')
            ->render();
    }

    public function deleteCategory($id) {
        if ($this->category->delete($id) !== 1) {
            Session::setError('Something went wrong. Try again');
            Redirect::back();
        }

        Session::setMessage('The category is deleted');
        Redirect::to('');
    }

    public function getAdd() {
        $result['title'] = 'Add Category';
        $result['action'] = '/admin/category/add';
        $result['submit'] = 'Add';

        View::make('category.add', $result);
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

        if ($this->category->add($name) !== 1) {
            Session::setError('something went wrong');
            Redirect::back();
        }

        Session::setMessage('The category is added');
        Redirect::to('');
    }

    public function getEdit($id) {
        $result = array('category' => $this->category->getCategory($id));
        $result['title'] = 'Edit Category';
        $result['action'] = '/admin/category/' . $result['category']->id . '/edit';
        $result['submit'] = 'Edit';

        View::make('category.add', $result);
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

        $this->category->edit($id, $name);

        Session::setMessage('The category is edited');
        Redirect::to('');
    }
} 