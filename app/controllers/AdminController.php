<?php

namespace Controllers;


use FW\Helpers\Redirect;
use FW\Session\Session;
use FW\View\View;

class AdminController {

    /**
     * @var \Models\User
     */
    private $user;

    public function getUsers() {
        $result['users'] = $this->user->getUsersWithRoles();
        $result['title'] = 'Users';

        View::make('admin.roles', $result);
        View::appendTemplateToLayout('topBar', 'top_bar/user');
        View::appendTemplateToLayout('header', 'includes/header')
            ->appendTemplateToLayout('footer', 'includes/footer')
            ->render();
    }

    public function setRole($id, $role) {
        if (!in_array($role, array('admin', 'editor', 'user'))) {
            Session::setError('incorrect role');
            Redirect::back();
        }

        if ($role == 'user') {
            if ($this->user->deleteUserRole($id) === 0) {
                Session::setError('something went wrong');
                Redirect::back();
            }

            Session::setMessage('Done');
            Redirect::to('/admin/users');
        }
        if ($this->user->setRole($id, $role) !== 1) {
            Session::setError('something went wrong');
            Redirect::back();
        }

        Session::setMessage('Done');
        Redirect::to('/admin/users');
    }
} 