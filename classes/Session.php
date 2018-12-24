<?php

class Session{
    public static function put($key, $value){
        return $_SESSION[$key] = $value;
    }

    public static function exists($key){
        return (isset($_SESSION[$key]))? true: false;
    }

    public static function get($key){
        return $_SESSION[$key];
    }

    public static function delete($key){
        if (self::exists($key)){
            unset($_SESSION[$key]);
        }
    }

    public static function flash($message, $content = ''){
        if (self::exists($message)){
            $value = self::get($message);
            self::delete($message);
            return $value;
        }
        else{
            self::put($message,$content);
        }
    }
}