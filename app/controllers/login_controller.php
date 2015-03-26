<?php

class LoginController extends AppController 
{
    public function index()
    {
        $login = new Login;
        $check = Param::get('call');
        $login->error = false;
        $error = false;
        $message = 'Welcome, please log in';
        if ($check) {
            $login->username = Param::get('username');
            $login->password = Param::get('password');
            try {
                $login->checkInput();
                $login->loginAction();
            } catch (ValidationException $e) {
                $error = true;
            } catch (RecordNotFoundException $e) {
                $login->error = true;
            }
            if (!$login->error && !$error) {
                $_SESSION['username'] = $login->username;
            }
        }
        $is_logged = isset($_SESSION['username']);
        if ($is_logged) {
            redirect('/user/index');
        }
        $this->set(get_defined_vars());
    }
}
