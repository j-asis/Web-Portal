<?php

class RegisterController extends AppController 
{
    public function index()
    {
        if (isset($_SESSION['username'])) {
            redirect(url('user/profile'));
        }
        $check = Param::get('call', false);
        $error = false;
        if ($check) {
            $params = array(
                'username'   => Param::get('username', ''),
                'first_name' => Param::get('first_name', ''),
                'last_name'  => Param::get('last_name', ''),
                'email'      => Param::get('email', ''),
                'password'   => Param::get('password', ''),
                'confirm_password'  => Param::get('confirm_password', ''),
            );
            $register = new Register($params);
            try {
                $register->create();
            } catch (ValidationException $e) {
                $error = true;
            }
        }
        $this->set(get_defined_vars());
        if ($error) {
            $this->render('index');
        }
    }
}
