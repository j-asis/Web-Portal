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
        $query = sprintf("SELECT * FROM thread ORDER BY created DESC LIMIT %d, %d", $offset, $limit);
        $rows = $db->rows($query);
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
        $thread_username = $db->row('SELECT username,avatar FROM user WHERE id = ?', array($row['user_id']));
        $num_comment = $db->value('SELECT COUNT(*) FROM comment WHERE thread_id = ?', array($id));
        $num_follow = $db->value('SELECT COUNT(*) FROM follow WHERE thread_id = ?', array($id));
        $thread_username['avatar'] = empty($thread_username['avatar']) ? '/public_images/default.jpg' : $thread_username['avatar'];
        $returns = array(
            'id'          => $id,
            'username'    => $thread_username['username'],
            'date'        => $row['created'],
            'user_id'     => $row['user_id'],
            'num_comment' => $num_comment,
            'avatar'      => $thread_username['avatar'],
            'num_follow'  => $num_follow,
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

    public function topThreads($table)
    {
        $top_threads = array();
        $db = DB::conn();
        $nums = $db->rows("SELECT COUNT(*) as num FROM {$table} GROUP BY thread_id ORDER BY num DESC");
        $last = 0;
        $limit = 0;
        $max_thread = 10;
        foreach ($nums as $num) {
            if ($last > $num['num']) {
                continue;
            }
            if ($limit >= $max_thread) {
                $last = $num['num'];
            }
            $limit++;
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
        $query = sprintf("SELECT * FROM thread WHERE user_id = %d ORDER BY created DESC LIMIT %d, %d", $id, $offset, $limit);
        $rows = $db->rows($query);
        foreach ($rows as $row) {
            $thread_info = objectToArray(self::getThreadInfo($row['id']));
            $row = array_merge($row,$thread_info);
            $threads[] = new self($row);
        }
        return $threads;
    }

    public static function countByUser($user_id)
    {
        $db = DB::conn();
        $value = $db->value('SELECT COUNT(*) FROM thread WHERE user_id = ?', array($user_id));
        return $value;
    }

    public function follow()
    {
        $db = DB::conn();
        try {
            $db->begin();
            if ($this->follow_type === 'follow') {
                $row = $db->row('SELECT * FROM follow WHERE user_id = ? AND thread_id = ?', array($this->user_id, $this->follow_id));
                if (empty($row)) {
                    $params = array(
                        'user_id'   => $this->user_id,
                        'thread_id' => $this->follow_id,
                        );
                    $db->replace('follow',$params);
                }
            } elseif ($this->follow_type === 'unfollow') {
                $db->query('DELETE FROM follow WHERE user_id = ? AND thread_id = ?', array($this->user_id, $this->follow_id));
            }
            $db->commit();
        } catch (Exception $e) {
            $db->rollback();
            throw $e;
        }
    }
}
