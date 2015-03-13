<?php

class User extends AppModel
{
    public function logOut(){
        session_unset('username');
        session_destroy();
    }
}
