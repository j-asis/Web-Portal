<?php

function objectToArray($obj)
{
    if (is_array($obj)) {
        return $obj;
    }
    if (!is_object($obj)) {
        return false;
    }
    $array = array();
    foreach ( $obj as $key => $value ) {
        $array[$key] = $value;
    }
    return $array;
}
