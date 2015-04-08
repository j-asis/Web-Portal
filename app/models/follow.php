<?php

class Follow extends AppModel
{

    public static function count($thread_id)
    {
        $db = DB::conn();
        return (int) $db->value('SELECT COUNT(*) FROM follow WHERE thread_id = ?', array($thread_id));
    }

    public function thread()
    {
        $db = DB::conn();
        $follow_id = (int) $db->value('SELECT id FROM follow
        WHERE user_id = ? AND thread_id = ?', array($this->user_id, $this->follow_id));
        if ($this->follow_type === 'follow' && $follow_id === 0) {
            self::addFollow($this->user_id, $this->follow_id);
        }
        if ($this->follow_type === 'unfollow') {
            self::removeFollow($follow_id);
        }
    }

    public static function addFollow($user_id, $thread_id)
    {
        $db = DB::conn();
        $db->begin();
        $params = array(
            'user_id' => $user_id,
            'thread_id' => $thread_id,
        );
        try {
            $db->insert('follow', $params);
            $db->commit();
        } catch (Exception $e) {
            $db->rollback();
            throw $e;
        }
    }

    public static function removeFollow($id)
    {
        $db = DB::conn();
        $db->begin();
        try {
            $db->query('DELETE FROM follow WHERE id = ?', array($id));
            $db->commit();
        } catch (Exception $e) {
            $db->rollback();
            throw $e;
        }
    }
}
