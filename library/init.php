<?php

require 'Scriptloader.php';

$loader = new Scriptloader();
$loader -> setLibraryPaths(array(
  APP_ROOT . APP_LIBRARY . '/classes/',
  APP_ROOT . APP_LIBRARY . '/core/',
  APP_ROOT . APP_LIBRARY . '/plugins/'
));

$loader -> runLoader();

$dbc = new PDO('mysql:host='.$config['database']['host'].';dbname='.$config['database']['name'], 
    $config['database']['user'], $config['database']['pass']);
$dbc -> setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
$dbc -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

Registry::setAll(array('dbc' => $dbc, 'route' => $rules));

$authentication = new Authentication($dbc);
$configurations = new Configuration($dbc);
$searchPost = new SearchSeeker($dbc);
$frontPaginator = new Paginator(10, 'p');
$postFeeds = new RssFeed($dbc);
$dispatching = new Dispatcher();

//set_exception_handler('LogError::exceptionHandler');
//set_error_handler('LogError::errorHandler');

if (!isset($_SESSION)) {
    
    session_start();
    
}

ob_start();