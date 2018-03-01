<?php

// fungsi batas waktu
function time_keeper()
{
    $time_limit = 10440;
    $_SESSION ['timeOut'] = time() + $time_limit;
}

// fungsi validasi batas waktu
function validate_time_login()
{
    $timeOut = $_SESSION['timeOut'];
    
    if (time() < $timeOut) {
        time_keeper();
        return true;
    } else {
        unset( $_SESSION['timeOut'] );
        return false;
    }
}
