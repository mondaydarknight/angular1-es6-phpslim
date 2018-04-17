<?php

namespace App;

global $app;

$app->get('/', 'App\Controller\CoreController:pageIndex');


