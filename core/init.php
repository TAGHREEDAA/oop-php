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

if (Cookie::exists(Config::get('remember.cookie_name')) && !Session::exists(Config::get('session.session_name'))){
//    var_dump('User Asked to be remembered');
    //1- lookup in db get user id of the hash

    $hash = Cookie::get(Config::get('remember.cookie_name'));
    $userSession =DB::getInstance()->get('users_session',['hash','=',$hash]);
    if ($userSession->count()){
        // hash matches log user in
        $userID=$userSession->first()->user_id;

        $user = new User($userID);
        $user->login();

    }



}