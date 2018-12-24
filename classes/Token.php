<?php

class Token{
    public static function generate(){
        //1- get the token name from configuration
        //2- generate random string and save it in the session
        $tokenName= Config::get('session.token_name');
        return Session::put($tokenName, md5(uniqid()));
    }

    public static function check($token){
        $tokenName= Config::get('session.token_name ');
        if (Session::exists($tokenName) && $token === Session::get($tokenName)){
            Session::delete($tokenName);
            return true;
        }

        return false;
    }
}