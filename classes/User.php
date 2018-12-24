<?php
class User{
    private $_db,
            $_sessionName,
            $_isLoggedIn,
            $_data;


    public function __construct($user = null){
        $this->_db = DB::getInstance();

        $this->_sessionName = Config::get("session.session_name");

        if(!$user){
            if(Session::exists($this->_sessionName)){
                $user = Session::get($this->_sessionName);

                if($this->find($user)){
                    $this->_isLoggedIn = true;
                }else{
                    // proccess logout
                }
            }
        }
    }

    public function create(Array $data){
        if(!$this->_db->insert('users',$data)){
            throw  new Exception('A Problem Occured while register new account');
        }
    }

    public function find($user = null){
        if ($user){
            $field = (is_numeric($user))? 'id': 'username';
            $data = $this->_db->get('users',[$field,'=',$user]);

            if ($this->_db->count()) {
                $this->_data = $data->first();
                return true;
            }
        }
        else
            return false;
    }
    public function login($username=null, $password=null){
        // 1- find user where username = $username
        if ($this->find($username)){
            $user = $this->data();

            // 2- hash password with salt
            // 3- compare
            if (Hash::make($password,$user->salt) === $user->password){
                Session::put($this->_sessionName,$user->id);
                return true;
            }
            else
                return false;

        }
    }

    public function data(){
        return $this->_data;
    }

    public function isLoggedIn(){
        return $this->_isLoggedIn;
    }

}