<?php

class UserController extends AppController 
{
    public function index()
    {
        $user = new User;
        $is_logged = isset($_SESSION['username']);
        if (!$is_logged) {
            redirect(url(''));
        }
        $home = '/user/index';
        $user->username = $_SESSION['username'];
        $user_id = $user->getUserId($user->username);
        $user_details = objectToArray($user->getUserDetail($user_id));

        $this->set(get_defined_vars());
    }
    public function logout()
    {
        session_unset('username');
        session_destroy();
        redirect(url(''));
    }
    public function viewUser()
    {
        $user = new User;
        $is_logged = isset($_SESSION['username']);
        if (!$is_logged) {
            redirect(url(''));
        }
        $home = '/user/index';
        $user->username = $_SESSION['username'];
        $user_id = Param::get('user_id',0);
        $user_details = $user->getUserDetail($user_id);
        $this->set(get_defined_vars());
    }
}
