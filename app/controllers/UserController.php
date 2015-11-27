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
     * @var \Models\Estate
     */
    private $estate;
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

        Session::setMessage('The profile is edited');
        Redirect::to('');
    }

    public function addToFavourites($id) {

        if($this->estate->estateExist($id) !== 1) {
            Session::setError('The estate id is invalid');
            Redirect::back();
        }

        if(Auth::isAuth()) {
            $this->user->addEstateToFavourites(Auth::getUserId(), $id);
        } else {
            $favourites = Session::get('favourites') ? Session::get('favourites') : array();
            $favourites[] = $id;
            Session::set('favourites', $favourites);
        }

        Session::setMessage('Added to favourites');
        Redirect::back();
    }

    public function removeFromFavourites($id) {

        if(Auth::isAuth()) {
            $this->user->delteEstateFromFavourites(Auth::getUserId(), $id);
        } else {
            $favourites = Session::get('favourites') ? Session::get('favourites') : array();
            if(($key = array_search($id, $favourites)) !== false) {
                unset($favourites[$key]);
            }
            Session::set('favourites', $favourites);
        }

        Session::setMessage('Deleted successfully');
        Redirect::back();
    }

    public function getFavourites() {

        $userFavourite = array();
        if(Auth::isAuth()) {
            $favorites = ($this->user->getFavourites(Auth::getUserId()));
            foreach($favorites as $f) {
                $userFavourite[] = $f['estate_id'];
            }
        } else {
            $userFavourite = Session::get('favourites');
        }

        if(!empty($userFavourite)) {
            $result['estates'] = $this->estate->getFavoritesEstates($userFavourite);
        } else {
            $result['estates'] = array();
        }


        View::make('user.favorites', $result);
        View::appendTemplateToLayout('topBar', 'top_bar/user');
        View::appendTemplateToLayout('header', 'includes/header')
            ->appendTemplateToLayout('footer', 'includes/footer')
            ->render();
    }
} 