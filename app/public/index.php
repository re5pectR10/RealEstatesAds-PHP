<?php
error_reporting(E_ALL ^ E_NOTICE);
include '../../FW/App.php';

$app = \FW\App::getInstance();


$app->run();