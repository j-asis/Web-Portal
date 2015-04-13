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
            'id'            => $id,
            'username'      => User::getUserName($row['user_id']),
            'date'          => $row['created'],
            'user_id'       => $row['user_id'],
            'comment_count' => Comment::countAll($id),
            'avatar'        => User::getAvatar($row['user_id']),
            'follow_count'  => Follow::count($id),
            'title'         => $row['title'],
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

    public static function getMostCommented($limit)
    {
        $threads = array();
        $thread_ids = Comment::getTopThreads($limit);
        foreach ($thread_ids as $thread_id) {
            $threads[] = self::getThreadInfo($thread_id['id']);
        }
        return $threads;
    }

    public static function getMostFollowed($limit)
    {
        $threads = array();
        $thread_ids = Follow::getTopThreads($limit);
        foreach ($thread_ids as $thread_id) {
            $threads[] = self::getThreadInfo($thread_id['id']);
        }
        return $threads;
    }

    public static function deleteById($id)
    {
        Follow::deleteByThreadId($id);
        Comment::deleteByThreadId($id);
        $db = DB::conn();
        try {
            $db->query('DELETE FROM thread WHERE id = ? ', array($id));
        } catch (Exception $e) {
            throw $e;
        }
        return true;
    }

    public static function deleteByUserId($user_id)
    {
        $db = DB::conn();
        try {
            $rows = $db->rows('SELECT * FROM thread WHERE user_id = ?', array($user_id));
            foreach ($rows as $thread) {
                self::deleteById($thread['id']);
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    public static function searchByQuery($query)
    {
        $results = array();
        $db = DB::conn();
        $rows = $db->rows('SELECT * FROM thread WHERE title LIKE ? ', array($query));
        foreach ($rows as $row) {
            $thread_info = objectToArray(Thread::getThreadInfo($row['id']));
            $row = array_merge($row, $thread_info);
            $results[] = new Thread($row);
        }
        return $results;
    }

    public static function getLatestByUserId($user_id)
    {
        $recent = array();
        $db = DB::conn();
        $rows = $db->rows("SELECT * FROM thread WHERE user_id = ? ORDER BY created DESC LIMIT 0, 3 ", array($user_id));
        foreach ($rows as $row) {
            $recent[] = Thread::getThreadInfo($row['id']);
        }
        return $recent;
    }
}
