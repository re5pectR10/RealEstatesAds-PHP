<?php
return array(

    'debug' => true,

    'default_controller' => 'EstateController',

    'default_method' => 'index',

    'enable_default_routing' => false,

    'global_input_escaping' => true,

    'escape_input_without' => array(
        '_token',
        'password'
    ),

    'namespaces' => array(

        'Controllers' => dirname(__DIR__) . '\controllers',
        'Models' => dirname(__DIR__) . '\models'
    ),

    'session' => array(

        'autostart' => true,

        'type' => 'native',

        'name' => '_sess',

        'lifetime' => 3600,

        'path' => '/',

        'domain' => '',

        'secure' => false,

        'dbConnection' => 'default',

        'dbTable' => 'sessions',
    ),

    'role_table' => array(
        'name' => 'roles',
        'id_column' => 'id',
        'role_name_column' => 'role'
    ),
    'user_role_table' => array(
        'name' => 'user_roles',
        'user_id_column' => 'user_id',
        'role_id_column' => 'role_id'
    ),
    'user_table' => array(
        'name' => 'users',
        'id' => 'id',
        'username' => 'username',
        'password' => 'password'
    ),
    'controllers_namespace' => 'Controllers'
);