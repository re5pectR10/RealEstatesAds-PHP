<?php
use FW\Helpers\DependencyProvider;

DependencyProvider::inject('Controllers\CategoryController', 'category', null, 'Models\Category');

DependencyProvider::inject('Controllers\CityController', 'city', null, 'Models\City');

DependencyProvider::inject('Controllers\EstateController', 'estate', null, 'Models\Estate');
DependencyProvider::inject('Controllers\EstateController', 'category', null, 'Models\Category');
DependencyProvider::inject('Controllers\EstateController', 'city', null, 'Models\City');

DependencyProvider::inject('Controllers\AdminController', 'user', null, 'Models\User');

DependencyProvider::inject('Controllers\UserController', 'user', null, 'Models\User');