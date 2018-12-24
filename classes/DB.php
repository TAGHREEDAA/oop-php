<?php

class DB{

    private static $_instance=null;

    private $_pdo,
            $_query,
            $_errors = false,
            $_results,
            $_count = 0;

    private function __construct(){
        try{
            $this->_pdo=new PDO("mysql:host=localhost; dbname=registerSystem",Config::get('mysql.username'),Config::get('mysql.password'));
//            $this->_pdo=new PDO("mysql:host=".Config::get('mysql.host').", dbname=".Config::get('mysql.db'),Config::get('mysql.username'),Config::get('mysql.password'));

//            $this->_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


        }
        catch (PDOException $exception){

            die($exception->getMessage());
        }
    }

    /**
     * @return null
     */
    public static function getInstance()
    {
       if (!isset(self::$_instance)){
           self::$_instance = new DB();
       }
       return self::$_instance;
    }


    /**
     * @param $sql
     * @param array $params
     * @return $this|bool
     */
    public function query($sql, $params=[]){
        $this->_errors=false;
        if ($this->_query = $this->_pdo->prepare($sql)) {
            foreach ($params as $i=>$param){
                $this->_query->bindValue($i+1, $param);
            }
            if ($this->_query->execute()){
                $this->_count = $this->_query->rowCount();
                if ($this->_count){
                    $this->_results = $this->_query->fetchAll(PDO::FETCH_OBJ);
                }
            }
            else{
                $this->_errors = true;
            }
        }

        return $this;
    }

    /**
     * @param $action
     * @param $table
     * @param $where
     */
    private function action($action, $table, $where){
        if (count($where) === 3){
            $field= $where[0];
            $operator= $where[1];
            $value= $where[2];
            $validOperators = ['=','<','>','<=','>=', '<>'];

            if (in_array($operator, $validOperators))
            {
                $sql ="$action FROM $table WHERE $field $operator? ";
                if (!$this->query($sql,[$value])->error()){
                    return $this;
                }
            }
        }
        return false;

    }
    public function get($table, Array $where=[]){
        $this->action("SELECT * ",$table ,$where);
        return $this;
    }

    public function delete($table, Array $where=[]){
        $this->action("DELETE ",$table ,$where);

    }


    public function insert($table, array $params){
        if (count($params)){
            $columns =implode(',', array_keys($params));
            $input = '?';
            $multiplier = count($params);
            $separator = ',';
            $str = implode($separator, array_fill(0, $multiplier, $input));

            $sql = "INSERT INTO $table ($columns) VALUES ($str)";

            if (!$this->query($sql, array_values($params))->error())
            {
                return true;
            }
        }
        return false;
    }

    public function update($table, $id, array $params){
        if (count($params)){
            $columns =implode('=?,', array_keys($params));
            $fields= array_values($params);
            $fields[]=$id;

            $sql = "UPDATE $table SET $columns =? Where id=?";

            if (!$this->query($sql, $fields)->error())
            {
                return true;
            }
        }
        return false;
    }


    public function error()
    {
        return $this->_errors;
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return $this->_count;
    }

    /**
     * @return mixed
     */
    public function results()
    {
        return $this->_results;
    }

    /**
     * @return mixed
     */
    public function first(){
        return $this->results()[0];
    }
}