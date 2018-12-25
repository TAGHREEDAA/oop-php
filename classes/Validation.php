<?php

class Validation{

    private $_passed,
            $_errors=[],
            $_db;

    public function __construct()
    {
        $this->_db = DB::getInstance();
    }

    public function check($data, array $items){

        foreach ($items as $item=>$rules){
            foreach ($rules as $rule=>$ruleValue){

//                echo "{$item} $data[$item] must be $rule : $ruleValue <br>";

                if ($rule ==='required' && empty($data[$item])){
                    $this->addError("$item is required!");

                }
                if ($rule === 'min' && strlen($data[$item]) < $ruleValue){
                    $this->addError("$item length must be greater than or equals $ruleValue chars length!");

                }
                if ($rule === 'max'&& strlen($data[$item]) > $ruleValue){
                    $this->addError("$item length must be less than or equals $ruleValue chars length!");
                }
                if ($rule === 'matches' && $data[$item] !== $data[$ruleValue]){
                    $this->addError("$item and $ruleValue are not matched! ");
                }
                if ($rule === 'unique'&& !$this->checkUnique($ruleValue, $item, $data[$item])){
                    $this->addError("$item $data[$item] is taken! ");
                }
                if ($rule === 'unique-ignore'&& !$this->checkUniqueIgnoreValue($ruleValue[0],$ruleValue[1],$ruleValue[2], $item, $data[$item])){
                    $this->addError("$item $data[$item] is taken! ");
                }

            }

        }

        if (empty($this->_errors)){
            $this->_passed =true;
        }
    }

    private function addError($error){
        $this->_errors[] = $error;
    }

    /**
     * @return array
     */
    public function errors(): array
    {
        return $this->_errors;
    }

    /**
     * @return mixed
     */
    public function passed()
    {
        return $this->_passed;
    }

    private function checkUnique($table, $column, $value){
        $this->_db->get($table,[$column,'=',$value]);
        return ($this->_db->count() > 0)? false : true;
    }

    public function checkUniqueIgnoreValue($table,$ignoredCol, $ignoredValue, $column, $value){

        $this->_db->query("SELECT * FROM {$table} WHERE {$ignoredCol} <> ? AND {$column}= ?",[$ignoredValue, $value]);

        return ($this->_db->count() > 0)? false : true;
    }
}