<?php

class LoginController extends AppController 
{
    public function index()
    {
        $check = Param::get('call', false);
        $error = false;
        $message = 'Welcome, please log in';
        if ($check) {
            $params = array(
                'username' => Param::get('username', ''),
                'password' => Param::get('password', ''),
                'error'    => false,
            );
            $login = new Login($params);
            try {
                $login->checkInput();
                $login->accept();
            } catch (ValidationException $e) {
                $error = true;
            } catch (RecordNotFoundException $e) {
                $login->error = true;
            }
            if (!$login->error && !$error) {
                $_SESSION['username'] = $login->username;
            }
        }
        if (isset($_SESSION['username'])) {
            redirect(url('user/profile'));
        }
        $this->set(get_defined_vars());
    }
}
