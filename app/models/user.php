<?php

class User extends AppModel
{

    public function check_user(){
        $this->validate();
        if ($this->hasError()) {
            throw new ValidationException('invalid Input');
        }
    }

    public function log_in(){
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

    public function log_out(){
        session_unset('username');
        session_destroy();
    }

}