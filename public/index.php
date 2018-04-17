<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Application;

define('ROOT_DIR', realpath(__DIR__.'/..'));

$app = new Application;
$app->run();

