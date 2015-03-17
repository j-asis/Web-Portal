<?php
class Register extends AppModel
{
    const MIN_STRING_LENGTH = 1;
    const MAX_STRING_LENGTH = 80;
    const MIN_PASSWORD_LENGTH = 8;
    const MAX_PASSWORD_LENGTH = 50;

    public $validation = array(
        'password' => array(
            'length' => array(
                'validate_between', self::MIN_PASSWORD_LENGTH, self::MAX_PASSWORD_LENGTH
                ),
            ),
        'username' => array(
            'length' => array(
                'validate_between', self::MIN_STRING_LENGTH, self::MAX_STRING_LENGTH
                ),
            ),
        'first_name' => array(
            'length' => array(
                'validate_between', self::MIN_STRING_LENGTH, self::MAX_STRING_LENGTH
                ),
            ),
        'last_name' => array(
            'length' => array(
                'validate_between', self::MIN_STRING_LENGTH, self::MAX_STRING_LENGTH
                ),
            ),
        'email' => array(
            'length' => array(
                'validate_between', self::MIN_STRING_LENGTH, self::MAX_STRING_LENGTH
                ),
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
                'password' => md5($this->password)
            );
            $db->insert('user', $params);
            $db->commit();
            $this->created = true;
        } catch(Exception $e) {
            $db->rollback();
            throw $e;
        }
    }

    public function validate_password()
    {
        if (!($this->password === $this->cpassword)) {
            $this->validation_errors['password']['match'] = true;
        }
    }

    public function user_exists(){
        $db = DB::conn();
        $db->begin();
        $row = $db->row('SELECT * FROM user WHERE username = ? ', array($this->username));
        if (!empty($row)) {
            $this->validation_errors['username']['exists'] = true;
        }
    }
}
