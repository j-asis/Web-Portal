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
                'cpassword'  => Param::get('cpassword', ''),
            );
            $register = new Register($params);
            if (!($register->password === $register->cpassword)) {
                $register->validation_errors['password']['match'] = true;
            }
            if ($register->userExists($register->username)) {
                $register->validation_errors['username']['exists'] = true;
            }
            if ($register->emailExists($register->email)) {
                $register->validation_errors['email']['exists'] = true;
            }
            $register->validate();
            if ($register->hasError()) {                    
                $error = true;
            } else {
                $register->create();
            }
        }
        $this->set(get_defined_vars());
        if ($error) {
            $this->render('index');
        }
    }
}
