<?php

class User extends AppModel
{
    const MIN_STRING_LENGTH = 1;
    const MAX_STRING_LENGTH = 80;

    public $validation = array(
            'new_username' => array(
                'length' => array(
                    'validate_between', self::MIN_STRING_LENGTH, self::MAX_STRING_LENGTH
                ),
            ),
            'new_first_name' => array(
                'length' => array(
                    'validate_between', self::MIN_STRING_LENGTH, self::MAX_STRING_LENGTH
                ),
            ),
            'new_last_name' => array(
                'length' => array(
                    'validate_between', self::MIN_STRING_LENGTH, self::MAX_STRING_LENGTH
                ),
            ),
            'new_email' => array(
                'length' => array(
                    'validate_between', self::MIN_STRING_LENGTH, self::MAX_STRING_LENGTH
                ),
            ),

        );
    public function __construct()
    {
        securedPage();
        $this->username = $_SESSION['username'];
        $this->user_id = $this->getUserId($this->username);
        $this->user_details = $this->getUserDetail($this->user_id);
        //$this->followed_threads = $this->followedThreads();
    }
    public static function getUserDetail($id)
    {
        try {
            $db = DB::conn();
            $row = $db->row('SELECT * FROM user WHERE id = ?', array($id));
        } catch (Exception $e) {
            throw new Exception("Mysql Error, record not found");
        }
        if (empty($row['avatar'])) {
            $row['avatar'] = '/public_images/default.jpg';
        }
        return $row;
    }
    public static function getUserName($user_id)
    {
        $db = DB::conn();
        $row = $db->row('SELECT username FROM user WHERE id = ?', array($user_id));
        return $row['username'];
    }
    public static function getUserId($username)
    {
        try {
            $db = DB::conn();
            $row = $db->row('SELECT id FROM user WHERE username = ?', array($username));
        } catch (Exception $e) {
            throw new Exception("Mysql Error, record not found");
        }
        return !empty($row['id']) ? $row['id'] : false;
    }
    public function saveAvatar($dir)
    {
        try {
            $db = DB::conn();
            $db->begin();
            $db->update('user', array('avatar'=>$dir), array('id'=>$this->user_id));
            $db->commit();
        } catch(Exception $e) {
            $db->rollback();
            throw $e;
        }
    }
    public function updateUser()
    {
        $this->validate();
        if ($this->hasError()) {
            throw new ValidationException('invalid Input');
        }
        try {
            $db = DB::conn();
            $db->begin();
            $params = array(
                'username'=>$this->new_username,
                'first_name'=>$this->new_first_name,
                'last_name'=>$this->new_last_name,
                'email'=>$this->new_email,
                );
            $db->update('user', $params, array('id'=>$this->user_id));
            $db->commit();

        } catch(Exception $e) {
            $db->rollback();
            throw $e;
        }
    }
    public function changePassword()
    {
        try {
        $db = DB::conn();
        $db->begin();
        $db->update('user', array('password'=>md5($this->new_password)), array('id'=>$this->user_id));
        $db->commit();
        } catch (Exception $e) {
            $db->rollback();
            throw $e;
        }
    }
}
