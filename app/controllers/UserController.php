<?php

namespace Controllers;
use FW\Security\Auth;
use FW\Input\InputData;
use FW\Helpers\Redirect;
use FW\Session\Session;
use FW\Security\Validation;
use FW\View\View;
use Models\UserModel;

class UserController{

    /**
     * @var \Models\Product
     */
    private $product;
    /**
     * @var \Models\User
     */
    private $user;

    public function getRegister() {
        $result['title']='Shop';
        View::make('user.register', $result);
        if (Auth::isAuth()) {
            View::appendTemplateToLayout('topBar', 'top_bar/user');
        } else {
            View::appendTemplateToLayout('topBar', 'top_bar/guest');
        }

        View::appendTemplateToLayout('header', 'includes/header')
            ->appendTemplateToLayout('footer', 'includes/footer')
            ->render();
    }

    public function postRegister(UserModel $user) {
        $validator = new Validation();
        $validator->setRule('required', $user->username, null, 'username');
        $validator->setRule('required', $user->password, null, 'password');
        $validator->setRule('email', $user->email, null, 'email');
        if (!$validator->validate()) {
            Session::setError($validator->getErrors());
            Redirect::back();
        }

        if (($result = $this->user->register($user->username, $user->email, $user->password)) !== 1) {
            Session::setError($result);
            Redirect::back();
        }

        Session::setMessage('registered successfully');
        Redirect::to('');
    }

    public function getLogin() {
        $result['title']='Shop';
        View::make('user.login', $result);
        if (Auth::isAuth()) {
            View::appendTemplateToLayout('topBar', 'top_bar/user');
        } else {
            View::appendTemplateToLayout('topBar', 'top_bar/guest');
        }

        View::appendTemplateToLayout('header', 'includes/header')
            ->appendTemplateToLayout('footer', 'includes/footer')
            ->render();
    }

    public function postLogin(UserModel $user) {
        if (!Auth::validateUser($user->username, $user->password)) {
            Session::setError('wrong credentials');
            Redirect::back();
        }

        Redirect::to('');
    }

    public function logout() {
        Auth::removeAuth();
        Redirect::to('');
    }

    public function getProfile() {
        $result['isEditor'] = Auth::isUserInRole(array('editor', 'admin'));
        $result['isAdmin'] = Auth::isUserInRole(array('admin'));
        $result['user'] = $this->user->getUser(Auth::getUserId());
        View::make('user.profile', $result);
        View::appendTemplateToLayout('topBar', 'top_bar/user');
        View::appendTemplateToLayout('header', 'includes/header')
            ->appendTemplateToLayout('footer', 'includes/footer')
            ->render();
    }

    public function editProfile(UserModel $user, $new_password) {
        $validator = new Validation();
        $validator->setRule('required', $user->email);
        $validator->setRule('required', $user->password);
        $validator->setRule('email', $user->email);
        if (!$validator->validate()) {
            Redirect::back();
        }

        if ($this->user->editUser(Auth::getUserId(), $user->email, $new_password, $user->password) !== 1) {
            Redirect::back();
        }

        Redirect::to('');
    }
} 