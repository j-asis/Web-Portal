<?php

class Like extends AppModel
{
    public static function count($comment_id)
    {
        $db = DB::conn();
        return (int) $db->value('SELECT COUNT(*) FROM likes WHERE comment_id = ?', array($comment_id));
    }
}
