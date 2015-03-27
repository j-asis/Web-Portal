<?php

class User extends AppModel
{
    public function getUserDetail($id)
    {
        try {
            $db = DB::conn();
            $row = $db->row('SELECT * FROM user WHERE id = ?', array($id));
        } catch (Exception $e) {
            throw new Exception("Mysql Error, record not found");
        }
        return new self($row);
    }
    public static function getUserName($user_id)
    {
        $user = new User;
        $details = $user->getUserDetail($user_id);
        return $details->username;
    }
    public static function getUserId($username)
    {
        try {
            $db = DB::conn();
            $row = $db->row('SELECT id FROM user WHERE username = ?', array($username));
        } catch (Exception $e) {
            throw new Exception("Mysql Error, record not found");
        }
        return !empty($row['id']) ? $row['id'] : false;
    }
}
