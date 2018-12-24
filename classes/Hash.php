<?php

class Hash{
    public static function make($string, $salt=''){
        return hash('sha256', $string.$salt);
    }

    /**
     * /**
     * generate a salt string of $length
     * @param $length
     * @return string
     * @throws Exception
     */
    public static function salt($length){

        //mcrypt_create_iv($length); // Deprecated
        return random_bytes($length);
    }

    /**
     * @return string
     */
    public static function unique(){
        return self::make(uniqid());
    }
}