<?php
class Login extends AppModel
{
    const MIN_STRING_LENGTH = 1;
    const MAX_STRING_LENGTH = 50;
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
    );

    public function checkInput()
    {
        $this->validate();
        if ($this->hasError()) {
            throw new ValidationException('invalid Input');
        }
    }

    public function accept()
    {
        $db = DB::conn();
        $db->begin();
        $params = array(
            $this->username,
            md5($this->password)
        );
        $row = $db->row('SELECT * FROM user WHERE username = ? AND password = ?', $params);
        if (!$row) {
            throw new RecordNotFoundException('no record found');
        }
    }
}
