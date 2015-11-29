<?php

namespace Controllers;

use \FW\View\View;
use \FW\Security\Auth;
use \FW\Session\Session;
use \FW\Helpers\Redirect;
use \FW\Security\Validation;
use \FW\Security\IValidator;
use Models\BindingModels\MessageBindingModel;

class MessageController {

    /**
     * @var \Models\Estate
     */
    private $estate;
    /**
     * @var \Models\Message
     */
    private $message;

    public function index($orderBy = 'created', $type = 'asc') {
        $result['title'] = 'Messages';
        $result['messages'] = $this->message->getAll($orderBy, $type);
        $result['currentOrder'] = $orderBy == 'name' ? $orderBy : 'created';
        $result['currentSort'] = $type == 'desc' ? $type : 'asc';

        View::make('message.index', $result);
        if (Auth::isAuth()) {
            View::appendTemplateToLayout('topBar', 'top_bar/user');
        } else {
            View::appendTemplateToLayout('topBar', 'top_bar/guest');
        }

        View::appendTemplateToLayout('header', 'includes/header')
            ->appendTemplateToLayout('footer', 'includes/footer')
            ->render();
    }

    public function get($id) {
        $result['title'] = 'Message';
        /* @var $message \Models\ViewModels\MessageViewModel */
        $message = $this->message->getById($id);

        if(!$message->is_read) {
            $this->message->markAsRead($id);
        }

        $result['message'] = $message;

        View::make('message.details', $result);
        if (Auth::isAuth()) {
            View::appendTemplateToLayout('topBar', 'top_bar/user');
        } else {
            View::appendTemplateToLayout('topBar', 'top_bar/guest');
        }

        View::appendTemplateToLayout('header', 'includes/header')
            ->appendTemplateToLayout('footer', 'includes/footer')
            ->render();
    }

    public function getAdd($id) {
        $result['title'] = 'Send Messages';
        /* @var $estate \Models\ViewModels\EstateViewModel */
        $estate = $this->estate->getEstate($id);
        $result['estateInfo'] = 'ID: ' . $estate->id . '; Category: ' . $estate->category . '; Type: ' . ($estate->ad_type == 1 ? 'For Sale' : 'For Rent')
            . '; City: ' . $estate->city . '; Location: ' . $estate->location . '; Price: ' . $estate->price . ' EUR';

        View::make('message.add', $result);
        if (Auth::isAuth()) {
            View::appendTemplateToLayout('topBar', 'top_bar/user');
        } else {
            View::appendTemplateToLayout('topBar', 'top_bar/guest');
        }

        View::appendTemplateToLayout('header', 'includes/header')
            ->appendTemplateToLayout('footer', 'includes/footer')
            ->render();
    }

    public function postAdd(MessageBindingModel $message) {
        $validator = $this->validateMessage(new Validation(), $message);

        if (!$validator->validate()) {
            Session::setError($validator->getErrors());
            Redirect::back();
        }

        $this->message->add($message->first_name,$message->last_name,$message->email,$message->phone,$message->content,$message->about, date("Y-m-d H:i:s"), false);

        Session::setMessage('Message is sent successfully');
        Redirect::to('');
    }

    /**
     * @param IValidator $validator
     * @param MessageBindingModel $message
     * @return IValidator
     */
    private function validateMessage(IValidator $validator, MessageBindingModel $message){
        $validator->setRule('required', $message->first_name, null, 'First Name');
        $validator->setRule('minlength', $message->first_name, 2, 'First Name');
        $validator->setRule('maxlength', $message->first_name, 30, 'First Name');

        $validator->setRule('required', $message->last_name, null, 'Last Name');
        $validator->setRule('minlength', $message->last_name, 2, 'Last Name');
        $validator->setRule('maxlength', $message->last_name, 30, 'Last Name');

        $validator->setRule('required', $message->phone, null, 'Phone');
        $validator->setRule('minlength', $message->phone, 3, 'Phone');
        $validator->setRule('maxlength', $message->phone, 20, 'Phone');

        $validator->setRule('required', $message->email, null, 'Email');
        $validator->setRule('email', $message->email, null, 'Email');

        $validator->setRule('required', $message->content, null, 'Content');
        $validator->setRule('maxlength', $message->content, 1000, 'Content');

        $validator->setRule('maxlength', $message->about, 200, 'About');

        return $validator;
    }
} 