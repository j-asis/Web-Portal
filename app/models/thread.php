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
        $rows = $db->rows("SELECT * FROM thread ORDER BY created DESC LIMIT {$offset}, {$limit}");
        foreach ($rows as $row) {
            $thread_info = objectToArray(self::getThreadInfo($row['id']));
            $row = array_merge($row, $thread_info);
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
            $row = array('error'=>'Not Exsisting Thread');
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
            'title'   => $this->title,
        );
        $db->insert('thread', $params);
        $this->id = $db->lastInsertId();
        $comment->write($this);
        $db->commit();
    }

    public static function getThreadInfo($id = NULL)
    {
        if (empty($id)) {
            return false;
        }
        $db = DB::conn();
        $row = $db->row('SELECT * FROM thread WHERE id = ?', array($id));
        $returns = array(
            'id'          => $id,
            'username'    => User::getUserName($row['user_id']),
            'date'        => $row['created'],
            'user_id'     => $row['user_id'],
            'num_comment' => Comment::countAllComments($id),
            'avatar'      => User::getAvatar($row['user_id']),
            'num_follow'  => Follow::count($id),
            'title'       => $row['title'],
        );
        return new self($returns);
    }

    public function editThread()
    {
        if (!$this->validate()) {
            throw new ValidationException('invalid comment');
        }
        $db = DB::conn();
        try {
            $db->begin();
            $db->update('thread', array('title'=>$this->title), array('id'=>$this->thread_id));
            $db->commit();
        } catch (Exception $e) {
            $db->rollback();
            throw $e;
        }
    }

    public function top($table)
    {
        $top_threads = array();
        $db = DB::conn();
        $nums = $db->rows("SELECT COUNT(*) as num FROM {$table} GROUP BY thread_id ORDER BY num DESC");
        $last = 0;
        $limit = 0;
        $max_thread = 10;
        foreach ($nums as $num) {
            if ($limit < $max_thread || $last === $num['num']) {
                $limit++;
                $last = $num['num'];
            }
        }
        $rows = $db->rows("SELECT thread_id, COUNT(*) as num FROM {$table} GROUP BY thread_id ORDER BY num DESC LIMIT 0, {$limit}");
        foreach ($rows as $row) {
            $info = objectToArray(self::getThreadInfo($row['thread_id']));
            $top_threads[] = new self($info);
        }
        return $top_threads;
    }

    public static function userThread($id, $offset, $limit)
    {
        $threads = array();
        $db = DB::conn();
        $rows = $db->rows("SELECT * FROM thread WHERE user_id = ? ORDER BY created DESC LIMIT {$offset}, {$limit}", array($id));
        foreach ($rows as $row) {
            $thread_info = objectToArray(self::getThreadInfo($row['id']));
            $threads[] = new self(array_merge($row,$thread_info));
        }
        return $threads;
    }

    public static function countByUser($user_id)
    {
        $db = DB::conn();
        $value = $db->value('SELECT COUNT(*) FROM thread WHERE user_id = ?', array($user_id));
        return $value;
    }
}
