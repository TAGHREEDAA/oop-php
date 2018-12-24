<?php
class Input{

    public static function exists($type = 'post'){
        switch ($type){
            case 'post':
                return (!empty($_POST))? true: false;
                break;
            case 'get':
                return (!empty($_GET))? true: false;
                break;
            default:
                return false;
                break;
        }
    }

    public static function get($key){
        if (isset($_POST[$key])){
            return $_POST[$key];
        }
        elseif (isset($_GET[$key])){
            return $_GET[$key];
        }

        return '';
    }

}