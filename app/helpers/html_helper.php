<?php

function readable_text($string)
{
    if (!isset($string)) return;
    $string = htmlspecialchars($string, ENT_QUOTES);
    $string = nl2br($string);
    echo $string;
}

function redirect($url)
{
    header("Location: $url");
}

function time_difference($date){
    $seconds_difference = strtotime(date("Y-m-d H:i:s")) - strtotime($date);
    if ($seconds_difference > 60*60*24) {
        return floor($seconds_difference / (60*60*24)) > 1 ? floor($seconds_difference / (60*60*24)) . " Days ago" : floor($seconds_difference / (60*60*24)) . " Day ago";
    } elseif ($seconds_difference > 60*60) {
        return floor($seconds_difference / (60*60)) > 1 ? floor($seconds_difference / (60*60)) . " Hours ago" : floor($seconds_difference / (60*60)) . " Hour ago";
    } elseif ($seconds_difference > 60) {
        return floor($seconds_difference / (60)) > 1 ? floor($seconds_difference / (60)) . " Minutes ago" : floor($seconds_difference / (60)) . " Minute ago";
    } else {
        return floor($seconds_difference) . " Seconds ago";
    }
}
