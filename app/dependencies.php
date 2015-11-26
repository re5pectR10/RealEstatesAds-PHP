<?php
use FW\Helpers\DependencyProvider;

DependencyProvider::inject('Controllers\CategoryController', 'category', null, 'Models\Category');

DependencyProvider::inject('Controllers\CityController', 'city', null, 'Models\City');

DependencyProvider::inject('Controllers\EstateController', 'estate', null, 'Models\Estate');
DependencyProvider::inject('Controllers\EstateController', 'category', null, 'Models\Category');
DependencyProvider::inject('Controllers\EstateController', 'city', null, 'Models\City');
DependencyProvider::inject('Controllers\EstateController', 'image', null, 'Models\Image');
DependencyProvider::inject('Controllers\EstateController', 'user', null, 'Models\User');

DependencyProvider::inject('Controllers\MessageController', 'estate', null, 'Models\Estate');
DependencyProvider::inject('Controllers\MessageController', 'message', null, 'Models\Message');

DependencyProvider::inject('Controllers\AdminController', 'user', null, 'Models\User');

DependencyProvider::inject('Controllers\UserController', 'user', null, 'Models\User');
DependencyProvider::inject('Controllers\UserController', 'estate', null, 'Models\Estate');

DependencyProvider::inject('Controllers\ImageController', 'image', null, 'Models\Image');