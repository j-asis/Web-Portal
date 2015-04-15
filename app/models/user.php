<?php

class User extends AppModel
{
    const MIN_STRING_LENGTH = 1;
    const MAX_STRING_LENGTH = 80;
    const DEFAULT_SEARCH_PAGE = 1;

    const USER_SEARCH_KEY = 'user';
    const THREAD_SEARCH_KEY = 'thread';
    const COMMENT_SEARCH_KEY = 'comment';

    public $validation = array(
        'new_username' => array(
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
        'new_first_name' => array(
            'length' => array(
                'validate_between', self::MIN_STRING_LENGTH, self::MAX_STRING_LENGTH
            ),
            'valid' => array(
                'validate_name'
            ),
        ),
        'new_last_name' => array(
            'length' => array(
                'validate_between', self::MIN_STRING_LENGTH, self::MAX_STRING_LENGTH
            ),
            'valid' => array(
                'validate_name'
            ),
        ),
        'new_email' => array(
            'length' => array(
                'validate_between', self::MIN_STRING_LENGTH, self::MAX_STRING_LENGTH
            ),
            'exists' => array(
                'emailExists'
            ),
        ),
        'new_password' => array(
            'length' => array(
                'validate_between', self::MIN_STRING_LENGTH, self::MAX_STRING_LENGTH
            ),
            'match' => array(
                'isPasswordMatch'
            ),
        ),
        'old_password' => array(
            'correct' => array(
                'checkPassword'
            ),
        ),

    );

    public function isPasswordMatch($password)
    {
        return ($password === $this->confirm_new_password);
    }

    public function checkPassword($password)
    {
        return ($this->user_details['password'] === md5($password));
    }

    public function userExists($username)
    {
        if ($this->user_details['username'] === $username) {
            return true;
        }
        $db = DB::conn();
        $row = $db->row('SELECT * FROM user WHERE username = ? ', array($username));
        if (!$row) {
            return true;
        }
        return false;
    }
    
    public function emailExists($email)
    {
        if ($this->user_details['email'] === $email) {
            return true;
        }
        $db = DB::conn();
        $row = $db->row('SELECT * FROM user WHERE email = ? ', array($email));
        if (!$row) {
            return true;
        }
        return false;
    }

    public function setInfoByUsername($username)
    {
        $this->username         = $username;
        $this->user_id          = $this->getUserId($this->username);
        $this->user_details     = objectToArray($this->getInfoById($this->user_id));
        $this->followed_threads = Follow::getFollowedByUserId($this->user_id);
        $this->liked_comments   = Likes::getLikedByUserId($this->user_id);
    }

    public static function getInfoById($id)
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
        return new self($row);
    }

    public static function getUserName($user_id)
    {
        $data = self::getInfoById($user_id);
        return $data->username;
    }

    public static function getUserId($username)
    {
        $db = DB::conn();
        $row = $db->row('SELECT id FROM user WHERE username = ?', array($username));
        if (!$row) {
            throw new RecordNotFoundException("Mysql Error, record not found");
        }
        return $row['id'];
    }

    public function saveAvatar($directory)
    {
        try {
            $db = DB::conn();
            $db->update('user', array('avatar' => $directory), array('id' => $this->user_id));
        } catch(Exception $e) {
            throw $e;
        }
    }

    public function update()
    {
        if (!$this->validate()) {
            throw new ValidationException('invalid input');
        }
        try {
            $db = DB::conn();
            $params = array(
                'username'   => $this->new_username,
                'first_name' => $this->new_first_name,
                'last_name'  => $this->new_last_name,
                'email'      => $this->new_email,
            );
            $db->update('user', $params, array('id' => $this->user_id));
        } catch(Exception $e) {
            throw $e;
        }
    }

    public function changePassword()
    {
        if (!$this->validate()) {
            throw new ValidationException('invalid input');
        }
        try {
            $db = DB::conn();
            $db->update('user', array('password' => md5($this->new_password)), array('id' => $this->user_id));
        } catch (Exception $e) {
            throw $e;
        }
    }

    public static function deleteById($id)
    {
        Follow::deleteByUserId($id);
        Likes::deleteByUserId($id);
        Comment::deleteByUserId($id);
        Thread::deleteByUserId($id);
        $db = DB::conn();
        try {
            $db->query('DELETE FROM user WHERE id = ? ', array($id));
        } catch (Exception $e) {
            throw $e;
        }
        return true;
    }
    
    public static function searchByQuery($query)
    {
        $results = array();
        $query = "%{$query}%";
        $db = DB::conn();
        $rows = $db->rows('SELECT * FROM user
            WHERE username LIKE ? OR first_name LIKE ? OR last_name LIKE ? OR email LIKE ? ',
            array($query, $query, $query, $query) );
        foreach ($rows as $row) {
            $row['avatar'] = empty($row['avatar']) ? '/public_images/default.jpg' : $row['avatar'];
            $results[] = $row;
        }
        return $results;
    }

    public static function getAvatar($id)
    {
        $db = DB::conn();
        $avatar = (string) $db->value('SELECT avatar FROM user WHERE id = ?', array($id));
        return empty($avatar) ? '/public_images/default.jpg' : $avatar;
    }

    public function isUnchanged()
    {
        $db = DB::conn();
        $params = array(
            $this->new_username,
            $this->new_first_name,
            $this->new_last_name,
            $this->new_email,
        );
        $count = $db->value('SELECT COUNT(*) FROM user WHERE username = ? AND first_name = ?
            AND last_name = ? AND email = ?', $params);
        return ($count > 0);
    }

}
