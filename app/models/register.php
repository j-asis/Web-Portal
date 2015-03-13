<?php

class Register extends AppModel
{
    public $validation = array(
        'password' => array(
                        'match' => array('validate_password'),
                        'length' => array(
                            'validate_between', 8, 50
                        ),
        ),
        'username' => array(
                        'length' => array(
                            'validate_between', 1, 50
                        ),
        ),
        'first_name' => array(
                            'length' => array(
                                'validate_between', 1, 50)
                            ),
        'last_name' => array(
                            'length' => array(
                                'validate_between', 1, 50)
                            ),
        'email' => array(
                        'length' => array(
                            'validate_between', 1, 50)
                        ),
    );

     public function create()
    {
        $this->validate();
        if ($this->hasError()) {                    
            throw new ValidationException('invalid Input');
        }
        try {
            $db = DB::conn();
            $db->begin();
            $params = array(
                'username' => $this->username,
                'first_name' => $this->first_name,
                'last_name' => $this->last_name,
                'email' => $this->email,
                'password' => md5($this->password) // use MD5 to encrypt
            );
            $db->insert('user', $params);
            $db->commit();
            $this->created = true;
        } catch(Exception $e) {
            $db->rollback();
            throw $e;
        }
    }

    public static function getPasswords(){
        return array(Param::get('password'), Param::get('cpassword'));
    }
}