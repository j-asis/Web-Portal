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
            'match' => array(
                'isPasswordMatch'
            ),
        ),
        'username' => array(
            'length' => array(
                'validate_between', self::MIN_STRING_LENGTH, self::MAX_STRING_LENGTH
            ),
            'valid' => array(
                'validate_username'
            ),
            'exists' => array(
                'userExists'
            ),
        ),
        'first_name' => array(
            'length' => array(
                'validate_between', self::MIN_STRING_LENGTH, self::MAX_STRING_LENGTH
            ),
            'valid' => array(
                'validate_name'
            ),
        ),
        'last_name' => array(
            'length' => array(
                'validate_between', self::MIN_STRING_LENGTH, self::MAX_STRING_LENGTH
            ),
            'valid' => array(
                'validate_name'
            ),
        ),
        'email' => array(
            'length' => array(
                'validate_between', self::MIN_STRING_LENGTH, self::MAX_STRING_LENGTH
            ),
            'exists' => array(
                'emailExists'
            ),
        ),
    );

    public function create()
    {
        if (!$this->validate()) {
            throw new ValidationException('Invalid Input');
        }
        try {
            $db = DB::conn();
            $params = array(
                'username'   => $this->username,
                'first_name' => $this->first_name,
                'last_name'  => $this->last_name,
                'email'      => $this->email,
                'password'   => md5($this->password)
            );
            $db->insert('user', $params);
            $this->created = true;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function userExists($username)
    {
        $db = DB::conn();
        $row = $db->row('SELECT * FROM user WHERE username = ? ', array($username));
        if (!$row) {
            return true;
        }
        return false;
    }
    
    public function emailExists($email)
    {
        $db = DB::conn();
        $row = $db->row('SELECT * FROM user WHERE email = ? ', array($email));
        if (!$row) {
            return true;
        }
        return false;
    }

    public function isPasswordMatch($password)
    {
        return ($password === $this->confirm_password);
    }
}
