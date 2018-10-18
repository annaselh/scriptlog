<?php

#include __DIR__ . '/../library/main-reserve.php';
include 'convert.php';
/*
class MyClass 
{

}

function buildObjects()
{
  $objectA = new MyClass();
  $objectB = new MyClass();

  $objectA->b = $objectB;
  $objectB->a = $objectA;

  return $objectA;

}

$leakHolder = [];
for ($i=0; $i < 200000; $i++) { 

    $object = buildObjects();

    if ($i % 10 === 0) {
        $leakHolder[] = $object;
    }

}

gc_collect_cycles();
echo convert(memory_get_usage());
*/

$link = new mysqli("localhost", "root", "kartatopia", "blog");

if (mysqli_connect_errno()) {
  printf("Connect failed: %s\n", mysqli_connect_error());
  exit();
}

/* Print server version */
printf("Server version: %s\n", $link->server_info);
print("<br>");
/* print server version */
printf("Server version: %d\n", $link->server_version);
print("<br>");
print("<pre>");
$mysql_version = $link->server_info;
preg_match("/^[0-9\.]+/", $mysql_version, $match);
$mysql_version = $match[0];

if (version_compare($mysql_version, "5.6.0", ">=")) {
  print("You can continue installation. Your server version is ".$mysql_version);
} else {
  print("Your environment is not supported. Your server version is ".$mysql_version);
}
print("</pre>");

$link -> close();