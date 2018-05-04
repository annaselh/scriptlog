<?php
/**
 * File init.php
 * to initialize or instantiate an object
 * 
 * @author  Maoelana Noermoehammad
 * @license MIT
 * 
 */
require 'Scriptloader.php';

$loader = new Scriptloader();
$loader -> setLibraryPaths(array(
  APP_ROOT . APP_LIBRARY . '/core/',
  APP_ROOT . APP_LIBRARY . '/dao/',
  APP_ROOT . APP_LIBRARY . '/plugins/'
));

$loader -> runLoader();

$dbc = DbFactory::connect(['mysql:host='.$config['db']['host'].';dbname='.$config['db']['name'], 
       $config['db']['user'], $config['db']['pass']   
       ]);

Registry::setAll(array('dbc' => $dbc, 'route' => $rules));

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