<?php

$installation_path = __DIR__ . '/../install/';

$current_path = __DIR__ . '/';

$path = dirname($current_path) . '/';

echo 'Installation Path: '. $installation_path . '<br>';

echo 'Current Path: '. $current_path .'<br>';

echo 'Path: ' . $path;