<?php
session_start();
class UserController extends AppController 
{
    public function index()
    {
        $user = new User;
        $is_logged = isset($_SESSION['username']);
        if(!$is_logged){
            redirect('/');
        }
        $user->username = $_SESSION['username'];
        $this->set(get_defined_vars());

    }

    public function logout(){
        session_unset('username');
        session_destroy();
        redirect('/');
    }

}