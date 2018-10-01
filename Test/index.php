<?php

require_once(__DIR__ . '/../library/main-reserve.php');
/*
$classes_dir = dirname(__FILE__). '/classes/';

echo $classes_dir;

$users = new User();
$data = $users -> getUsers();
*/

$path_root = APP_ROOT;

echo $path_root . '<br>';

if (file_exists(APP_ROOT . 'public/themes/soerabaia/theme.ini')) {

$data_ini = parse_ini_file(APP_ROOT . 'public/themes/soerabaia/theme.ini');

print('<pre>'.$data_ini['theme_name'].'</pre>');

$menus = new Menu();
$getMenus = $menus->findMenus();

var_dump($getMenus) . '<br>';

print_r($getMenus);


}
