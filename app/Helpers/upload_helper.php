<?php

/* 
    The rename functions check if the file name exists in one of the
    upload folders. If it does, it will rename the file with a number
    to avoid duplicate file names.
*/

function get_time_from_seconds($totalSeconds) {
    $totalSeconds = intval($totalSeconds);
    $time = [
        'hours' => 0,
        'minutes' => 0,
        'seconds' => 0,
    ];
    $time['seconds'] = $totalSeconds % 60;

    $totalMinutes = floor($totalSeconds / 60);

    $time['minutes'] = ($totalMinutes > 60) ? $totalMinutes % 60 : $totalMinutes;
    
    $totalHours = floor($totalMinutes / 60);

    $time['hours'] = $totalHours;

    return $time;

}

?>