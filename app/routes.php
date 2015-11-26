<?php
use \FW\Route\Route;

Route::Group('helppage', array(), function() {
    Route::GET('', array('use'=>'HelpPageController@index','name'=>'sds'));
    Route::GET('/{index:int}', array('use'=>'HelpPageController@getItem'));
});

Route::GET('', array('use' => 'EstateController@index'));
Route::GET('estate/{id:int}', array('use' => 'EstateController@details'));

Route::Group('user', array(), function() {
    Route::GET('', array('use'=>'UserController@getProfile', 'before' => 'auth'));
    Route::POST('', array('use'=>'UserController@editProfile', 'before' => 'auth|csrf'));
    Route::GET('/register', array('use'=>'UserController@getRegister'));
    Route::POST('/register', array('use'=>'UserController@postRegister', 'before' => 'csrf'));
    Route::GET('/login', array('use'=>'UserController@getLogin'));
    Route::POST('/login', array('use'=>'UserController@postLogin', 'before' => 'csrf'));
    Route::GET('/logout', array('use'=>'UserController@logout', 'before' => 'auth'));
    Route::GET('/{id:int}/products', array('use'=>'UserController@getProducts', 'before' => 'auth'));
    Route::POST('/product/{id:int}/sell/{upid:int}', array('use'=>'UserController@sellProduct', 'before' => 'auth|csrf'));
});

Route::Group('admin', array('roles' => 'admin', 'before' => 'auth'), function() {
    Route::GET('/users', array('use' => 'AdminController@getUsers'));
    Route::GET('/make/{id:int}/{role}', array('use' => 'AdminController@setRole'));
    Route::GET('/ban/{id:int}', array('use' => 'AdminController@banUser'));

    Route::Group('/category', array(), function() {
        Route::GET('/', array('use' => 'CategoryController@index'));
        Route::GET('/{id:int}/delete', array('use' => 'CategoryController@deleteCategory'));
        Route::GET('/add', array('use' => 'CategoryController@getAdd'));
        Route::POST('/add', array('use' => 'CategoryController@postAdd'));
        Route::GET('/{id:int}/edit', array('use' => 'CategoryController@getEdit'));
        Route::POST('/{id:int}/edit', array('use' => 'CategoryController@postEdit'));
    });

    Route::Group('/city', array(), function() {
        Route::GET('/', array('use' => 'CityController@index'));
        Route::GET('/{id:int}/delete', array('use' => 'CityController@deleteCity'));
        Route::GET('/add', array('use' => 'CityController@getAdd'));
        Route::POST('/add', array('use' => 'CityController@postAdd'));
        Route::GET('/{id:int}/edit', array('use' => 'CityController@getEdit'));
        Route::POST('/{id:int}/edit', array('use' => 'CityController@postEdit'));
    });

    Route::Group('/estate', array(), function() {
        Route::GET('/{id:int}/delete', array('use' => 'EstateController@deleteEstate'));
        Route::GET('/add', array('use' => 'EstateController@getAdd'));
        Route::POST('/add', array('use' => 'EstateController@postAdd'));
        Route::GET('/{id:int}/edit', array('use' => 'EstateController@getEdit'));
        Route::POST('/{id:int}/edit', array('use' => 'EstateController@postEdit'));
    });

    Route::GET('/image/delete/{id:int}', array('use' => 'ImageController@delete'));

    Route::GET('/messages', array('use' => 'MessageController@index'));
    Route::GET('/message/{id:int}', array('use' => 'MessageController@get'));
});

Route::GET('estate/{id:int}/message', array('use' => 'MessageController@getAdd'));
Route::POST('estate/{id:int}/message', array('use' => 'MessageController@postAdd'));

Route::GET('estate/favorites/{id:int}/add', array('use' => 'UserController@addToFavourites'));
Route::GET('estate/favorites/{id:int}/remove', array('use' => 'UserController@removeFromFavourites'));