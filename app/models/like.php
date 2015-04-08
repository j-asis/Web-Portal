<?php

class Like extends AppModel
{
    public static function count($comment_id)
    {
        $db = DB::conn();
        return (int) $db->value('SELECT COUNT(*) FROM likes WHERE comment_id = ?', array($comment_id));
    }

    public function comment()
    {
        $db = DB::conn();
        $like_id = (int) $db->value('SELECT id FROM likes
        WHERE user_id = ? AND comment_id = ?', array($this->user_id, $this->comment_id));
        if ($this->type === 'like' && $like_id === 0) {
            self::addLike($this->user_id, $this->comment_id);
        }
        if ($this->type === 'unlike') {
            self::removeLike($like_id);
        }
    }

    public static function addLike($user_id, $comment_id)
    {
        $db = DB::conn();
        $db->begin();
        $params = array(
            'user_id' => $user_id,
            'comment_id' => $comment_id,
        );
        try {
            $db->insert('likes', $params);
            $db->commit();
        } catch (Exception $e) {
            $db->rollback();
            throw $e;
        }
    }
    
    public static function removeLike($id)
    {
        $db = DB::conn();
        $db->begin();
        try {
            $db->query('DELETE FROM likes WHERE id = ?', array($id));
            $db->commit();
        } catch (Exception $e) {
            $db->rollback();
            throw $e;
        }
    }
}
