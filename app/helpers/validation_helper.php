<?php

function validate_between($check, $min, $max)            
{
    $n = mb_strlen(trim($check));
                        
    return $min <= $n && $n <= $max;
}
