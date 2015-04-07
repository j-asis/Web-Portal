<?php

class Follow extends AppModel
{
    public static function count($thread_id)
    {
        $db = DB::conn();
        return (int) $db->value('SELECT COUNT(*) FROM follow WHERE thread_id = ?', array($thread_id));
    }
}
