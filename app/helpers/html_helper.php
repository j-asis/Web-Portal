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
    $date = strtotime($date);
    $now = strtotime(date("Y-m-d H:i:s"));
    $today = strtotime(date("Y-m-d"));
    $day = strtotime(date("Y-m-d",$date));
    $difference = $now - $date;
    $time = date("h:ia",$date);
    $date_difference = ($today - $day) / (60*60*24);
    $time_frame = array(60,60,24);
    $time_title = array('second','minute','hour');
    
    if ($date_difference === 1) {
        return "yesterday at " . $time;
    } elseif ($date_difference > 1 && $date_difference < 7) {
        return $date_difference . " days ago";
    }

    for ($i = 0; $i < 3; $i++) {
        $difference = $difference / $time_frame[$i];
        if ($difference > 1) {
            continue;
        }
        $num = floor($difference * $time_frame[$i]);
        $title = $num > 1 ? $time_title[$i] . "s" : $time_title[$i];
        return isset($num) ? "$num $title ago" : $day;
    }
    
}
