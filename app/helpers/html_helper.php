<?php

function readable_text($string)
{
    if (!isset($string)) return;
    $string = htmlspecialchars($string, ENT_QUOTES);
    $string = nl2br(trim($string));
    echo $string;
}

function redirect($url)
{
    header("Location: $url");
}

function time_difference($date)
{
    $second = 60;
    $minute = 60;
    $hour = 24;
    $yesterday = 1;
    $week = 7;
    $date = strtotime($date);
    $now = strtotime(date("Y-m-d H:i:s"));
    $today = strtotime(date("Y-m-d"));
    $day = strtotime(date("Y-m-d", $date));
    $difference = $now - $date;
    $time = date("h:ia", $date);
    $date_difference = ($today - $day) / ($second*$minute*$hour);
    $time_frame = array($second, $minute, $hour);
    $time_title = array('second', 'minute', 'hour');
    
    if ($date_difference === $yesterday) {
        return "yesterday at " . $time;
    } elseif ($date_difference > $yesterday && $date_difference < $week) {
        return $date_difference . " days ago";
    }
    $count = count($time_frame);
    for ($i = 0; $i < $count; $i++) {
        $difference = $difference / $time_frame[$i];
        if ($difference > 1) {
            continue;
        }
        $num = floor($difference * $time_frame[$i]);
        $title = $num > 1 ? $time_title[$i] . "s" : $time_title[$i];
        return isset($num) ? "$num $title ago" : $day;
    }
    return date("Y-m-d h:ia", $date);
}
