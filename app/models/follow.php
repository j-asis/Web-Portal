<?php

class Follow extends AppModel
{

    public static function count($thread_id)
    {
        $db = DB::conn();
        return (int) $db->value('SELECT COUNT(*) FROM follow WHERE thread_id = ?', array($thread_id));
    }

    public static function add($thread_id, $user_id)
    {
        $db = DB::conn();
        $params = array(
            'user_id' => $user_id,
            'thread_id' => $thread_id,
        );
        $id = (int) $db->value('SELECT id FROM follow WHERE thread_id = ? AND user_id = ?', array($thread_id, $user_id));
        try {
            $db->insert('follow', $params);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public static function remove($thread_id, $user_id)
    {
        $db = DB::conn();
        $id = (int) $db->value('SELECT id FROM follow WHERE thread_id = ? AND user_id = ?', array($thread_id, $user_id));
        try {
            $db->query('DELETE FROM follow WHERE id = ?', array($id));
        } catch (Exception $e) {
            throw $e;
        }
    }
}
