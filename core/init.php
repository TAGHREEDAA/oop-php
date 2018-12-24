<?php

session_start();

$GLOBALS['config']=[
    'mysql'=>[
        'host'=>'localhost',
        'username'=>'root',
        'password'=>'123',
        'db'=>'registerSystem'
    ],
    'remember'=>[
        'cookie_name'=>'hash',
        'cookie_expiry'=>2592000 // 1 month
    ],
    'session'=>[
        'session_name'=>'user',
        'token_name'=>'csrf-token'
    ]
];


spl_autoload_register(function($class){
    require_once "classes/{$class}.php";
});


require_once 'functions/sanitize.php';