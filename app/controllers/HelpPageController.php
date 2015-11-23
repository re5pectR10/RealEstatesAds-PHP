<?php

namespace Controllers;

use FW\Helpers\HelpPage;
use FW\Route\Route;
use FW\View\View;


class HelpPageController {

    public function index() {
        $help = new HelpPage(Route::getRouters());
        $helpData = $help->getData();
        View::make('Help/helpPage', array('title' => 'Help Page', 'data' => $helpData))->render();
    }

    public function getItem($index) {
        $help = new HelpPage(Route::getRouters());
        $helpData = $help->getByIndex($index);
        View::make('Help/itemPage', array('title' => $helpData['url'], 'data' => $helpData))->render();
    }
} 