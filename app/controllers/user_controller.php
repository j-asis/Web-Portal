<?php

class UserController extends AppController 
{
    public function index()
    {
        $user = new User;
        $home = '/user/index';
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
        $home = '/user/index';
        $user_id = Param::get('user_id',0);
        $other_user = $user->getUserDetail($user_id);
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
    public function changePassword()
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
}
