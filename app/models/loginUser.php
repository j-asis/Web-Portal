<?php

class loginUser extends AppModel
{
    public $validation = array(
        'password' => array('length'=>array('validate_between',8,50)),
        'username' => array('length'=>array('validate_between',1,50))
        );
}