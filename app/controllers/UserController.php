<?php

namespace Controllers;
use FW\Helpers\Common;
use FW\Security\Auth;
use FW\Helpers\Redirect;
use FW\Session\Session;
use FW\Security\Validation;
use FW\View\View;
use Models\Model;
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
        $result['title'] = 'Sign In';
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
        $validator->setRule('required', $user->username, null, 'Username');
        $validator->setRule('required', $user->password, null, 'Password');
        $validator->setRule('email', $user->email, null, 'Email');
        if (!$validator->validate()) {
            Session::setError($validator->getErrors());
            Redirect::back();
        }

        if (($result = $this->user->register($user->username, $user->email, Common::hashPassword($user->password))) !== 1) {
            Session::setError($result);
            Redirect::back();
        }

        Session::setMessage('Registered successfully');
        Redirect::to('');
    }

    public function getLogin() {
        $result['title'] = 'Log In';
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
            Session::setError('Wrong credentials');
            Redirect::back();
        }

        Redirect::to('');
    }

    public function logout() {
        Auth::removeAuth();
        Redirect::to('');
    }

    public function getProfile() {
        $result['title'] = 'Profile';
        $result['user'] = $this->user->getUser(Auth::getUserId());

        View::make('user.profile', $result);
        View::appendTemplateToLayout('topBar', 'top_bar/user');
        View::appendTemplateToLayout('header', 'includes/header')
            ->appendTemplateToLayout('footer', 'includes/footer')
            ->render();
    }

    public function editProfile(UserModel $user, $new_password) {
        $validator = new Validation();
        $validator->setRule('required', $user->email, null, 'Email');
        $validator->setRule('required', $user->password, null, 'Current Password');
        $validator->setRule('email', $user->email, null, 'Email');
        if (!$validator->validate()) {
            Session::setError($validator->getErrors());
            Redirect::back();
        }

        /* @var $user \Models\UserModel */
        $userFromDb = $this->user->getUser(Auth::getUserId());
        if (!Common::verifyPassword($user->password, $userFromDb->password)) {
            Session::setError('Current password is not correct');
            Redirect::back();
        }

        if ($this->user->editUser(Auth::getUserId(), $user->email, Common::hashPassword($new_password)) !== 1) {
            Session::setError('Something is wrong. Try again.');
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
        $result['title'] = 'User Favorites';
        $userFavourite = array();

        if(Auth::isAuth()) {
            $favorites = ($this->user->getFavourites(Auth::getUserId()));
            foreach($favorites as $f) {
                $userFavourite[] = $f['estate_id'];
            }

            View::appendTemplateToLayout('topBar', 'top_bar/user');
        } else {
            $userFavourite = Session::get('favourites');
            View::appendTemplateToLayout('topBar', 'top_bar/guest');
        }

        /* @var $estates \Models\ViewModels\EstateBasicViewModel[] */
        if(!empty($userFavourite)) {
            $estates = $this->estate->getFavoritesEstates($userFavourite);
            foreach($estates as $estate){
                $estate->image = EstateController::setEstateMainImage($estate);
                $estate->thumbnailName = EstateController::setImageThumb($estate->image);
            }
            $result['estates'] = $estates;
        } else {
            $result['estates'] = array();
        }

        View::make('user.favorites', $result);
        View::appendTemplateToLayout('header', 'includes/header')
            ->appendTemplateToLayout('footer', 'includes/footer')
            ->render();
    }
} 