<?php
function get_time_difference($date1, $date2) {
    $difference = $date1->getTimestamp() - $date2->getTimestamp();

    if( $difference < 60) {
        return 'moments ago';
    }
    $minutes = floor($difference / 60);
    if( $minutes < 60) {
        return ($minutes == 1) ? '1 minute ago' : strval($minutes) .' minutes ago';
    }
    $hours = floor($minutes / 60);
    if($hours < 24) {
        return ($hours == 1) ? '1 hour ago' : strval($hours) .' hours ago' ;
    }
    $days = floor($hours / 24);
    if($days < 30) {
        return ($days == 1) ? '1 day ago': strval($days) . ' days ago';
    }
    $months = floor($days / 30);
    if($months < 12) {
        return ($months == 1) ? '1 month ago': strval($months) . ' months ago';
    }
    $years = floor($days / 365);
    if($years > 1) {
        return ($years == 1) ?'1 year ago': strval($years) . ' years ago';
    }
    return '11 months ago';
    
}

function nameOfFile($filename) {
    $splitIndex = 0;
    for($i = strlen($filename) - 1; $i >= 0; $i--) {
        if($filename[$i] == '.') {
            $splitIndex = $i;
            break;
        }
    }

    return substr($filename,0 ,$splitIndex);
}

function getThumbnailURL($caption, $type) {
    $captionArr = explode('.', $caption);
    $extension = end($captionArr);
    return base_url('/writable/uploads/'.$type.'/'.nameOfFile($caption) . '_thumb.' . $extension);
}

function trimDurationText($duration) {
    $i = 1;
    while($i < 4 && in_array($duration[$i], array('0', ':'))) $i++;
    return substr($duration,$i);
}   

function dateTimeToDate($datetime) {
    $months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
    $date = explode(" ", $datetime)[0];
    $dateArray = explode("-", $date);
    
    return $months[$dateArray[1]-1] . " " . $dateArray[2] . ", " . $dateArray[0];
}

?>