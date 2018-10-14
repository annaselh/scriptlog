<?php 

require 'library/main.php';

$dispatcher = new Dispatcher();
$dispatcher -> dispatch();

$time_end = microtime(true);
$time = $time_end - SCRIPTLOG_START_TIME;
echo "<br>$time seconds\n";
echo "<br>\n Memory Consumption is   ";
echo round(memory_get_usage()/1048576,2).''.' MB';