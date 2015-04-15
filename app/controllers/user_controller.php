<?php

class UserController extends AppController 
{

    public function logout()
    {
        session_unset('username');
        session_destroy();
        redirect(url('login/index'));
    }

    public function profile()
    {
        if (!isset($_SESSION['username'])) {
            redirect(url('/'));
        }
        $user = new User();
        $user->setInfoByUsername($_SESSION['username']);
        $home = '/user/profile';
        $user_id = Param::get('user_id', $user->user_id);
        $user_info = objectToArray($user->getInfoById($user_id));
        $recent_threads = Thread::getLatestByUserId($user_info['id']);
        $recent_comments = Comment::getLatestByUserId($user_info['id']);
        $title = $user_info['username'];
        $this->set(get_defined_vars());
    }

    public function update()
    {
        if (!isset($_SESSION['username'])) {
            redirect(url('/'));
        }
        $user = new User();
        $user->setInfoByUsername($_SESSION['username']);
        $update = Param::get('update', false);
        $is_same_data = true;
        if (!$update) {
            $this->set(get_defined_vars());
            return;
        }
        $user->new_username    = Param::get('username', '');
        $user->new_first_name  = Param::get('first_name', '');
        $user->new_last_name   = Param::get('last_name', '');
        $user->new_email       = Param::get('email', '');
        $is_same_data = $user->isUnchanged();
        if ($is_same_data) {
            $this->set(get_defined_vars());
            return;
        }
        try {
            $user->update();
            $success = true;
        } catch (ValidationException $e) {
            $success = false;
        }
        if ($success) {
            $_SESSION['username'] = $user->new_username;
            $user = new User();
            $user->setInfoByUsername($_SESSION['username']);
        }
        $this->set(get_defined_vars());
    }

    public function change_password()
    {
        if (!isset($_SESSION['username'])) {
            redirect(url('/'));
        }
        $user = new User();
        $user->setInfoByUsername($_SESSION['username']);
        $check = Param::get('check', false);
        if (!$check) {
            $this->set(get_defined_vars());
            return;
        }
        $user->old_password = Param::get('old_password', '');
        $user->new_password = Param::get('new_password', '');
        $user->confirm_new_password = Param::get('confirm_new_password', '');
        try {
            $user->changePassword();
        } catch (ValidationException $e) {
            $user->error = true;
        }
        $this->set(get_defined_vars());
    }

    public function delete()
    {
        if (!isset($_SESSION['username'])) {
            redirect(url('/'));
        }
        $user = new User();
        $user->setInfoByUsername($_SESSION['username']);
        $type = Param::get('type', '');
        $id = Param::get('id', '');
        $url_back = urldecode(Param::get('url_back', '/'));
        $password = Param::get('password','');
        $confirm = Param::get('confirm', 'false');
        $is_success = false;
        
        if (($type === 'comment') && $confirm === 'true') {
            $is_success = Comment::deleteById($id);
            $this->set(get_defined_vars());
            return;
        }
        if (($type === 'thread') && $confirm === 'true') {
            $is_success = Thread::deleteById($id);
            $this->set(get_defined_vars());
            return;
        }
        if ($type === 'user') {
            $check = Param::get('check', false);
            if ($id !== $user->user_id) {
                $user->auth_error = "Cannot Delete Other User's Account!";
                $this->set(get_defined_vars());
                return;
            }
            if (!$check) {
                $this->set(get_defined_vars());
                return;
            }
            $password = Param::get('password', '');
            if ( md5($password) === $user->user_details['password'] ) {
                $is_success = User::deleteById($id);
                $url_back = '/user/logout';
            }
        }
        $this->set(get_defined_vars());
    }

    public function upload_image()
    {
        if (!isset($_SESSION['username'])) {
            redirect(url('/'));
        }
        $user = new User();
        $user->setInfoByUsername($_SESSION['username']);
        $this->set(get_defined_vars());
    }

    public function upload()
    {
        if (!isset($_SESSION['username'])) {
            redirect(url('/'));
        }
        $user = new User();
        $user->setInfoByUsername($_SESSION['username']);
        $upload = new UploadImg();
        if (!isset($_FILES['avatar']) || $upload->error['fatal']) {
            redirect(url('user/profile'));
        }
        $file = $_FILES['avatar'];
        $upload->set($file);
        if (!$upload->isFileAccepted()) {
            $this->set(get_defined_vars());
            return;
        }
        $upload->rename($user->username);
        if (strpos($user->user_details['avatar'], 'default') === false) {
            unlink(APP_DIR.'webroot'.$user->user_details['avatar']);
        }
        $saved = $upload->save('webroot/public_images/');
        if (!$saved) {
            $error = "Unexpected Error Occured";
            $this->set(get_defined_vars());
            return;
        }
        $user->saveAvatar($saved);
        chmod(APP_DIR.'webroot'.$saved, 0755);
        $this->set(get_defined_vars());
    }

    public function search()
    {
        if (!isset($_SESSION['username'])) {
            redirect(url('/'));
        }
        $type = Param::get('type', false);
        $query = Param::get('query', false);
        if (!$type || !$query) {
            redirect(url('user/profile'));
        }
        $user = new User();
        $user->setInfoByUsername($_SESSION['username']);
        switch ($type) {
            case User::USER_SEARCH_KEY:
                $render_page = '/user/search';
                $varname = 'query_results';
                $query_results = User::searchByQuery($query);
                break;
            case User::THREAD_SEARCH_KEY:
                $render_page = '/thread/index';
                $varname = 'threads';
                $query_results = Thread::searchByQuery($query);
                break;
            case User::COMMENT_SEARCH_KEY:
                $render_page = '/comment/view';
                $varname = 'comments';
                $query_results = Comment::searchByQuery($query);
                break;
            default:
                redirect(url('user/profile'));
                break;
        }
        $title = sprintf("Search for '%s' in %s", $query, $type);
        $url_params = "?type={$type}&query={$query}";
        $page = Param::get('page', User::DEFAULT_SEARCH_PAGE);
        $page = (int) $page;
        $per_page = 5;
        $pagination = new SimplePagination($page, $per_page);
        $results = array();
        for ($i = ($pagination->start_index - 1); $i < (($pagination->count)*$page); $i++) {
            if (isset($query_results[$i])) {
                $results[] = $query_results[$i];
            }
        }
        $total = count($query_results);
        $this->set($varname, $results);
        $pages = (int) ceil($total / $per_page);
        $pagination->is_last_page = $pages === $page ? true : false;
        $sub_title = sprintf("Showing %d results found", $total);
        $this->set(get_defined_vars());
        $this->render($render_page);
    }
}
