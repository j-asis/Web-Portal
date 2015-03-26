<?php
class Thread extends AppModel
{
    const MIN_STRING_LENGTH = 1;
    const MAX_STRING_LENGTH = 80;

    public $validation = array(
       'title' => array(
            'length' => array(
               'validate_between', self::MIN_STRING_LENGTH, self::MAX_STRING_LENGTH,
            ),
        ),
    );

    public static function getAll($offset, $limit)
    {
        $threads = array();
        $db = DB::conn();
        $query = sprintf("SELECT * FROM thread LIMIT %d, %d", $offset, $limit);
        $rows = $db->rows($query);
        foreach ($rows as $row) {
            $threads[] = new self($row);
        }
        return $threads;
    }

    public static function countAll()
    {
        $db = DB::conn();
        return (int) $db->value("SELECT COUNT(*) FROM thread");
    }

    public static function countAllComments($thread_id)
    {
        $db = DB::conn();
        return (int) $db->value("SELECT COUNT(*) FROM comment WHERE thread_id = ?", array($thread_id));
    }

    public static function get($id)
    {
        $db = DB::conn();
        $row = $db->row('SELECT * FROM thread WHERE id = ?', array($id));
        if (!$row) {
            throw new RecordNotFoundException('no record found');
        }
        return new self($row);
    }

    public function getComments($offset, $limit)
    {
        $comments = array();
        $db = DB::conn();
        $query = sprintf("SELECT * FROM comment WHERE thread_id = ? ORDER BY created ASC LIMIT %d, %d", $offset, $limit);
        $rows = $db->rows($query, array($this->id));
        foreach ($rows as $row) {
            $row['username'] = $this->getUserName($row['user_id']);
            $comments[] = new Comment($row);
        }
        return $comments;
    }

    public function write(Comment $comment)
    {
        if (!$comment->validate()) {
            throw new ValidationException('invalid comment');
        }
        $db = DB::conn();
        $db->query('INSERT INTO comment SET thread_id = ?, user_id = ?, body = ?, created = NOW()', array($this->id, $comment->user_id, $comment->body));
    }

    public function create(Comment $comment)
    {
        $this->validate();
        $comment->validate();
        if ($this->hasError() || $comment->hasError()) {
            throw new ValidationException('invalid thread or comment');
        }
        $db = DB::conn();
        $db->begin();
        $params = array(
            'user_id' => $comment->user_id,
            'title' => $this->title,
        );
        $db->insert('thread', $params);
        $this->id = $db->lastInsertId();
        $this->write($comment);
        $db->commit();
    }

    public function getThreadInfo()
    {
        $thread_user_id = array();
        $db = DB::conn();
        $row = $db->row('SELECT * FROM thread WHERE id = ?', array($this->id));
        foreach ($rows as $row) {
            $thread_user_id[0] = $row['user_id'];
            $thread_user_id[1] = $row['created'];
        }
        $thread_username = $db->row('SELECT username FROM user WHERE id = ?', array($thread_user_id[0]));
        return array(
            'username' => $thread_username['username'],
            'date' => $thread_user_id[1],
            'user_id' => $thread_user_id[0]
        );
    }

    public function getUserName($user_id)
    {
        $db = DB::conn();
        $row = $db->row('SELECT username FROM user WHERE id = ?', array($user_id));
        return $row['username'];
    }
    
    public function getUserId($username)
    {
        $db = DB::conn();
        $row = $db->row('SELECT id FROM user WHERE username = ?', array($username));
        return $row['id'];
    }
}
