<?php

class Like extends AppModel
{
    public static function count($comment_id)
    {
        $db = DB::conn();
        return (int) $db->value('SELECT COUNT(*) FROM likes WHERE comment_id = ?', array($comment_id));
    }

    public static function add($comment_id, $user_id)
    {
        $db = DB::conn();
        $params = array(
            'user_id' => $user_id,
            'comment_id' => $comment_id,
        );
        $id = (int) $db->value('SELECT id FROM likes WHERE user_id = ? AND comment_id = ?',array($user_id, $comment_id));
        if ($id !== 0) {
            return;
        }
        try {
            $db->insert('likes', $params);
        } catch (Exception $e) {
            throw $e;
        }
    }
    
    public static function remove($comment_id, $user_id)
    {
        $db = DB::conn();
        $id = (int) $db->value('SELECT id FROM likes WHERE user_id = ? AND comment_id = ?',array($user_id, $comment_id));
        try {
            $db->query('DELETE FROM likes WHERE id = ?', array($id));
        } catch (Exception $e) {
            throw $e;
        }
    }
}
