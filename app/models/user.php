<?php

class User extends AppModel
{
    public function getUserDetail($id)
    {
        try{
            $db = DB::conn();
            $row = $db->row('SELECT * FROM user WHERE id = ?', array($id));
        } catch (Exception $e) {
            throw new Exception("Mysql Error, record not found");
        }
        return $row;
    }
    public function getUserName($user_id)
    {
        try{
            $db = DB::conn();
            $row = $db->row('SELECT username FROM user WHERE id = ?', array($user_id));
        } catch (Exception $e) {
            throw new Exception("Mysql Error, record not found");
        }
        return !empty($row['username']) ? $row['username'] : false;
    }
    public function getUserId($username)
    {
        try{
            $db = DB::conn();
            $row = $db->row('SELECT id FROM user WHERE username = ?', array($username));
        } catch (Exception $e) {
            throw new Exception("Mysql Error, record not found");
        }
        return !empty($row['id']) ? $row['id'] : false;
    }
}
