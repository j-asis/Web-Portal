<?php

class UserController extends AppController 
{
    const MAX_POST_SIZE = 8000000;
    public function logout()
    {
        session_unset('username');
        session_destroy();
        redirect(url('/'));
    }
    public function profile()
    {
        $user = new User;
        $home = '/user/profile';
        $user_id = Param::get('user_id', $user->user_id);
        $user_info = $user->getUserDetail($user_id);
        $title = $user_info['username'];
        $this->set(get_defined_vars());
    }
    public function update()
    {
        $user = new User;
        $update = Param::get('update');
        if ($update) {
            $user->new_username = Param::get('username');
            $user->new_first_name = Param::get('first_name');
            $user->new_last_name = Param::get('last_name');
            $user->new_email = Param::get('email');
            if (!($user->new_username === $user->username)) {

                if (Register::user_exists($user->new_username)) {
                    $user->validation_errors['new_username']['exists'] = true;
                }
                
            }
            try {
                $user->updateUser();
                $_SESSION['username'] = $user->new_username;
            } catch (ValidationException $e) {
                $error = true;
            } catch (Exception $e) {
                $db_error = true; 
            }
        }
        $this->set(get_defined_vars());
    }
    public function change_password()
    {
        $user = new User;
        $check = Param::get('check');
        if ($check) {

            $password = Param::get('old_password');
            $new_password = Param::get('new_password');
            $cnew_password = Param::get('cnew_password');

            if (md5($password) === $user->user_details['password']) {

                if ($new_password === $cnew_password) {

                    $user->new_password = $new_password;

                    try {
                        $user->changePassword();
                        $change_success = true;
                    } catch(Exception $e) {
                        $error_message = "Unexpected Error!";
                    }

                } else {
                    $error_message = "New Password did not match!";
                }

            } else {
                $error_message = "Wrong Password!";
            }
        }
        $this->set(get_defined_vars());
    }
    public function delete()
    {
        $user = new User;
        $type = Param::get('type');
        $id = Param::get('id');
        $url_back = urldecode(Param::get('url_back'));
        $password = Param::get('password','');
        $confirm = Param::get('confirm','false');
        $is_success = false;
        
        if (($type === 'comment') && $confirm === 'true') {

            $is_success = $user->deleteComment($id);

        } elseif (($type === 'thread') && $confirm === 'true') {

            $is_success = $user->deleteThread($id);

        } elseif ($type === 'user') {

            $check = Param::get('check');
            if ($check) {

                $password = Param::get('password');
                if ( md5($password) === $user->user_details['password'] ) {

                    $is_success = $user->deleteUser($id);
                    $url_back = '/user/logout';

                }
            }
        }
        $this->set(get_defined_vars());
    }
    public function upload_image()
    {
        $user = new User;
        $this->set(get_defined_vars());
    }
    public function upload()
    {
        $user = new User;
        if (self::MAX_POST_SIZE < $_SERVER['CONTENT_LENGTH']) {
            $error = "Error! File Size too big! Can only upload 2 Mega Bytes";
        }
        if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === 0) {
            $file = $_FILES['avatar'];
            $upload = new UploadImg($file);
            if ($upload->isFileAccepted()) {
                $upload->rename($user->username);
                if (strpos($user->user_details['avatar'], 'default') === false) {
                    unlink(APP_DIR.'webroot'.$user->user_details['avatar']);
                }
                $saved = $upload->save('webroot/public_images/');
                if ($saved) {
                    $user->saveAvatar($saved);
                    chmod(APP_DIR.'webroot'.$saved, 0755);
                } else {
                    $error = "Unexpected Error Occured";
                }
            } else {
                $error = "File Upload Error";
            }
        } elseif ($_FILES['avatar']['error'] > 0) {
            $error = "Error! File Size too big! Can only upload 2 Mega Bytes";
        } elseif (!isset($error)) {
            redirect('/');
        }
        $this->set(get_defined_vars());
    }
    public function search()
    {
        $type = Param::get('type', false);
        $query = Param::get('query', false);
        if (!$type || !$query) {
            redirect(url('/'));
        }
        $user = new User;
        switch ($type) {
            case ('user'):
                $url_params = "?type=user&query={$query}";
                $title = sprintf("Search for '%s' in users", $query);
                $render_page = '/user/search';
                $query_results = $user->search($type, $query);
                $varname = 'query_results';
                break;
            case ('thread'):
                $url_params = "?type=thread&query={$query}";
                $title = sprintf("Search for '%s' in threads", $query);
                $query_results = $user->search($type, $query);
                $render_page = '/thread/index';
                $varname = 'threads';
                break;
            case ('comment'):
                $url_params = "?type=comment&query={$query}";
                $query_results = $user->search($type, $query);
                $title = sprintf("Search for '%s' in comments", $query);
                $render_page = '/comment/view';
                $varname = 'comments';
                break;
            default:
                redirect(url('/'));
                break;
        }
        $page = Param::get('page', 1);
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
        ${$varname} = $results;
        $pages = (int) ceil($total / $per_page);
        $pagination->is_last_page = $pages === $page ? true : false;
        $sub_title = sprintf("Showing %d results found", $total);
        
        $this->set(get_defined_vars());
        $this->render($render_page);
    }
}
