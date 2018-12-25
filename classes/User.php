<?php
class User{
    private $_db,
            $_sessionName,
            $_cookieName,
            $_isLoggedIn,
            $_data;


    public function __construct($user = null){
        $this->_db = DB::getInstance();

        $this->_sessionName = Config::get("session.session_name");
        $this->_cookieName = Config::get("remember.cookie_name");

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
        else{
            if($this->find($user)){
                $this->_isLoggedIn = true;
            }else{
                // proccess logout
            }
        }
    }

    public function create(Array $data){
        if(!$this->_db->insert('users',$data)){
            throw  new Exception('A Problem Occured while register new account');
        }
    }

    public function update(Array $data, $id =null){
        $id = (!$id && $this->isLoggedIn())?$this->data()->id: $id;
        if(!$this->_db->update('users',$id,$data)){
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
    public function login($username=null, $password=null, $remember=false){

        if (!$username && !$password && $this->exists()){
            // login user automatically

            Session::put($this->_sessionName, $this->data()->id);
        }
        else
        {
            // 1- find user where username = $username
            if ($this->find($username)){
                $user = $this->data();

                // 2- hash password with salt
                // 3- compare password with the saved in db
                if (Hash::make($password,$user->salt) === $user->password){
                    Session::put($this->_sessionName,$user->id);

                    if ($remember){

                        // 1- check if exists in users_session table
                        $userSession =$this->_db->get('users_session',['user_id','=',$user->id]);

                        if (!$userSession->count()) {
                            $hash=Hash::unique();

                            // 2- if new insert into db
                            $this->_db->insert('users_session',[
                                'user_id'=> $user->id,
                                'hash'=> $hash,
                            ]);
                        }
                        else{
                            $hash = $userSession->first()->hash;
                        }

                        Cookie::set($this->_cookieName, $hash, Config::get('remember.cookie_expiry'));
                    }
                    return true;
                }
                else
                    return false;

            }
        }
    }

    public function data(){
        return $this->_data;
    }

    public function isLoggedIn(){
        return $this->_isLoggedIn;
    }

    public function logout(){

        // delete user_sessions
        $this->_db->delete('users_session',['user_id','=',$this->data()->id]);

        Session::delete($this->_sessionName);
        Cookie::delete($this->_cookieName);

    }

    private function exists(){

        return (!empty($this->data()))? true: false;
    }
}