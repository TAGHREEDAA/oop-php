<?php

class Cookie{

    public static function exists($key){
        return (isset($_COOKIE[$key]))? true : false;
    }

    public static function get($key){
        return $_COOKIE[$key];
    }

    public static function set($key, $value, $expiry){
        if (setcookie($key, $value, time() + $expiry, '/')){
            return true;
        }
        return false;
    }

    public static function delete($key){
        self::set($key,'', -1);
    }
}