<?php

class RegisterController extends AppController 
{
    public function index()
    {
        $register = new Register();
        $check = Param::get('call');
        $error = false;
        if ($check) {
            $register->username = Param::get('username');
            $register->first_name = Param::get('first_name');
            $register->last_name = Param::get('last_name');
            $register->email = Param::get('email');
            $register->password = Param::get('password');
            $register->cpassword = Param::get('cpassword');
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
