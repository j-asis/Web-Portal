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
        $this->followed_threads = $this->followedThreads();
        $this->liked_comments = $this->likedComments();
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
    public function deleteComment($id)
    {
        $is_authenticated = false;
        $db = DB::conn();
        $db->begin();
        $row = $db->row("SELECT * FROM comment WHERE id = ? ", array($id));
        if ($this->user_id===$row['user_id']) {
            $is_authenticated = true;
        }
        if ($is_authenticated) {
            $db->query("DELETE FROM comment WHERE id = ? ", array($id));
            $db->commit();
            return true;
        } else {
            $this->error_message = "Authentication Failed! You are not allowed to delete this Comment!";
            return false;
        }
    }
    public function deleteThread($id)
    {
        $is_authenticated = false;
        $db = DB::conn();
        $db->begin();
        $row = $db->row("SELECT * FROM thread WHERE id = ? ", array($id));
        if ($this->user_id===$row['user_id']) {
            $is_authenticated = true;
        }
        if ($is_authenticated) {
            $db->query("DELETE FROM thread WHERE id = ? ", array($id));
            $db->query("DELETE FROM follow WHERE thread_id = ? ", array($id));
            $comments = $db->rows("SELECT id FROM comment WHERE thread_id = ? ", array($id));
                foreach ($comments as $comment) {
                    $db->query("DELETE FROM comment WHERE id = ? ", array($comment['id']));
                }
            $db->commit();
            return true;
        } else {
            $this->error_message = "Authentication Failed! You are not allowed to delete this thread!";
            return false;
        }
    }
    public function deleteUser($id)
    {
        $is_authenticated = false;
        $db = DB::conn();
        $db->begin();
        $row = $db->row("SELECT * FROM user WHERE id = ? ", array($id));
        if ($this->user_id===$row['id']) {
            $is_authenticated = true;
        }
        if ($is_authenticated) {
            $threads = $db->rows("SELECT id FROM thread WHERE user_id = ? ", array($id));
            foreach ($threads as $thread) {
                $comments = $db->rows("SELECT id FROM comment WHERE thread_id = ? ", array($thread['id']));
                foreach ($comments as $comment) {
                    $db->query("DELETE FROM comment WHERE id = ? ", array($comment['id']));
                }
                $db->query("DELETE FROM thread WHERE id = ? ", array($thread['id']));
            }
            $db->query("DELETE FROM user WHERE id = ? ", array($id));
            $db->commit();
            return true;
        } else {
            $this->error_message = "Authentication Failed! You are not allowed to delete this User!";
            return false;
        }
    }
    public function followedThreads()
    {
        $followed = array();
        $db = DB::conn();
        $rows = $db->rows('SELECT * FROM follow WHERE user_id = ?', array($this->user_id));
        foreach ($rows as $row) {
            $followed[$row['thread_id']] = true;
        }
        return $followed;
    }
    public function likedComments()
    {
        $liked = array();
        $db = DB::conn();
        $rows = $db->rows('SELECT * FROM likes WHERE user_id = ?', array($this->user_id));
        foreach ($rows as $row) {
            $liked[$row['comment_id']] = true;
        }
        return $liked;
    }
    public static function search($type, $query)
    {
        $results = array();
        $query = "%{$query}%";
        $db = DB::conn();
        switch ($type) {
            case ('user'):
                $rows = $db->rows('SELECT * FROM user
                    WHERE username LIKE ? OR first_name LIKE ? OR last_name LIKE ? OR email LIKE ? ',
                    array($query, $query, $query, $query) );
                foreach ($rows as $row) {
                    $row['avatar'] = empty($row['avatar']) ? '/public_images/default.jpg' : $row['avatar'];
                    $results[] = $row;
                }
                break;
            case ('thread'):
                $rows = $db->rows('SELECT * FROM thread WHERE title LIKE ? ', array($query));
                foreach ($rows as $row) {
                    $thread_info = Thread::getThreadInfo($row['id']);
                    $row = array_merge($row, $thread_info);
                    $results[] = new Thread($row);
                }
                break;
            case ('comment'):
                $rows = $db->rows('SELECT * FROM comment WHERE body LIKE ? ', array($query));
                foreach ($rows as $row) {
                    $results[] = new Comment(Comment::getCommentInfo($row['id']));
                }
                break;
            default:
                throw new NotFoundException("{$type} is not found");
                break;
        }
        return $results;
    }
}
