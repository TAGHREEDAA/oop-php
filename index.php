<?php

require_once 'core/init.php';

if (Session::exists('success')){
    echo Session::flash('success');
}

//var_dump(Config::get('mysql.host'));


//$users = DB::getInstance()->query("Select * from users WHERE id=?",["1"]);
//$users = DB::getInstance()->get("users",["name","=","Taghreed M. KAmel"]);
//if ($users->count()){
//    foreach ($users->results() as $user){
//        echo $user->username."<br>";
//    }
//}


//$users = DB::getInstance()->delete("users",["username","=","Ahmed"]);

//
//
//DB::getInstance()->insert('users',[
//    'username'  => 'Ahmed',
//    'email'  =>'Ahmed@mail.com',
//    'name'  =>'Ahmed Kamel',
//    'password'  =>md5('123'),
//    'salt'      => 'salt',
//    'joined_at'      => date('Y-m-d H:i:s'),
//    'group_id' => 1
//]);





DB::getInstance()->update('users',3, [
    'username'  => 'Taghreeeeeed'
]);
