<?php

function validate_password()            
{
    $pass = Register::getPasswords();
    return ($pass[0] == $pass[1] ? true : false);
}

