<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once __DIR__ . '/../src/Lib/App.php';

$app = new App();
$app->run();
$app->display();