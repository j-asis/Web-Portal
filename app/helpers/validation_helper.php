<?php

function validate_between($check, $min, $max)            
{
    $n = mb_strlen(trim($check));
    return $min <= $n && $n <= $max;
}

function validate_username($username)
{
    return preg_match('/^[a-zA-Z0-9._-]+$/', $username);
}

function validate_name($string)
{
    return preg_match('/^[a-zA-Z -]+$/', $string);
}
