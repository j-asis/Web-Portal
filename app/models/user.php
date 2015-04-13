<?php

class User extends AppModel
{
    const MIN_STRING_LENGTH = 1;
    const MAX_STRING_LENGTH = 80;
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
        ),

    );

    public function setInfoByUsername($username)
    {
        $this->username         = $username;
        $this->user_id          = $this->getUserId($this->username);
        $this->user_details     = objectToArray($this->getUserDetail($this->user_id));
        $this->followed_threads = Follow::getFollowedByUserId($this->user_id);
        $this->liked_comments   = Likes::getLikedByUserId($this->user_id);
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
        return new self($row);
    }

    public static function getUserName($user_id)
    {
        $data = self::getUserDetail($user_id);
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

    public function saveAvatar($dir)
    {
        try {
            $db = DB::conn();
            $db->begin();
            $db->update('user', array('avatar' => $dir), array('id' => $this->user_id));
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
                'username'   => $this->new_username,
                'first_name' => $this->new_first_name,
                'last_name'  => $this->new_last_name,
                'email'      => $this->new_email,
                );
            $db->update('user', $params, array('id' => $this->user_id));
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
        $db->update('user', array('password' => md5($this->new_password)), array('id' => $this->user_id));
        $db->commit();
        } catch (Exception $e) {
            $db->rollback();
            throw $e;
        }
    }

    public static function deleteById($id)
    {
        Thread::deleteByUserId($id);
        $db = DB::conn();
        try {
            $db->query('DELETE FROM user WHERE id = ? ', array($id));
        } catch (Exception $e) {
            throw $e;
        }
        return true;
    }

    public static function search($type, $query)
    {
        $results = array();
        $query = "%{$query}%";
        switch ($type) {
            case self::USER_SEARCH_KEY:
                $results = self::searchByQuery($query);
                break;
            case self::THREAD_SEARCH_KEY:
                $results = Thread::searchByQuery($query);
                break;
            case self::COMMENT_SEARCH_KEY:
                $results = Comment::searchByQuery($query);
                break;
            default:
                throw new NotFoundException("{$type} is not found");
                break;
        }
        return $results;
    }
    
    public static function searchByQuery($query)
    {
        $results = array();
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

    public function isUnchanged($username, $first_name, $last_name, $email)
    {
        $db = DB::conn();
        $count = $db->value('SELECT COUNT(*) FROM user WHERE username = ? AND first_name = ?
            AND last_name = ? AND email = ?', array($username, $first_name, $last_name, $email));
        return ($count > 0);
    }

}
