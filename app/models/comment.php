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
            $row['like_count'] = Like::count($row['id']);
            $comments[] = new self($row);
        }
        return $comments;
    }

    public static function countAllComments($thread_id)
    {
        $db = DB::conn();
        return (int) $db->value("SELECT COUNT(*) FROM comment WHERE thread_id = ?", array($thread_id));
    }

    public static function getCommentContent($id)
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

    public static function getCommentInfo($id)
    {
        $db = DB::conn();
        $row = $db->row('SELECT * FROM comment WHERE id = ? ', array($id));
        $user_detail = objectToArray(User::getUserDetail($row['user_id']));
        $like_count = Like::count($row['id']);
        $returns = array(
            'username' => $user_detail['username'],
            'avatar' => $user_detail['avatar'],
            'like_count' => $like_count,
        );
        $returns = array_merge($returns, $row);
        return new self($returns);
    }

    public static function getMostLiked()
    {
        $db = DB::conn();
        $nums = $db->rows('SELECT COUNT(*) as num FROM likes GROUP BY comment_id ORDER BY num DESC');
        $last = 0;
        $limit = 0;
        $max_comment = 10;
        foreach ($nums as $num) {
            if ($limit < $max_comment || $last === $num['num']) {
                $limit++;
                $last = $num['num'];
            }
        }
        $rows = $db->rows("SELECT comment_id, COUNT(*) as num FROM likes GROUP BY comment_id ORDER BY num DESC LIMIT 0, {$limit}");
        $comments = array();
        foreach ($rows as $row) {
            $params = objectToArray(self::getCommentInfo($row['comment_id']));
            $comments[] = new self($params);
        }
        return $comments;
    }
}
