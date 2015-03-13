<?php

class Login extends AppModel
{
    public $validation = array(
        'password' => array('length'=>array('validate_between',8,50)),
        'username' => array('length'=>array('validate_between',1,50))
        );


    public function check_input(){
        $this->validate();
        if ($this->hasError()) {
            throw new ValidationException('invalid Input');
        }
    }

    public function log_in()
    {
        $db = DB::conn();
        $db->begin();
        $params = array(
            $this->username,
            md5($this->password)
        );
        $row = $db->row('SELECT * FROM user WHERE username = ? AND password = ?', $params);
        if(!$row){
            throw new RecordNotFoundException('no record found');
        }
    }

}
