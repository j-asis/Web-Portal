<?php
class Comment extends AppModel
{
    const MIN_STRING_LENGTH = 1;
    const MAX_STRING_LENGTH = 200;
    
    public $validation = array(
        'body' => array(
            'length' => array(
                'validate_between', self::MIN_STRING_LENGTH, self::MAX_STRING_LENGTH,
            ),
        ),
    );

    public function write($thread)
    {
        if (!$this->validate()) {
            throw new ValidationException('invalid comment');
        }
        $db = DB::conn();
        $db->query('INSERT INTO comment SET thread_id = ?, user_id = ?, body = ?, created = NOW()', array($thread->id, $this->user_id, $this->body));
    }

    public static function getByThreadId($id, $offset, $limit)
    {
        $comments = array();
        $db = DB::conn();
        $query = sprintf("SELECT * FROM comment WHERE thread_id = ? ORDER BY created DESC LIMIT %d, %d", $offset, $limit);
        $rows = $db->rows($query, array($id));
        foreach ($rows as $row) {
            $row['username'] = User::getUserName($row['user_id']);
            $row['avatar'] = User::getAvatar($row['user_id']);
            $row['like_count'] = Likes::count($row['id']);
            $comments[] = new self($row);
        }
        return $comments;
    }

    public static function countAll($thread_id)
    {
        $db = DB::conn();
        return (int) $db->value("SELECT COUNT(*) FROM comment WHERE thread_id = ?", array($thread_id));
    }

    public static function getContent($id)
    {
        $db = DB::conn();
        $row = $db->row('SELECT * FROM comment WHERE id = ? ', array($id));
        if (!$row) {
            $row = array('error'=>'Not Exsisting Comment');
        }
        return new self($row);
    }

    public function edit()
    {
        if (!$this->validate()) {
            throw new ValidationException('invalid comment');
        }
        $db = DB::conn();
        try {
            $db->begin();
            $db->update('comment', array('body'=>$this->body), array('id'=>$this->comment_id));
            $db->commit();
        } catch (Exception $e) {
            $db->rollback();
            throw $e;
        }
    }

    public static function getInfo($id)
    {
        $db = DB::conn();
        $row = $db->row('SELECT * FROM comment WHERE id = ? ', array($id));
        $user_detail = objectToArray(User::getUserDetail($row['user_id']));
        $like_count = Likes::count($row['id']);
        $returns = array(
            'username' => $user_detail['username'],
            'avatar' => $user_detail['avatar'],
            'like_count' => $like_count,
        );
        $returns = array_merge($returns, $row);
        return new self($returns);
    }

    public static function getMostLiked($limit)
    {
        $db = DB::conn();
        $comment_ids = Likes::getTopComments($limit);
        $comments = array();
        foreach ($comment_ids as $comment_id) {
            $comments[] = self::getInfo($comment_id['id']);
        }
        return $comments;
    }

    public static function getTopThreads($limit)
    {
        $db = DB::conn();
        return (array) $db->rows("SELECT thread_id as id, COUNT(*) as num FROM comment GROUP BY thread_id ORDER BY num DESC LIMIT 0, {$limit}");
    }

    public static function getThreadsByCommentCount()
    {
        $db = DB::conn();
        return (array) $db->rows("SELECT COUNT(*) as num FROM comment GROUP BY thread_id ORDER BY num DESC");
    }

    public static function deleteByThreadId($thread_id)
    {
        $db = DB::conn();
        try {
            $rows = $db->rows('SELECT * FROM comment WHERE thread_id = ?', array($thread_id));
            foreach ($rows as $comment) {
                self::deleteById($comment['id']);
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    public static function deleteById($id)
    {
        Likes::deleteByCommentId($id);
        $db = DB::conn();
        try {
            $db->query('DELETE FROM comment WHERE id = ? ', array($id));
        } catch (Exception $e) {
            throw $e;
        }
        return true;
    }

    public static function searchByQuery($query)
    {
        $results = array();
        $db = DB::conn();
        $rows = $db->rows('SELECT * FROM comment WHERE body LIKE ? ', array($query));
        foreach ($rows as $row) {
            $results[] = self::getInfo($row['id']);
        }
        return $results;
    }

    public static function getLatestByUserId($user_id)
    {
        $recent = array();
        $db = DB::conn();
        $rows = $db->rows("SELECT * FROM comment WHERE user_id = ? ORDER BY created DESC LIMIT 0, 3 ", array($user_id));
        foreach ($rows as $row) {
            $recent[] = Comment::getInfo($row['id']);
        }
        return $recent;
    }
}
