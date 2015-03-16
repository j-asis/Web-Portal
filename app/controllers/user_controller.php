<?php

class UserController extends AppController 
{
    public function index()
    {
        $user = new User;
        $is_logged = isset($_SESSION['username']);
        if (!$is_logged) {
            redirect('/');
        }
        $home = '/user/index';
        $user->username = $_SESSION['username'];
        
        $page = Param::get('page', 1);
        $per_page = 5;
        $pagination = new SimplePagination($page, $per_page);
        $threads = Thread::getAll($pagination->start_index - 1, $pagination->count + 1);
        $pagination->checkLastPage($threads);
        $total = Thread::countAll();
        $pages = ceil($total / $per_page);
        
        $this->set(get_defined_vars());
        $this->render('/thread/index');
    }
    public function logout()
    {
        $user = new User;
        session_unset('username');
        session_destroy();
        redirect('/');
    }
}
