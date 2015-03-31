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
            $like_count = $db->value('SELECT COUNT(*) FROM likes WHERE comment_id = ?', array($row['id']));
            $user_detail = User::getUserDetail($row['user_id']);
            $row['username'] = $user_detail['username'];
            $row['avatar'] = $user_detail['avatar'];
            $row['like_count'] = $like_count;
            $comments[] = new Comment($row);
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
            throw new RecordNotFoundException('no record found');
        }
        return new self($row);
    }
    public function editComment()
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
    public function like()
    {
        $db = DB::conn();
        try{
            $db->begin();
            if ($this->type === 'like') {
                $row = $db->row('SELECT * FROM likes WHERE user_id = ? AND comment_id = ?', array($this->user_id, $this->comment_id));
                if (empty($row)) {
                    $params = array(
                        'user_id' => $this->user_id,
                        'comment_id' => $this->comment_id,
                    );
                    $db->replace('likes',$params);
                }
            } elseif ($this->type === 'unlike') {
                $db->query('DELETE FROM likes WHERE user_id = ? AND comment_id = ?', array($this->user_id, $this->comment_id));
            }
            $db->commit();
        } catch (Exception $e) {
            $db->rollback();
            throw $e;
        }
    }
}
