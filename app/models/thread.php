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
            $thread_info = self::getThreadInfo($row['id']);
            $row = array_merge($row,$thread_info);
            $threads[] = new self($row);
        }
        return $threads;
    }

    public static function countAll()
    {
        $db = DB::conn();
        return (int) $db->value("SELECT COUNT(*) FROM thread");
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
        $comment->write($this);
        $db->commit();
    }

    public function getThreadInfo($id = NULL)
    {
        if (empty($id)) {
            return false;
        }
        $db = DB::conn();
        $row = $db->row('SELECT * FROM thread WHERE id = ?', array($this->id));
        $thread_username = $db->row('SELECT username,avatar FROM user WHERE id = ?', array($row['user_id']));
        $num_comment = $db->value('SELECT COUNT(*) FROM comment WHERE thread_id = ?', array($id));
        $num_follow = $db->value('SELECT COUNT(*) FROM follow WHERE thread_id = ?', array($id));
        $thread_username['avatar'] = empty($thread_username['avatar']) ? '/public_images/default.jpg' : $thread_username['avatar'];
        $returns = array(
            'id' => $id,
            'username' => $thread_username['username'],
            'date' => $row['created'],
            'user_id' => $row['user_id'],
            'num_comment' => $num_comment,
            'avatar' => $thread_username['avatar'],
            'num_follow' => $num_follow,
        );
        return $returns;
    }
}
