<?php

class RegisterController extends AppController 
{
    public function index()
    {
        $check = Param::get('call',false);
        $error = false;
        if ($check) {
            $params = array(
                'username' => Param::get('username'),
                'first_name' => Param::get('first_name'),
                'last_name' => Param::get('last_name'),
                'email' => Param::get('email'),
                'password' => Param::get('password'),
                'cpassword' => Param::get('cpassword'),
            );
            $register = new Register($params);
            $register->validate_password();
            $register->user_exists();
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
