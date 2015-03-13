<?php

function validate_password()            
{
    $pass = Login::getPasswords();
    return ($pass[0] == $pass[1] ? true : false);
}

